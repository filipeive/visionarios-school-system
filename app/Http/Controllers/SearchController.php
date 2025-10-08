<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Payment;
use App\Models\Enrollment;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return redirect()->back()->with('warning', 'Digite pelo menos 2 caracteres para pesquisar.');
        }

        $results = [
            'students' => collect(),
            'teachers' => collect(),
            'classes' => collect(),
            'subjects' => collect(),
            'payments' => collect()
        ];

        // Buscar alunos (se tiver permissão)
        if (auth()->user()->can('view_students')) {
            $results['students'] = Student::where(function($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%")
                      ->orWhere('student_number', 'like', "%{$query}%")
                      ->orWhere('emergency_phone', 'like', "%{$query}%");
                })
                ->active()
                ->with(['currentEnrollment.class'])
                ->limit(20)
                ->get();
        }

        // Buscar professores (se tiver permissão)
        if (auth()->user()->can('view_teachers')) {
            $results['teachers'] = Teacher::where(function($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%")
                      ->orWhere('specialization', 'like', "%{$query}%");
                })
                ->active()
                ->limit(20)
                ->get();
        }

        // Buscar turmas (se tiver permissão)
        if (auth()->user()->can('view_classes')) {
            $results['classes'] = ClassRoom::where('name', 'like', "%{$query}%")
                ->orWhere('classroom', 'like', "%{$query}%")
                ->active()
                ->currentYear()
                ->with(['teacher', 'students'])
                ->limit(10)
                ->get();
        }

        // Buscar disciplinas
        if (auth()->user()->can('view_subjects')) {
            $results['subjects'] = Subject::where('name', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%")
                ->active()
                ->with(['teacher'])
                ->limit(10)
                ->get();
        }

        // Buscar pagamentos (se tiver permissão)
        if (auth()->user()->can('view_payments')) {
            $results['payments'] = Payment::where('reference_number', 'like', "%{$query}%")
                ->orWhereHas('student', function($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%");
                })
                ->with(['student'])
                ->latest()
                ->limit(10)
                ->get();
        }

        $totalResults = array_sum(array_map('count', $results));

        return view('search.results', compact('results', 'query', 'totalResults'));
    }

    public function liveSearch(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Busca rápida de alunos
        if (auth()->user()->can('view_students')) {
            $students = Student::where(function($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%")
                      ->orWhere('student_number', 'like', "%{$query}%");
                })
                ->active()
                ->limit(5)
                ->get()
                ->map(function($student) {
                    return [
                        'type' => 'student',
                        'title' => $student->full_name,
                        'subtitle' => $student->student_number,
                        'url' => route('students.show', $student),
                        'icon' => 'fas fa-user-graduate'
                    ];
                });

            $results = array_merge($results, $students->toArray());
        }

        // Busca rápida de professores
        if (auth()->user()->can('view_teachers')) {
            $teachers = Teacher::where(function($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%");
                })
                ->active()
                ->limit(5)
                ->get()
                ->map(function($teacher) {
                    return [
                        'type' => 'teacher',
                        'title' => $teacher->full_name,
                        'subtitle' => $teacher->specialization,
                        'url' => route('teachers.show', $teacher),
                        'icon' => 'fas fa-chalkboard-teacher'
                    ];
                });

            $results = array_merge($results, $teachers->toArray());
        }

        return response()->json($results);
    }
}