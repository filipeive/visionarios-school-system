<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Lista de alunos elegíveis para emissão de Certidões e Certificados.
     */
    public function index(Request $request)
    {
        $this->authorize('view_students');

        $query = Student::with(['currentEnrollment.class', 'enrollments.class']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('first_name')->paginate(15);

        return view('certificates.index', compact('students'));
    }

    /**
     * Emitir Certidão de Habilitações Literárias (MINEDH Moçambique).
     */
    public function certidao(Student $student)
    {
        $this->authorize('view_students');

        $student->load(['enrollments.class', 'parent']);
        $schoolName = Setting::get('school_name', 'ESCOLA SECUNDÁRIA ZAMEDU');
        $province = Setting::get('province', 'MAPUTO');
        $district = Setting::get('district', 'CIDADE DE MAPUTO');
        $directorName = Setting::get('director_name', 'O Director da Escola');
        $secretaryName = Setting::get('secretary_name', 'O Chefe da Secretaria');

        $grades = Grade::where('student_id', $student->id)
            ->with('subject')
            ->get()
            ->groupBy('subject_id');

        $subjectsData = [];
        $totalSum = 0;
        $subjectCount = 0;

        foreach ($grades as $subjectId => $subGrades) {
            $subName = $subGrades->first()->subject->name ?? 'Disciplina';
            $avgGrade = round($subGrades->avg('grade'), 1);
            $subjectsData[] = [
                'name' => $subName,
                'grade' => $avgGrade,
                'grade_words' => $this->gradeToWords($avgGrade),
            ];
            $totalSum += $avgGrade;
            $subjectCount++;
        }

        $finalAverage = $subjectCount > 0 ? round($totalSum / $subjectCount, 1) : 0;
        $finalAverageWords = $this->gradeToWords($finalAverage);

        $pdf = Pdf::loadView('certificates.pdf_certidao', compact(
            'student',
            'schoolName',
            'province',
            'district',
            'directorName',
            'secretaryName',
            'subjectsData',
            'finalAverage',
            'finalAverageWords'
        ));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download("Certidao_Habilitacoes_{$student->student_number}.pdf");
    }

    /**
     * Emitir Certificado Oficial de Conclusão do Ensino (6ª ou 12ª Classe).
     */
    public function certificado(Student $student)
    {
        $this->authorize('view_students');

        $student->load(['enrollments.class']);
        $schoolName = Setting::get('school_name', 'ESCOLA SECUNDÁRIA ZAMEDU');
        $province = Setting::get('province', 'MAPUTO');
        $district = Setting::get('district', 'CIDADE DE MAPUTO');
        $directorName = Setting::get('director_name', 'O Director da Escola');

        $lastEnrollment = $student->enrollments->last();
        $className = $lastEnrollment?->class?->name ?? '12ª Classe';
        $educationLevelName = $lastEnrollment?->class?->education_level_name ?? 'Ensino Secundário Geral';

        $pdf = Pdf::loadView('certificates.pdf_certificado', compact(
            'student',
            'schoolName',
            'province',
            'district',
            'directorName',
            'className',
            'educationLevelName'
        ));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download("Certificado_Conclusao_{$student->student_number}.pdf");
    }

    /**
     * Auxiliar para converter nota em palavras por extenso em português.
     */
    private function gradeToWords($grade): string
    {
        $integerPart = (int) floor($grade);
        $decimalPart = (int) round(($grade - $integerPart) * 10);

        $words = [
            0 => 'Zero', 1 => 'Um', 2 => 'Dois', 3 => 'Três', 4 => 'Quatro',
            5 => 'Cinco', 6 => 'Seis', 7 => 'Sete', 8 => 'Oito', 9 => 'Nove',
            10 => 'Dez', 11 => 'Onze', 12 => 'Doze', 13 => 'Treze', 14 => 'Catorze',
            15 => 'Quinze', 16 => 'Dezasseis', 17 => 'Dezassete', 18 => 'Dezoito',
            19 => 'Dezanove', 20 => 'Vinte'
        ];

        $intWord = $words[$integerPart] ?? (string)$integerPart;
        
        if ($decimalPart > 0) {
            $decWord = $words[$decimalPart] ?? (string)$decimalPart;
            return "{$intWord} valores e {$decWord} décimas";
        }

        return "{$intWord} valores";
    }
}
