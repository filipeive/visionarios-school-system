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
        $paperInput = strtolower($request->get('paper', 'auto'));

        $data = $this->buildPautaData($class, $term, $year, $type);
        $data['class'] = $class;
        $data['type'] = $type;
        $data['term'] = $term;
        $data['year'] = $year;

        $subjectCount = count($data['subjects']);

        if ($paperInput === 'a3') {
            $paper = 'a3';
        } elseif ($paperInput === 'a4') {
            $paper = 'a4';
        } else {
            $paper = ($subjectCount > 6 && $type === 'trimestral') || ($subjectCount > 9) ? 'a3' : 'a4';
        }

        $data['paper'] = $paper;

        $pdf = Pdf::loadView('pautas.pdf', $data)
            ->setPaper($paper, 'landscape');

        $filename = "Pauta_{$type}_Turma_{$class->name}_{$year}_{$paper}.pdf";

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

                    $macsGrades = $termGrades->whereIn('assessment_type', ['ACS1', 'ACS2', 'ACS3', 'continuous', 'test', 'assignment', 'participation']);
                    $macsMethod = setting('macs_calculation_method', 'launched_only');
                    if ($macsGrades->isNotEmpty()) {
                        if ($macsMethod === 'fixed_count') {
                            $fixedCount = max(1, (int) setting('default_acs_count', 3));
                            $macs = $macsGrades->sum('grade') / $fixedCount;
                        } else {
                            $macs = $macsGrades->avg('grade');
                        }
                    } else {
                        $macs = null;
                    }
                    $acp = $termGrades->whereIn('assessment_type', ['ACP', 'exam', 'ACF'])->first()?->grade;

                    $macsWeight = (float) setting('macs_weight', 2);
                    $acpWeight = (float) setting('acp_weight_in_mt', 1);
                    $totalWeight = $macsWeight + $acpWeight;

                    if ($macs !== null && $acp !== null) {
                        $mt = round(($macsWeight * $macs + $acpWeight * $acp) / $totalWeight, 1);
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

                    // A média final (MF) exige o lançamento dos 3 trimestres (MT1 + MT2 + MT3) / 3
                    $hasAllTerms = ($mt1 !== null && $mt2 !== null && $mt3 !== null);
                    $mf = $hasAllTerms ? round(($mt1 + $mt2 + $mt3) / 3, 1) : null;

                    $ne = $studentSubjectGrades->where('assessment_type', 'exam')->first()?->grade;
                    $nr = $studentSubjectGrades->where('assessment_type', 'ACF')->first()?->grade;

                    $effectiveExam = $nr !== null ? max($ne ?? 0, $nr) : $ne;

                    $includeAcf = setting('include_acf_in_mfd', '0') == '1';
                    $examLevels = array_map('trim', explode(',', setting('exam_class_levels', '6,7,10,12')));
                    $isExamClass = $class && in_array((string)$class->grade_level, $examLevels);

                    if ($includeAcf && $isExamClass && $effectiveExam !== null && $mf !== null) {
                        $acfWeight = (float) setting('acf_weight_in_mfd', 1);
                        $termsWeight = (float) setting('terms_weight_in_mfd', 3);
                        $totalW = $acfWeight + $termsWeight;
                        $mfd = round(($termsWeight * $mf + $acfWeight * $effectiveExam) / $totalW, 1);
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

            $totalSubjectsCount = count($subjects);
            $gradedCount = count($totalAverages);
            $avgMethod = setting('average_method', 'all_subjects');

            if ($gradedCount > 0) {
                if ($avgMethod === 'all_subjects') {
                    $studentData['overall_average'] = round(array_sum($totalAverages) / $totalSubjectsCount, 1);
                } else {
                    $studentData['overall_average'] = round(array_sum($totalAverages) / $gradedCount, 1);
                }
            } else {
                $studentData['overall_average'] = 0;
            }
            $studentData['graded_count'] = $gradedCount;
            $studentData['total_subjects'] = $totalSubjectsCount;

            if ($type === 'trimestral') {
                if (!empty($totalAverages)) {
                    $studentData['final_status'] = $studentData['overall_average'] >= 10 ? 'Aprovado' : 'Retido';
                } else {
                    $studentData['final_status'] = 'Em Curso';
                }
            } else {
                // Pauta Anual ou Final: Aprovado/Retido apenas quando todas as disciplinas e trimestres estiverem concluídos
                $isYearComplete = (count($totalAverages) === count($subjects)) && !empty($totalAverages);

                if ($isYearComplete) {
                    if ($studentData['overall_average'] >= 10 && $failedSubjectsCount <= 2) {
                        $studentData['final_status'] = 'Aprovado';
                        $classStats['approved']++;
                    } else {
                        $studentData['final_status'] = 'Retido';
                        $classStats['failed']++;
                    }
                } else {
                    $studentData['final_status'] = 'Em Curso';
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

        $acsGrades = $termGrades->whereIn('assessment_type', ['ACS1', 'ACS2', 'ACS3', 'test', 'assignment', 'participation']);
        $macsMethod = setting('macs_calculation_method', 'launched_only');
        if ($acsGrades->isNotEmpty()) {
            if ($macsMethod === 'fixed_count') {
                $fixedCount = max(1, (int) setting('default_acs_count', 3));
                $acs = $acsGrades->sum('grade') / $fixedCount;
            } else {
                $acs = $acsGrades->avg('grade');
            }
        } else {
            $acs = null;
        }
        $acp = $termGrades->whereIn('assessment_type', ['ACP', 'exam', 'ACF'])->first()?->grade;

        $macsWeight = (float) setting('macs_weight', 2);
        $acpWeight = (float) setting('acp_weight_in_mt', 1);
        $totalWeight = $macsWeight + $acpWeight;

        if ($acs !== null && $acp !== null) {
            return round(($macsWeight * $acs + $acpWeight * $acp) / $totalWeight, 1);
        }

        return round($termGrades->avg('grade'), 1);
    }
}
