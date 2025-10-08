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
use App\Models\StaffLeaveRequest;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Schema;    
use Illuminate\Support\Facades\Log;

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
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            'pending_payments' => Payment::pending()->count(),
            'overdue_payments' => Payment::overdue()->count(),
            'overdue_amount' => Payment::overdue()->sum('amount'),
            'todays_events' => Event::today()->count(),
            'total_enrollments' => Enrollment::active()->currentYear()->count(),
            'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
            'pending_leave_requests' => StaffLeaveRequest::where('status', 'pending')->count(),
            'new_students_this_month' => Student::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'revenue_change' => $this->calculateRevenueChange(),
            'pending_actions' => Payment::overdue()->count() + 
                               Enrollment::where('status', 'pending')->count() +
                               StaffLeaveRequest::where('status', 'pending')->count()
        ];

        $recentActivities = $this->getRecentActivities();
        $upcomingEvents = Event::with('createdBy')
            ->where('event_date', '>=', now())
            ->where('event_date', '<=', now()->addDays(7))
            ->orderBy('event_date')
            ->limit(5)
            ->get();

        $revenueData = $this->getRevenueData();
        $studentsDistribution = $this->getStudentsDistribution();

        return view('dashboard.admin', compact(
            'stats', 
            'recentActivities', 
            'upcomingEvents', 
            'revenueData',
            'studentsDistribution'
        ));
    }

    private function secretaryDashboard()
    {
        $stats = [
            'total_students' => Student::active()->count(),
            'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
            'monthly_revenue' => Payment::paid()
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            'pending_payments' => Payment::pending()->count(),
            'overdue_payments' => Payment::overdue()->count(),
            'overdue_amount' => Payment::overdue()->sum('amount'),
            'todays_payments' => Payment::paid()
                ->whereDate('payment_date', today())
                ->count(),
            'total_payments_today' => Payment::paid()
                ->whereDate('payment_date', today())
                ->sum('amount'),
            'new_enrollments_month' => Enrollment::where('status', 'active')
                ->whereMonth('created_at', now()->month)
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

        $pendingEnrollments = Enrollment::with(['student', 'class'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.secretary', compact(
            'stats', 
            'recentPayments', 
            'overduePayments',
            'pendingEnrollments'
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
            'total_subjects' => \App\Models\Subject::active()->count(),
            'class_performance_avg' => $this->getAverageClassPerformance(),
        ];

        $classPerformance = $this->getClassPerformance();
        $attendanceStats = $this->getAttendanceStats();
        $teacherStats = $this->getTeacherStats();
        $upcomingExams = Event::where('type', 'exam')
            ->upcoming()
            ->with('class')
            ->take(5)
            ->get();

        return view('dashboard.pedagogy', compact(
            'stats', 
            'classPerformance', 
            'attendanceStats', 
            'teacherStats',
            'upcomingExams'
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
            ->with(['students', 'subjects'])
            ->get();

        $stats = [
            'my_classes' => $myClasses->count(),
            'total_students' => $myClasses->sum(function($class) {
                return $class->students->count();
            }),
            'todays_attendance' => Attendance::whereIn('class_id', $myClasses->pluck('id'))
                ->whereDate('attendance_date', today())
                ->count(),
            'pending_grades' => Grade::where('teacher_id', $teacher->id)
                ->whereNull('grade')
                ->count(),
            'total_subjects' => $myClasses->sum(function($class) {
                return $class->subjects->count();
            }),
            'classes_with_attendance' => Attendance::whereIn('class_id', $myClasses->pluck('id'))
                ->whereDate('attendance_date', today())
                ->distinct('class_id')
                ->count('class_id'),
        ];

        $todaysSchedule = $this->getTodaysSchedule($teacher->id);
        $recentGrades = Grade::where('teacher_id', $teacher->id)
            ->with(['student', 'subject'])
            ->latest()
            ->take(10)
            ->get();

        $myStudents = Student::active()
            ->whereHas('enrollments', function($q) use ($myClasses) {
            $q->whereIn('class_id', $myClasses->pluck('id'))
              ->where('status', 'active');
            })
            ->with(['currentClass'])
            ->get();

        return view('dashboard.teacher', compact(
            'stats', 
            'myClasses', 
            'todaysSchedule', 
            'recentGrades',
            'myStudents',
            'teacher'
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
            'grades' => function($q) { 
                $q->currentYear()->with('subject'); 
            },
            'payments' => function($q) { 
                $q->whereYear('year', now()->year); 
            },
            'attendances' => function($q) {
                $q->whereDate('attendance_date', '>=', now()->subDays(30));
            }
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
            'children_in_school' => $children->where('status', 'active')->count(),
            'average_grades' => $children->avg(function($child) {
                return $child->grades->avg('grade');
            }),
            'attendance_rate' => $this->calculateChildrenAttendanceRate($children),
        ];

        $upcomingEvents = Event::where(function($query) {
                $query->where('target_audience', 'parents')
                      ->orWhere('target_audience', 'all');
            })
            ->upcoming()
            ->take(5)
            ->get();

        $recentGrades = $children->flatMap(function($child) {
            return $child->grades->take(3);
        })->sortByDesc('created_at')->take(5);

        return view('dashboard.parent', compact(
            'stats', 
            'children', 
            'upcomingEvents',
            'recentGrades'
        ));
    }

    private function basicDashboard()
    {
        $stats = [
            'welcome_message' => 'Bem-vindo ao Sistema Visionários',
            'user_name' => auth()->user()->name,
            'last_login' => auth()->user()->last_login_at?->format('d/m/Y H:i') ?? 'Primeiro acesso',
        ];

        return view('dashboard.basic', compact('stats'));
    }

    // ========== MÉTODOS AUXILIARES ATUALIZADOS ==========

    private function calculateRevenueChange()
    {
        $currentMonthRevenue = Payment::paid()
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $lastMonthRevenue = Payment::paid()
            ->whereMonth('payment_date', now()->subMonth()->month)
            ->whereYear('payment_date', now()->subMonth()->year)
            ->sum('amount');

        if ($lastMonthRevenue == 0) {
            return $currentMonthRevenue > 0 ? 100 : 0;
        }

        return round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1);
    }

    private function getRevenueData()
    {
        $months = [];
        $amounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M/Y');
            
            $revenue = Payment::paid()
                ->whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
                
            $amounts[] = $revenue;
        }

        return [
            'months' => $months,
            'amounts' => $amounts
        ];
    }

    private function getStudentsDistribution()
    {
        $distribution = ClassRoom::withCount(['students as students_count' => function($query) {
        $query->where('enrollments.status', 'active');
        }])
        ->where('is_active', true)
        ->where('school_year', 2025)
        ->get();


        return [
            'labels' => $distribution->pluck('name'),
            'data' => $distribution->pluck('students_count')
        ];
    }

    private function getRecentActivities()
    {
        // Atividades recentes - você pode substituir por um sistema de logs real
        $activities = collect();
        
        // Pagamentos recentes
        $recentPayments = Payment::with(['student'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function($payment) {
                return (object)[
                    'type' => 'payment',
                    'icon' => 'money-bill-wave',
                    'title' => 'Pagamento Recebido',
                    'description' => $payment->student->full_name . ' - ' . number_format($payment->amount, 2, ',', '.') . ' MT',
                    'user_name' => 'Sistema',
                    'created_at' => $payment->created_at
                ];
            });

        // Matrículas recentes
        $recentEnrollments = Enrollment::with(['student', 'class'])
            ->where('status', 'active')
            ->latest()
            ->take(3)
            ->get()
            ->map(function($enrollment) {
                return (object)[
                    'type' => 'enrollment',
                    'icon' => 'user-plus',
                    'title' => 'Nova Matrícula',
                    'description' => $enrollment->student->full_name . ' - ' . $enrollment->class->name,
                    'user_name' => 'Secretaria',
                    'created_at' => $enrollment->created_at
                ];
            });

        return $recentPayments->merge($recentEnrollments)->sortByDesc('created_at')->take(5);
    }

    private function calculateAverageAttendance()
    {
        $totalAttendances = Attendance::whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->count();
            
        $presentAttendances = Attendance::whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->where('status', 'present')
            ->count();
        
        if ($totalAttendances == 0) return 0;
        
        return round(($presentAttendances / $totalAttendances) * 100, 1);
    }

    private function getPendingGradesCount()
    {
        return Grade::whereNull('grade')
            ->whereHas('assessment', function($query) {
                $query->where('due_date', '>=', now());
            })
            ->count();
    }

    private function getAverageClassPerformance()
    {
        $average = Grade::whereYear('created_at', now()->year)
            ->avg('grade');
            
        return round($average ?? 0, 1);
    }

    private function getClassPerformance()
    {
        return ClassRoom::active()
            ->currentYear()
            ->with(['students.grades' => function($q) {
                $q->whereYear('created_at', now()->year);
            }])
            ->get()
            ->map(function($class) {
                $grades = $class->students->flatMap->grades;
                $averageGrade = $grades->avg('grade');
                    
                return [
                    'class_name' => $class->name,
                    'average_grade' => round($averageGrade ?? 0, 1),
                    'total_students' => $class->students->count(),
                    'teacher_name' => $class->teacher->full_name ?? 'N/A'
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
                ->where('status', 'present')
                ->count();
                
            $percentage = $totalAttendances > 0 ? 
                round(($presentAttendances / $totalAttendances) * 100, 1) : 0;
                
            $last7Days[] = [
                'date' => $date->format('d/m'),
                'percentage' => $percentage,
                'total' => $totalAttendances,
                'present' => $presentAttendances
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
            ->with(['classes' => function($q) {
                $q->active()->currentYear()->withCount('students');
            }])
            ->get()
            ->map(function($teacher) {
                $totalStudents = $teacher->classes->sum('students_count');
                
                return [
                    'name' => $teacher->full_name,
                    'classes_count' => $teacher->classes_count,
                    'total_students' => $totalStudents,
                    'specialization' => $teacher->specialization,
                    'email' => $teacher->email
                ];
            });
    }

    private function getTodaysSchedule($teacherId)
    {
        try {
            // Verificar se a tabela class_schedules existe
            if (!Schema::hasTable('class_schedules')) {
                return $this->getFallbackSchedule($teacherId);
            }

            $schedules = \App\Models\ClassSchedule::with(['class', 'subject'])
                ->where('teacher_id', $teacherId)
                ->where('weekday', now()->dayOfWeek)
                ->where('status', 'active')
                ->where('academic_year', now()->year)
                ->orderBy('start_time')
                ->get()
                ->map(function($schedule) {
                    $isCurrent = $schedule->isHappeningNow();
                    
                    return [
                        'id' => $schedule->id,
                        'class_name' => $schedule->class->name ?? 'Turma não encontrada',
                        'grade_level' => $schedule->class->grade_level ?? 'N/A',
                        'subject' => $schedule->subject->name ?? 'Disciplina não encontrada',
                        'time' => $schedule->start_time->format('H:i') . ' - ' . $schedule->end_time->format('H:i'),
                        'time_range' => $schedule->time_range,
                        'classroom' => $schedule->classroom ?? $schedule->class->classroom ?? 'Sala não definida',
                        'is_current' => $isCurrent,
                        'status' => $isCurrent ? 'current' : ($schedule->start_time->format('H:i') > now()->format('H:i') ? 'upcoming' : 'completed'),
                        'duration' => $schedule->duration . ' min',
                        'weekday_name' => $schedule->weekday_name
                    ];
                });

            return $schedules;

        } catch (\Exception $e) {
            // Fallback: buscar turmas do professor e criar horários fictícios
            Log::error('Erro ao buscar horário do professor: ' . $e->getMessage());
            return $this->getFallbackSchedule($teacherId);
        }
    }

    private function getFallbackSchedule($teacherId)
    {
        $teacherClasses = ClassRoom::where('teacher_id', $teacherId)
            ->active()
            ->where('school_year', now()->year)
            ->with(['subjects'])
            ->get();

        if ($teacherClasses->isEmpty()) {
            return collect();
        }

        $schedules = [];
        $startTimes = ['08:00', '09:30', '11:00', '14:00', '15:30'];
        $weekdayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
        
        foreach ($teacherClasses as $index => $class) {
            if ($class->subjects->isNotEmpty()) {
                $subject = $class->subjects->first();
                $startTime = $startTimes[$index % count($startTimes)];
                $endTime = date('H:i', strtotime($startTime . ' +90 minutes'));
                
                $schedules[] = [
                    'class_name' => $class->name,
                    'grade_level' => $class->grade_level,
                    'grade_level_name' => $class->grade_level_name,
                    'subject' => $subject->name,
                    'time' => $startTime . ' - ' . $endTime,
                    'classroom' => $class->classroom ?? 'Sala ' . ($index + 101),
                    'is_current' => false,
                    'status' => 'upcoming',
                    'weekday_name' => $weekdayNames[now()->dayOfWeek] ?? 'Hoje',
                    'is_fallback' => true // Para identificar que são dados simulados
                ];
            }
        }

        return collect($schedules);
    }

    private function calculateChildrenAttendanceRate($children)
    {
        $totalAttendances = 0;
        $presentAttendances = 0;
        
        foreach ($children as $child) {
            $childAttendances = $child->attendances->where('attendance_date', '>=', now()->subDays(30));
            $totalAttendances += $childAttendances->count();
            $presentAttendances += $childAttendances->where('status', 'present')->count();
        }
        
        if ($totalAttendances == 0) return 0;
        
        return round(($presentAttendances / $totalAttendances) * 100, 1);
    }

    // Método para API de contadores (usado no dashboard)
    public function counters()
    {
        $user = auth()->user();
        $data = [];

        switch ($user->role) {
            case 'admin':
                $data = [
                    'notifications' => $user->unreadNotifications->count(),
                    'overdue_payments' => Payment::overdue()->count(),
                    'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
                    'pending_leave_requests' => StaffLeaveRequest::where('status', 'pending')->count(),
                ];
                break;
                
            case 'secretary':
                $data = [
                    'notifications' => $user->unreadNotifications->count(),
                    'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
                    'overdue_payments' => Payment::overdue()->count(),
                    'todays_payments' => Payment::paid()->whereDate('payment_date', today())->count(),
                ];
                break;
                
            case 'teacher':
                $teacher = Teacher::where('user_id', $user->id)->first();
                if ($teacher) {
                    $data = [
                        'notifications' => $user->unreadNotifications->count(),
                        'pending_grades' => Grade::where('teacher_id', $teacher->id)->whereNull('grade')->count(),
                        'todays_classes' => \App\Models\ClassSchedule::where('teacher_id', $teacher->id)
                            ->where('weekday', now()->dayOfWeek)
                            ->count(),
                    ];
                }
                break;
                
            case 'parent':
                $parent = $user->parent;
                if ($parent) {
                    $data = [
                        'notifications' => $user->unreadNotifications->count(),
                        'pending_payments' => Payment::whereIn('student_id', $parent->students->pluck('id'))
                            ->pending()
                            ->count(),
                        'children_count' => $parent->students->count(),
                    ];
                }
                break;
        }

        return response()->json($data);
    }
}