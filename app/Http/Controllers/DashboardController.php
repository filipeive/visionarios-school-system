<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Grade;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Dashboard baseado no tipo de usuário
        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'secretary':
                return $this->secretaryDashboard();
            case 'pedagogy':
                return $this->pedagogyDashboard();
            case 'teacher':
                return $this->teacherDashboard();
            case 'parent':
                return $this->parentDashboard();
            default:
                return $this->basicDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_students' => Student::active()->count(),
            'total_teachers' => Teacher::active()->count(),
            'total_classes' => ClassRoom::active()->currentYear()->count(),
            'monthly_revenue' => Payment::paid()
                ->whereMonth('payment_date', now()->month)
                ->sum('amount'),
            'pending_payments' => Payment::pending()->count(),
            'overdue_payments' => Payment::overdue()->count(),
            'todays_events' => Event::today()->count(),
            'total_enrollments' => Enrollment::active()->currentYear()->count(),
        ];

        $recentActivities = $this->getRecentActivities();
        $upcomingEvents = Event::upcoming()->take(5)->get();
        $monthlyStats = $this->getMonthlyStats();
        $classStats = $this->getClassStats();

        return view('dashboard.admin', compact(
            'stats', 'recentActivities', 'upcomingEvents', 'monthlyStats', 'classStats'
        ));
    }

    private function secretaryDashboard()
    {
        $stats = [
            'total_students' => Student::active()->count(),
            'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
            'monthly_revenue' => Payment::paid()
                ->whereMonth('payment_date', now()->month)
                ->sum('amount'),
            'pending_payments' => Payment::pending()->count(),
            'overdue_payments' => Payment::overdue()->count(),
            'todays_payments' => Payment::paid()
                ->whereDate('payment_date', today())
                ->count(),
        ];

        $recentPayments = Payment::with(['student'])
            ->latest()
            ->take(10)
            ->get();
            
        $overduePayments = Payment::overdue()
            ->with(['student'])
            ->take(10)
            ->get();

        return view('dashboard.secretary', compact(
            'stats', 'recentPayments', 'overduePayments'
        ));
    }

    private function pedagogyDashboard()
    {
        $stats = [
            'total_students' => Student::active()->count(),
            'total_teachers' => Teacher::active()->count(),
            'total_classes' => ClassRoom::active()->currentYear()->count(),
            'average_attendance' => $this->calculateAverageAttendance(),
            'pending_grades' => $this->getPendingGradesCount(),
            'upcoming_exams' => Event::where('type', 'exam')->upcoming()->count(),
        ];

        $classPerformance = $this->getClassPerformance();
        $attendanceStats = $this->getAttendanceStats();
        $teacherStats = $this->getTeacherStats();

        return view('dashboard.pedagogy', compact(
            'stats', 'classPerformance', 'attendanceStats', 'teacherStats'
        ));
    }

    private function teacherDashboard()
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        
        if (!$teacher) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Complete seu perfil de professor para acessar o dashboard.');
        }

        $myClasses = ClassRoom::where('teacher_id', $teacher->id)
            ->active()
            ->currentYear()
            ->with(['students'])
            ->get();

        $stats = [
            'my_classes' => $myClasses->count(),
            'total_students' => $myClasses->sum('current_students'),
            'todays_attendance' => Attendance::whereIn('class_id', $myClasses->pluck('id'))
                ->today()
                ->count(),
            'pending_grades' => Grade::where('teacher_id', $teacher->id)
                ->whereNull('grade')
                ->count(),
        ];

        $todaysSchedule = $this->getTodaysSchedule($teacher->id);
        $recentGrades = Grade::where('teacher_id', $teacher->id)
            ->with(['student', 'subject'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.teacher', compact(
            'stats', 'myClasses', 'todaysSchedule', 'recentGrades'
        ));
    }

    private function parentDashboard()
    {
        $parent = auth()->user()->parent;
        
        if (!$parent) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Complete seu perfil para acessar as informações dos seus filhos.');
        }

        $children = $parent->students()->with([
            'currentEnrollment.class',
            'grades' => function($q) { $q->currentYear(); },
            'payments' => function($q) { $q->whereYear('year', now()->year); }
        ])->get();

        $stats = [
            'total_children' => $children->count(),
            'pending_payments' => Payment::whereIn('student_id', $children->pluck('id'))
                ->pending()
                ->count(),
            'overdue_payments' => Payment::whereIn('student_id', $children->pluck('id'))
                ->overdue()
                ->count(),
            'total_paid_this_year' => Payment::whereIn('student_id', $children->pluck('id'))
                ->paid()
                ->whereYear('year', now()->year)
                ->sum('amount'),
        ];

        $upcomingEvents = Event::where('target_audience', 'parents')
            ->orWhere('target_audience', 'all')
            ->upcoming()
            ->take(5)
            ->get();

        return view('dashboard.parent', compact(
            'stats', 'children', 'upcomingEvents'
        ));
    }

    private function basicDashboard()
    {
        return view('dashboard.basic');
    }

    // Métodos auxiliares
    private function getRecentActivities()
    {
        // Implementar log de atividades recentes
        return collect();
    }

    private function getMonthlyStats()
    {
        $months = [];
        $revenues = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M/Y');
            
            $revenue = Payment::paid()
                ->whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
                
            $revenues[] = $revenue;
        }

        return [
            'months' => $months,
            'revenues' => $revenues
        ];
    }

    private function getClassStats()
    {
        return ClassRoom::active()
            ->currentYear()
            ->selectRaw('grade_level, COUNT(*) as count, SUM(current_students) as total_students')
            ->groupBy('grade_level')
            ->get();
    }

    private function calculateAverageAttendance()
    {
        $totalAttendances = Attendance::thisMonth()->count();
        $presentAttendances = Attendance::thisMonth()->present()->count();
        
        if ($totalAttendances == 0) return 0;
        
        return round(($presentAttendances / $totalAttendances) * 100, 1);
    }

    private function getPendingGradesCount()
    {
        // Implementar lógica para contar avaliações pendentes
        return 0;
    }

    private function getClassPerformance()
    {
        return ClassRoom::active()
            ->currentYear()
            ->with(['students.grades' => function($q) {
                $q->currentYear();
            }])
            ->get()
            ->map(function($class) {
                $averageGrade = $class->students
                    ->flatMap(function($student) {
                        return $student->grades;
                    })
                    ->avg('grade');
                    
                return [
                    'class_name' => $class->name,
                    'average_grade' => round($averageGrade ?? 0, 2),
                    'total_students' => $class->current_students
                ];
            });
    }

    private function getAttendanceStats()
    {
        $last7Days = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $totalAttendances = Attendance::whereDate('attendance_date', $date)->count();
            $presentAttendances = Attendance::whereDate('attendance_date', $date)
                ->present()
                ->count();
                
            $percentage = $totalAttendances > 0 ? 
                round(($presentAttendances / $totalAttendances) * 100, 1) : 0;
                
            $last7Days[] = [
                'date' => $date->format('d/m'),
                'percentage' => $percentage
            ];
        }
        
        return $last7Days;
    }

    private function getTeacherStats()
    {
        return Teacher::active()
            ->withCount(['classes' => function($q) {
                $q->active()->currentYear();
            }])
            ->get()
            ->map(function($teacher) {
                return [
                    'name' => $teacher->full_name,
                    'classes_count' => $teacher->classes_count,
                    'specialization' => $teacher->specialization
                ];
            });
    }

    private function getTodaysSchedule($teacherId)
    {
        // Implementar lógica para buscar horário do professor
        return collect();
    }
}