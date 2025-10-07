<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Event;
use App\Models\Communication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherPortalController extends Controller
{
    public function dashboard()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        // Estatísticas
        $stats = [
            'total_classes' => $teacher->classes()->active()->currentYear()->count(),
            'total_students' => $this->getTotalStudents($teacher),
            'today_attendance' => $this->getTodayAttendance($teacher),
            'pending_grades' => $this->getPendingGrades($teacher),
            'upcoming_events' => $this->getUpcomingEvents($teacher),
        ];

        // Turmas do professor
        $myClasses = $teacher->classes()
            ->active()
            ->currentYear()
            ->withCount(['students'])
            ->orderBy('name')
            ->get();

        // Próximos eventos
        $upcomingEvents = Event::where('event_date', '>=', today())
            ->orderBy('event_date')
            ->take(5)
            ->get();
         $newCommsCount = Communication::forTeachers()->published()->recent(7)->count();
        // Comunicações recentes
        $recentCommunications = Communication::where('target_audience', 'teachers')
            ->orWhere('target_audience', 'all')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('teacher-portal.dashboard', compact(
            'teacher', 
            'stats', 
            'myClasses', 
            'upcomingEvents',
            'newCommsCount',
            'recentCommunications'
        ));
    }

    public function myClasses()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $classes = $teacher->classes()
            ->withCount(['students', 'subjects'])
            ->active()
            ->currentYear()
            ->get();

        return view('teacher-portal.classes', compact('teacher', 'classes'));
    }
    //classes
    public function classes()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $classes = $teacher->classes()
            ->withCount(['students', 'subjects'])
            ->active()
            ->currentYear()
            ->get();

        return view('teacher-portal.classes', compact('teacher', 'classes'));
    }
    public function classDetail($classId)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $class = ClassRoom::with(['students', 'subjects'])->findOrFail($classId);
        
        // Verificar se o professor tem acesso a esta turma
        if (!$teacher->classes()->where('classes.id', $classId)->exists()) {
            abort(403, 'Acesso não autorizado a esta turma.');
        }

        $todayAttendance = Attendance::where('class_id', $classId)
            ->whereDate('attendance_date', today())
            ->get()
            ->keyBy('student_id');

        return view('teacher-portal.class-detail', compact('teacher', 'class', 'todayAttendance'));
    }

    public function attendance($classId)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $class = ClassRoom::with(['students'])->findOrFail($classId);
        
        if (!$teacher->classes()->where('classes.id', $classId)->exists()) {
            abort(403, 'Acesso não autorizado.');
        }

        $attendanceDate = request('date', today()->format('Y-m-d'));
        $existingAttendance = Attendance::where('class_id', $classId)
            ->whereDate('attendance_date', $attendanceDate)
            ->get()
            ->keyBy('student_id');

        return view('teacher-portal.attendance', compact(
            'teacher', 
            'class', 
            'attendanceDate',
            'existingAttendance'
        ));
    }

    public function storeAttendance(Request $request, $classId)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        if (!$teacher->classes()->where('classes.id', $classId)->exists()) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate([
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late',
        ]);

        try {
            foreach ($request->attendances as $attendanceData) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $attendanceData['student_id'],
                        'class_id' => $classId,
                        'attendance_date' => $request->attendance_date,
                    ],
                    [
                        'status' => $attendanceData['status'],
                        'marked_by' => Auth::id(),
                        'notes' => $attendanceData['notes'] ?? null,
                        'arrival_time' => $attendanceData['status'] == 'late' ? now() : null,
                    ]
                );
            }

            return redirect()->route('teacher-portal.attendance', $classId)
                ->with('success', 'Presenças registradas com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao registrar presenças: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function grades($classId)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $class = ClassRoom::with(['students', 'subjects'])->findOrFail($classId);
        
        if (!$teacher->classes()->where('classes.id', $classId)->exists()) {
            abort(403, 'Acesso não autorizado.');
        }

        $selectedSubject = request('subject_id', $class->subjects->first()->id ?? null);
        $selectedTerm = request('term', 1);

        $grades = Grade::where('class_id', $classId)
            ->when($selectedSubject, function($query) use ($selectedSubject) {
                return $query->where('subject_id', $selectedSubject);
            })
            ->where('term', $selectedTerm)
            ->where('year', date('Y'))
            ->get()
            ->groupBy(['student_id', 'assessment_type']);

        return view('teacher-portal.grades', compact(
            'teacher',
            'class',
            'selectedSubject',
            'selectedTerm',
            'grades'
        ));
    }

    public function storeGrade(Request $request)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|numeric|min:0|max:20',
            'assessment_type' => 'required|in:test,homework,project,participation',
            'term' => 'required|in:1,2,3',
        ]);

        try {
            if (!$teacher->classes()->where('classes.id', $request->class_id)->exists()) {
                abort(403, 'Acesso não autorizado.');
            }

            Grade::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'assessment_type' => $request->assessment_type,
                    'term' => $request->term,
                    'year' => date('Y'),
                ],
                [
                    'grade' => $request->grade,
                    'teacher_id' => $teacher->id,
                    'date_recorded' => now(),
                    'comments' => $request->comments,
                ]
            );

            return redirect()->back()
                ->with('success', 'Nota registrada com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao registrar nota: ' . $e->getMessage());
        }
    }

    /* public function communications()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        $communications = Communication::where(function($query) {
                $query->where('target_audience', 'teachers')
                      ->orWhere('target_audience', 'all');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('teacher-portal.communications', compact('teacher', 'communications'));
    } */
    public function communications()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        // Temporariamente retornar array vazio
        $communications = collect([]);
        // Ou usar paginação vazia
        // $communications = \Illuminate\Pagination\LengthAwarePaginator::empty();

        return view('teacher-portal.communications', compact('teacher', 'communications'));
    }
    public function profile()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        return view('teacher-portal.profile', compact('teacher'));
    }

    public function updateProfile(Request $request)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        try {
            $teacher->update($request->only(['phone', 'address']));

            return redirect()->back()
                ->with('success', 'Perfil atualizado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar perfil: ' . $e->getMessage());
        }
    }

    // Métodos auxiliares
    private function getTotalStudents($teacher)
    {
        return $teacher->classes()
            ->active()
            ->currentYear()
            ->withCount('students')
            ->get()
            ->sum('students_count');
    }

    private function getTodayAttendance($teacher)
    {
        return Attendance::whereIn('class_id', $teacher->classes()->pluck('classes.id'))
            ->whereDate('attendance_date', today())
            ->where('status', 'present')
            ->count();
    }

    private function getPendingGrades($teacher)
    {
        // Simulação - na prática seria baseado em datas de avaliação
        return $teacher->classes()->active()->currentYear()->count() * 2;
    }

    private function getUpcomingEvents($teacher)
    {
        return Event::where('event_date', '>=', today())
            ->orderBy('event_date')
            ->count();
    }
}