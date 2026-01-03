<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Main reports dashboard.
     */
    public function index()
    {
        $stats = [
            'total_students' => Student::active()->count(),
            'total_teachers' => Teacher::active()->count(),
            'total_classes' => ClassRoom::active()->count(),
            'monthly_revenue' => Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->where('status', 'paid')
                ->sum('amount'),
        ];

        return view('reports.index', compact('stats'));
    }

    /**
     * Academic reports overview.
     */
    public function academic()
    {
        $classes = ClassRoom::withCount([
            'enrollments as active_students_count' => function ($query) {
                $query->where('status', 'active');
            }
        ])->get();

        return view('reports.academic', compact('classes'));
    }

    /**
     * Student performance report.
     */
    public function performance(Request $request)
    {
        $query = Grade::with(['student', 'subject', 'class']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('term')) {
            $query->where('term', $request->term);
        }

        $grades = $query->latest()->paginate(20);
        $classes = ClassRoom::active()->get();

        return view('reports.performance', compact('grades', 'classes'));
    }

    /**
     * Attendance report.
     */
    public function attendanceReport(Request $request)
    {
        $query = Attendance::with(['student', 'class']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendances = $query->latest()->paginate(20);
        $classes = ClassRoom::active()->get();

        return view('reports.attendance', compact('attendances', 'classes'));
    }

    /**
     * Financial reports overview.
     */
    public function financial()
    {
        $recentPayments = Payment::with('student')->latest()->take(10)->get();

        $monthlyRevenue = Payment::select(
            DB::raw('SUM(amount) as total'),
            DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month")
        )
            ->where('status', 'paid')
            ->groupBy(DB::raw("DATE_FORMAT(payment_date, '%Y-%m')"))
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        return view('reports.financial', compact('recentPayments', 'monthlyRevenue'));
    }

    /**
     * Revenue report.
     */
    public function revenue(Request $request)
    {
        $query = Payment::where('status', 'paid');

        if ($request->filled('date_from')) {
            $query->where('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(20);
        $totalRevenue = $query->sum('amount');

        return view('reports.revenue', compact('payments', 'totalRevenue'));
    }

    /**
     * Defaulters report.
     */
    public function defaulters()
    {
        $defaulters = Enrollment::where('status', 'active')
            ->whereDoesntHave('student.payments', function ($query) {
                $query->whereMonth('payment_date', now()->month)
                    ->whereYear('payment_date', now()->year)
                    ->where('status', 'paid');
            })
            ->with('student')
            ->get();

        return view('reports.defaulters', compact('defaulters'));
    }

    /**
     * Export students.
     */
    public function exportStudents()
    {
        // Placeholder for export logic
        return back()->with('info', 'Exportação de alunos em breve.');
    }

    /**
     * Export payments.
     */
    public function exportPayments()
    {
        // Placeholder for export logic
        return back()->with('info', 'Exportação de pagamentos em breve.');
    }

    /**
     * Export grades.
     */
    public function exportGrades()
    {
        // Placeholder for export logic
        return back()->with('info', 'Exportação de notas em breve.');
    }

    /**
     * API for monthly revenue chart.
     */
    public function monthlyRevenueChart()
    {
        $data = Payment::select(
            DB::raw('SUM(amount) as total'),
            DB::raw("DATE_FORMAT(payment_date, '%M') as month_name"),
            DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month")
        )
            ->where('status', 'paid')
            ->where('payment_date', '>=', now()->subMonths(6))
            ->groupBy(DB::raw("DATE_FORMAT(payment_date, '%Y-%m')"), DB::raw("DATE_FORMAT(payment_date, '%M')"))
            ->orderBy('month', 'asc')
            ->get();

        return response()->json($data);
    }

    /**
     * API for weekly attendance chart.
     */
    public function weeklyAttendanceChart()
    {
        $data = Attendance::select(
            DB::raw('COUNT(*) as total'),
            DB::raw('status'),
            DB::raw("DATE_FORMAT(date, '%W') as day_name"),
            DB::raw("DATE_FORMAT(date, '%Y-%m-%d') as day")
        )
            ->where('date', '>=', now()->startOfWeek())
            ->where('date', '<=', now()->endOfWeek())
            ->groupBy('status', DB::raw("DATE_FORMAT(date, '%Y-%m-%d')"), DB::raw("DATE_FORMAT(date, '%W')"))
            ->orderBy('day', 'asc')
            ->get();

        return response()->json($data);
    }

    /**
     * API for students by grade chart.
     */
    public function studentsByGradeChart()
    {
        $data = ClassRoom::withCount([
            'enrollments as student_count' => function ($query) {
                $query->where('status', 'active');
            }
        ])
            ->get()
            ->map(function ($class) {
                return [
                    'label' => $class->name,
                    'value' => $class->student_count
                ];
            });

        return response()->json($data);
    }
}
