<?php

// app/Http/Controllers/GradeController.php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGradeRequest;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
    /**
     * Display a listing of the grades.
     */
    public function index(Request $request)
    {
        $this->authorize('view_grades');

        $query = Grade::with(['student', 'subject', 'teacher']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('term')) {
            $query->where('term', $request->term);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('assessment_type')) {
            $query->where('assessment_type', $request->assessment_type);
        }

        $grades = $query->latest()->paginate(25);
        $subjects = Subject::active()->get();
        $classes = ClassRoom::active()->get();
        $currentYear = current_school_year();

        return view('grades.index', compact('grades', 'subjects', 'classes', 'currentYear'));
    }

    /**
     * Show the form for creating a new grade.
     */
    public function create()
    {
        $this->authorize('create_grades');

        $students = Student::active()->with('currentEnrollment.class')->get();
        $subjects = Subject::active()->get();
        $classes = ClassRoom::active()->get();
        $currentYear = current_school_year();

        return view('grades.create', compact('students', 'subjects', 'classes', 'currentYear'));
    }

    /**
     * Store a newly created grade.
     */
    public function store(StoreGradeRequest $request)
    {
        $this->authorize('create_grades');

        try {
            $user = $request->user();

            // Verificar se o aluno está ativo
            $student = Student::findOrFail($request->student_id);
            if (! in_array($student->status, ['active', 'pending_renewal'])) {
                return back()->with('error', 'Não é possível atribuir nota a um aluno inativo.')->withInput();
            }

            // Determinar teacher_id
            $teacherId = $user->teacher?->id;
            if (! $teacherId && ! $user->hasRole('admin')) {
                return back()->with('error', 'Apenas professores podem atribuir notas.')->withInput();
            }

            // Verificar se o aluno está matriculado na turma
            $isEnrolled = \App\Models\Enrollment::where('student_id', $request->student_id)
                ->where('class_id', $request->class_id)
                ->where('status', 'active')
                ->exists();

            if (! $isEnrolled) {
                return back()->with('error', 'O aluno não está matrículado nesta turma.')->withInput();
            }

            $grade = Grade::create([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'grade' => $request->grade,
                'assessment_type' => $request->assessment_type,
                'term' => $request->term,
                'year' => $request->year,
                'date_recorded' => $request->date_recorded,
                'teacher_id' => $teacherId,
                'comments' => $request->comments,
            ]);

            return redirect()->route('grades.index')
                ->with('success', 'Nota atribuída com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao criar nota: '.$e->getMessage(), [
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Erro ao atribuir nota. Contacte o administrador.')->withInput();
        }
    }

    /**
     * Show the form for batch creating grades.
     */
    public function batchCreate(Request $request)
    {
        $this->authorize('create_grades');

        $classId = $request->get('class_id');
        $subjectId = $request->get('subject_id');
        $term = $request->get('term', 1);
        $year = $request->get('year', current_school_year());
        $assessmentType = $request->get('assessment_type', 'test');

        $classes = ClassRoom::active()->get();
        $subjects = Subject::active()->get();

        $students = collect();
        $existingGrades = collect();

        if ($classId && $subjectId) {
            $students = Student::whereHas('currentEnrollment', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            })->active()->with('currentEnrollment.class')->get();

            // Buscar notas existentes para esta combinação
            $existingGrades = Grade::whereIn('student_id', $students->pluck('id'))
                ->where('subject_id', $subjectId)
                ->where('term', $term)
                ->where('year', $year)
                ->where('assessment_type', $assessmentType)
                ->get()
                ->keyBy('student_id');
        }

        return view('grades.batch-create', compact(
            'classes',
            'subjects',
            'students',
            'existingGrades',
            'classId',
            'subjectId',
            'term',
            'year',
            'assessmentType'
        ));
    }

    /**
     * Store batch grades.
     */
    public function batchStore(Request $request)
    {
        $this->authorize('create_grades');

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'term' => 'required|in:1,2,3',
            'year' => 'required|integer|min:2020|max:2030',
            'assessment_type' => 'required|in:test,assignment,exam,project,participation,ACS1,ACS2,ACS3,ACP,ACF,behavioral',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.grade' => 'nullable|numeric|min:0|max:20',
        ]);

        try {
            DB::beginTransaction();

            $user = $request->user();
            $teacherId = $user->teacher?->id;

            if (! $teacherId) {
                throw new \Exception('Apenas professores podem atribuir notas.');
            }

            $successCount = 0;
            $updatedCount = 0;

            foreach ($request->grades as $gradeData) {
                if (! empty($gradeData['grade'])) {
                    // Verificar se já existe uma nota para este aluno
                    $existingGrade = Grade::where('student_id', $gradeData['student_id'])
                        ->where('subject_id', $request->subject_id)
                        ->where('term', $request->term)
                        ->where('year', $request->year)
                        ->where('assessment_type', $request->assessment_type)
                        ->first();

                    if ($existingGrade) {
                        // Atualizar nota existente
                        $existingGrade->update([
                            'grade' => $gradeData['grade'],
                            'date_recorded' => now(),
                            'teacher_id' => $teacherId,
                            'comments' => $gradeData['comments'] ?? null,
                        ]);
                        $updatedCount++;
                    } else {
                        // Criar nova nota
                        Grade::create([
                            'student_id' => $gradeData['student_id'],
                            'class_id' => $request->class_id,
                            'subject_id' => $request->subject_id,
                            'grade' => $gradeData['grade'],
                            'assessment_type' => $request->assessment_type,
                            'term' => $request->term,
                            'year' => $request->year,
                            'date_recorded' => now(),
                            'teacher_id' => $teacherId,
                            'comments' => $gradeData['comments'] ?? null,
                        ]);
                        $successCount++;
                    }
                }
            }

            DB::commit();

            $message = 'Notas processadas com sucesso! ';
            $message .= "{$successCount} novas notas adicionadas, ";
            $message .= "{$updatedCount} notas atualizadas.";

            return redirect()->route('grades.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar notas em lote: '.$e->getMessage());

            return back()->with('error', 'Erro ao processar notas. Contacte o administrador.')->withInput();
        }
    }

    /**
     * Show the form for editing the specified grade.
     */
    public function edit(Grade $grade)
    {
        $this->authorize('edit_grades');

        // Verificar se o usuário pode editar esta nota
        $user = request()->user();
        if ($user->hasRole('teacher') && $grade->teacher_id !== $user->teacher?->id) {
            return redirect()->route('grades.index')
                ->with('error', 'Você só pode editar as suas próprias notas.');
        }

        $students = Student::active()->get();
        $subjects = Subject::active()->get();

        return view('grades.edit', compact('grade', 'students', 'subjects'));
    }

    /**
     * Update the specified grade.
     */
    public function update(Request $request, Grade $grade)
    {
        $this->authorize('edit_grades');

        // Verificar se o usuário pode editar esta nota
        $user = $request->user();
        if ($user->hasRole('teacher') && $grade->teacher_id !== $user->teacher?->id) {
            return redirect()->route('grades.index')
                ->with('error', 'Você só pode editar as suas próprias notas.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|numeric|min:0|max:20',
            'assessment_type' => 'required|in:test,assignment,exam,project,participation,ACS1,ACS2,ACS3,ACP,ACF,behavioral',
            'term' => 'required|in:1,2,3',
            'year' => 'required|integer|min:2020|max:2030',
            'class_id' => 'required|exists:classes,id',
            'date_recorded' => 'required|date',
            'comments' => 'nullable|string|max:500',
        ]);

        try {
            $grade->update([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'grade' => $request->grade,
                'assessment_type' => $request->assessment_type,
                'term' => $request->term,
                'year' => $request->year,
                'date_recorded' => $request->date_recorded,
                'comments' => $request->comments,
            ]);

            return redirect()->route('grades.index')
                ->with('success', 'Nota atualizada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar nota: '.$e->getMessage());

            return back()->with('error', 'Erro ao atualizar nota. Contacte o administrador.')->withInput();
        }
    }

    /**
     * Display student report card.
     */
    public function reportCard(Student $student)
    {
        $this->authorize('view_grades');

        $student->load(['grades.subject', 'currentEnrollment.class']);

        $currentYear = current_school_year();
        $terms = [1, 2, 3];

        $gradesByTerm = [];
        foreach ($terms as $term) {
            $gradesByTerm[$term] = $student->grades()
                ->where('year', $currentYear)
                ->where('term', $term)
                ->with('subject')
                ->get()
                ->groupBy('subject_id');
        }

        $subjects = Subject::active()->get();
        $currentEnrollment = $student->currentEnrollment;

        return view('grades.report-card', compact(
            'student',
            'gradesByTerm',
            'subjects',
            'currentEnrollment',
            'currentYear'
        ));
    }

    /**
     * Display class grade report.
     */
    public function classReport(ClassRoom $class)
    {
        $this->authorize('view_grades');

        $class->load([
            'students.grades' => function ($query) {
                $query->where('year', current_school_year());
            },
            'subjects',
        ]);

        $terms = [1, 2, 3];
        $currentYear = current_school_year();

        return view('grades.class-report', compact('class', 'terms', 'currentYear'));
    }

    /**
     * Display grade sheet for a class.
     */
    public function gradeSheet(ClassRoom $class, Request $request)
    {
        $this->authorize('view_grades');

        $subjectId = $request->get('subject_id');
        $term = $request->get('term', 1);
        $year = $request->get('year', current_school_year());

        $class->load([
            'students' => function ($query) {
                $query->orderBy('first_name')->orderBy('last_name');
            },
        ]);

        $subjects = $class->subjects;
        $selectedSubject = $subjectId ? Subject::find($subjectId) : $subjects->first();

        $grades = [];
        if ($selectedSubject) {
            $grades = Grade::whereIn('student_id', $class->students->pluck('id'))
                ->where('subject_id', $selectedSubject->id)
                ->where('term', $term)
                ->where('year', $year)
                ->get()
                ->keyBy('student_id');
        }

        return view('grades.grade-sheet', compact(
            'class',
            'subjects',
            'selectedSubject',
            'grades',
            'term',
            'year'
        ));
    }

    /**
     * Get grade statistics.
     */
    private function getGradeStatistics($grades)
    {
        if ($grades->isEmpty()) {
            return [
                'average' => 0,
                'highest' => 0,
                'lowest' => 0,
                'count' => 0,
            ];
        }

        return [
            'average' => round($grades->avg('grade'), 2),
            'highest' => $grades->max('grade'),
            'lowest' => $grades->min('grade'),
            'count' => $grades->count(),
        ];
    }
}
