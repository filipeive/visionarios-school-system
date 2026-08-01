<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GateKeeperController extends Controller
{
    /**
     * Portaria Digital main verification screen.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['admin', 'super_admin', 'secretary', 'security']) && !auth()->user()->can('view_students')) {
            abort(403, 'Acesso não autorizado à Portaria Digital.');
        }

        $searchedStudent = null;
        $accessLogs = collect();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $searchedStudent = Student::with(['currentEnrollment.class', 'parent'])
                ->where('student_number', $search)
                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                ->first();
        }

        // Histórico recente de presenças/passagens registadas hoje
        $todayLogs = Attendance::with(['student', 'class'])
            ->whereDate('attendance_date', now()->today())
            ->latest()
            ->take(15)
            ->get();

        return view('gatekeeper.index', compact('searchedStudent', 'todayLogs'));
    }

    /**
     * Log entrance/exit for student at gate.
     */
    public function logAccess(Request $request, Student $student)
    {
        $action = $request->input('action', 'entry'); // entry or exit

        // Registar presença do dia se for entrada
        if ($student->currentEnrollment) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'class_id' => $student->currentEnrollment->class_id,
                    'attendance_date' => now()->toDateString(),
                ],
                [
                    'status' => 'present',
                    'notes' => 'Registado na Portaria Digital às ' . now()->format('H:i') . ' (' . ($action === 'entry' ? 'Entrada' : 'Saída') . ')',
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('gatekeeper.index', ['search' => $student->student_number])
            ->with('success', 'Passagem (' . ($action === 'entry' ? 'Entrada' : 'Saída') . ') de ' . $student->full_name . ' registada com sucesso!');
    }
}
