<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Grade extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'student_id',
        'class_id',
        'subject_id',
        'assessment_id',
        'grade',
        'assessment_type',
        'term',
        'year',
        'date_recorded',
        'teacher_id',
        'comments',
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'date_recorded' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['grade', 'assessment_type', 'term'])
            ->logOnlyDirty();
    }

    // ===========================================================
    // SISTEMA DE AVALIAÇÃO DE MOÇAMBIQUE (ENSINO PRIMÁRIO)
    // Diploma Ministerial nº 59/2015
    //
    // Tipos de avaliação por trimestre:
    // - ACS1, ACS2, ACS3: Avaliação Contínua e Sistemática (3 provas)
    // - ACP: Avaliação Contínua Parcial (prova trimestral)
    // - ACF: Avaliação Contínua Final (exame da 6ª classe)
    // ===========================================================

    const TYPE_ACS1 = 'ACS1';

    const TYPE_ACS2 = 'ACS2';

    const TYPE_ACS3 = 'ACS3';

    const TYPE_ACP = 'ACP';

    const TYPE_ACF = 'ACF';

    public static array $continuousTypes = [self::TYPE_ACS1, self::TYPE_ACS2, self::TYPE_ACS3];

    // Relacionamentos
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    // Scopes
    public function scopeCurrentYear($query)
    {
        return $query->where('year', current_school_year());
    }

    public function scopeForTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    public function scopeContinuous($query)
    {
        return $query->whereIn('assessment_type', self::$continuousTypes);
    }

    public function scopeForAssessment($query, $assessmentId)
    {
        return $query->where('assessment_id', $assessmentId);
    }

    // Accessors
    public function getGradeStatusAttribute()
    {
        if ($this->grade >= 17) {
            return 'Muito Bom';
        }
        if ($this->grade >= 14) {
            return 'Bom';
        }
        if ($this->grade >= 10) {
            return 'Suficiente';
        }
        if ($this->grade >= 7) {
            return 'Mediocre';
        }

        return 'Mau';
    }

    public function getFormattedGradeAttribute()
    {
        if ($this->assessment_type === 'behavioral') {
            return $this->comments ?: 'N/A';
        }

        return number_format((float) $this->grade, 1);
    }

    // ===========================================================
    // CÁLCULO DAS MÉDIAS (MÉtodo Moçambicano)
    // ===========================================================

    /**
     * MACS - Média das Avaliações Contínuas e Sistemáticas
     * MACS = (ACS1 + ACS2 + ACS3) / 3
     */
    public static function calculateMACS(int $studentId, ?int $subjectId, int $classId, int $term): ?float
    {
        $query = self::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->whereIn('assessment_type', self::$continuousTypes);

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $grades = $query->get();

        if ($grades->isEmpty()) {
            return null;
        }

        return round($grades->avg('grade'), 1);
    }

    protected static function getMACSWeight(): int
    {
        return (int) setting('macs_weight', 2);
    }

    protected static function getACPWeight(): int
    {
        return (int) setting('acp_weight_in_mt', 1);
    }

    protected static function getMinTermsForFinalGrade(): int
    {
        return (int) setting('min_terms_for_final_grade', 3);
    }

    /**
     * MT - Média Trimestral (Moçambique)
     * MT = (MACS_Weight * MACS + ACP_Weight * ACP) / (MACS_Weight + ACP_Weight)
     */
    public static function calculateMT(int $studentId, ?int $subjectId, int $classId, int $term): ?float
    {
        $macs = self::calculateMACS($studentId, $subjectId, $classId, $term);

        $query = self::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->where('assessment_type', self::TYPE_ACP);

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $acp = $query->avg('grade');

        if ($macs === null || $acp === null) {
            return null;
        }

        $macsWeight = self::getMACSWeight();
        $acpWeight = self::getACPWeight();
        $totalWeight = $macsWeight + $acpWeight;

        return round(($macsWeight * $macs + $acpWeight * $acp) / $totalWeight, 1);
    }

    /**
     * MFD - Média de Frequência por Disciplina
     * MFD = (MT1 + MT2 + MT3) / 3
     *
     * Para passar: MFD >= passing_grade
     */
    public static function calculateMFD(int $studentId, ?int $subjectId, int $classId, int $year): ?float
    {
        $minTerms = self::getMinTermsForFinalGrade();
        $mts = [];

        for ($term = 1; $term <= 3; $term++) {
            $mts[$term] = self::calculateMT($studentId, $subjectId, $classId, $term);
        }

        $validMts = collect($mts)->filter(fn ($v) => $v !== null);

        if ($validMts->count() < $minTerms) {
            return null;
        }

        return round($validMts->avg(), 1);
    }

    /**
     * MF - Média Final (igual à MFD, usado para oboletim)
     */
    public static function calculateMF(int $studentId, ?int $subjectId, int $classId, int $year): ?float
    {
        return self::calculateMFD($studentId, $subjectId, $classId, $year);
    }

    /**
     * Verificar se o aluno está aprovado numa disciplina
     * Aprovado se MFD >= 10
     */
    public static function isApproved(int $studentId, int $subjectId, int $classId, int $year): bool
    {
        $mfd = self::calculateMFD($studentId, $subjectId, $classId, $year);

        return $mfd !== null && $mfd >= 10;
    }

    /**
     * Obter relatório completo das médias do aluno
     */
    public static function getFullReport(int $studentId, int $classId, int $year): array
    {
        $subjects = Subject::active()->get();
        $report = [];

        foreach ($subjects as $subject) {
            $macs1 = self::calculateMACS($studentId, $subject->id, $classId, 1);
            $macs2 = self::calculateMACS($studentId, $subject->id, $classId, 2);
            $macs3 = self::calculateMACS($studentId, $subject->id, $classId, 3);

            $mt1 = self::calculateMT($studentId, $subject->id, $classId, 1);
            $mt2 = self::calculateMT($studentId, $subject->id, $classId, 2);
            $mt3 = self::calculateMT($studentId, $subject->id, $classId, 3);

            $mfd = self::calculateMFD($studentId, $subject->id, $classId, $year);

            $report[$subject->id] = [
                'subject' => $subject->name,
                'macs' => [$macs1, $macs2, $macs3],
                'mt' => [$mt1, $mt2, $mt3],
                'mfd' => $mfd,
                'approved' => $mfd !== null && $mfd >= 10,
            ];
        }

        return $report;
    }
}
