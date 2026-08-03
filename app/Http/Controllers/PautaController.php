<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PautaController extends Controller
{
    /**
     * Pauta Trimestral de uma Turma.
     */
    public function trimestral(ClassRoom $class, Request $request)
    {
        $this->authorize('view_grades');

        $termRaw = $request->get('term', 1);
        $year = (int) $request->get('year', current_school_year());

        if ($termRaw === 'all' || $termRaw === 'final') {
            return redirect()->route('pautas.final', ['class' => $class->id, 'year' => $year]);
        }

        $term = (int) $termRaw;
        $classData = $this->buildPautaData($class, $term, $year, 'trimestral');

        return view('pautas.trimestral', array_merge($classData, [
            'class' => $class,
            'term' => $term,
            'year' => $year,
        ]));
    }

    /**
     * Pauta Anual Consolidada de uma Turma (MT1, MT2, MT3).
     */
    public function anual(ClassRoom $class, Request $request)
    {
        $this->authorize('view_grades');

        $year = (int) $request->get('year', current_school_year());

        $classData = $this->buildPautaData($class, null, $year, 'anual');

        return view('pautas.anual', array_merge($classData, [
            'class' => $class,
            'year' => $year,
        ]));
    }

    /**
     * Pauta Final com Frequência, Exames e Resultados de Promoção.
     */
    public function final(ClassRoom $class, Request $request)
    {
        $this->authorize('view_grades');

        $year = (int) $request->get('year', current_school_year());

        $classData = $this->buildPautaData($class, null, $year, 'final');

        return view('pautas.final', array_merge($classData, [
            'class' => $class,
            'year' => $year,
        ]));
    }

    /**
     * Exportação de Pauta em PDF.
     */
    public function exportPdf(ClassRoom $class, Request $request)
    {
        $this->authorize('view_grades');

        $type = $request->get('type', 'trimestral');
        $term = (int) $request->get('term', 1);
        $year = (int) $request->get('year', current_school_year());

        $data = $this->buildPautaData($class, $term, $year, $type);
        $data['class'] = $class;
        $data['type'] = $type;
        $data['term'] = $term;
        $data['year'] = $year;

        $pdf = Pdf::loadView('pautas.pdf', $data)
            ->setPaper('a4', 'landscape');

        $filename = "Pauta_{$type}_Turma_{$class->name}_{$year}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Auxiliar para calcular e organizar a matriz da pauta.
     */
    private function buildPautaData(ClassRoom $class, ?int $term, int $year, string $type): array
    {
        $students = Student::whereHas('enrollments', function ($q) use ($class) {
            $q->where('class_id', $class->id);
        })
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

        $subjects = $class->subjects->isEmpty() 
            ? Subject::active()->get() 
            : $class->subjects;

        $allGrades = Grade::whereIn('student_id', $students->pluck('id'))
            ->where('year', $year)
            ->get();

        $matrix = [];
        $classStats = [
            'total_students' => $students->count(),
            'approved' => 0,
            'failed' => 0,
            'exam' => 0,
        ];

        foreach ($students as $student) {
            $studentData = [
                'student' => $student,
                'subjects' => [],
                'overall_average' => 0,
                'final_status' => 'Pendente',
            ];

            $totalAverages = [];
            $failedSubjectsCount = 0;

            foreach ($subjects as $subject) {
                $studentSubjectGrades = $allGrades->where('student_id', $student->id)
                    ->where('subject_id', $subject->id);

                if ($type === 'trimestral') {
                    $termGrades = $studentSubjectGrades->where('term', $term);
                    
                    $acs1 = $termGrades->whereIn('assessment_type', ['ACS1', 'continuous', 'test'])->first()?->grade;
                    $acs2 = $termGrades->where('assessment_type', 'ACS2')->first()?->grade;
                    $acs3 = $termGrades->where('assessment_type', 'ACS3')->first()?->grade;

                    $macs = $termGrades->whereIn('assessment_type', ['ACS1', 'ACS2', 'ACS3', 'continuous', 'test', 'assignment', 'participation'])->avg('grade');
                    $acp = $termGrades->whereIn('assessment_type', ['ACP', 'exam', 'ACF'])->first()?->grade;

                    if ($macs !== null && $acp !== null) {
                        $mt = round(($macs * 0.4) + ($acp * 0.6), 1);
                    } else {
                        $mt = round($termGrades->avg('grade') ?? 0, 1);
                    }

                    $studentData['subjects'][$subject->id] = [
                        'acs1' => $acs1 !== null ? round($acs1, 1) : '-',
                        'acs2' => $acs2 !== null ? round($acs2, 1) : '-',
                        'acs3' => $acs3 !== null ? round($acs3, 1) : '-',
                        'macs' => $macs !== null ? round($macs, 1) : '-',
                        'acp' => $acp !== null ? round($acp, 1) : '-',
                        'mt' => $termGrades->isNotEmpty() ? $mt : '-',
                    ];

                    if ($termGrades->isNotEmpty()) {
                        $totalAverages[] = $mt;
                    }
                } else {
                    // Anual ou Final
                    $mt1 = $this->calculateTermAverage($studentSubjectGrades, 1);
                    $mt2 = $this->calculateTermAverage($studentSubjectGrades, 2);
                    $mt3 = $this->calculateTermAverage($studentSubjectGrades, 3);

                    $validTms = array_filter([$mt1, $mt2, $mt3], fn($val) => $val !== null);
                    $mf = !empty($validTms) ? round(array_sum($validTms) / count($validTms), 1) : null;

                    $ne = $studentSubjectGrades->where('assessment_type', 'exam')->first()?->grade;
                    $nr = $studentSubjectGrades->where('assessment_type', 'ACF')->first()?->grade;

                    $effectiveExam = $nr !== null ? max($ne ?? 0, $nr) : $ne;

                    if ($class->isSecondary() && in_array((int)$class->grade_level, [7, 10, 12]) && $effectiveExam !== null) {
                        $mfd = round(($mf * 0.6) + ($effectiveExam * 0.4), 1);
                    } else {
                        $mfd = $mf;
                    }

                    $studentData['subjects'][$subject->id] = [
                        'mt1' => $mt1 ?? '-',
                        'mt2' => $mt2 ?? '-',
                        'mt3' => $mt3 ?? '-',
                        'mf' => $mf ?? '-',
                        'exam' => $effectiveExam ?? '-',
                        'mfd' => $mfd ?? '-',
                    ];

                    if ($mfd !== null) {
                        $totalAverages[] = $mfd;
                        if ($mfd < 10) {
                            $failedSubjectsCount++;
                        }
                    }
                }
            }

            if (!empty($totalAverages)) {
                $studentData['overall_average'] = round(array_sum($totalAverages) / count($totalAverages), 1);
                
                if ($studentData['overall_average'] >= 10 && $failedSubjectsCount <= 2) {
                    $studentData['final_status'] = 'Aprovado';
                    $classStats['approved']++;
                } else {
                    $studentData['final_status'] = 'Retido';
                    $classStats['failed']++;
                }
            }

            $matrix[] = $studentData;
        }

        return [
            'subjects' => $subjects,
            'matrix' => $matrix,
            'stats' => $classStats,
        ];
    }

    private function calculateTermAverage($grades, int $term): ?float
    {
        $termGrades = $grades->where('term', $term);
        if ($termGrades->isEmpty()) {
            return null;
        }

        $acs = $termGrades->whereIn('assessment_type', ['ACS1', 'ACS2', 'ACS3', 'test', 'assignment', 'participation'])->avg('grade');
        $acp = $termGrades->whereIn('assessment_type', ['ACP', 'exam', 'ACF'])->first()?->grade;

        if ($acs !== null && $acp !== null) {
            return round(($acs * 0.4) + ($acp * 0.6), 1);
        }

        return round($termGrades->avg('grade'), 1);
    }
}
