<?php

namespace App\Http\Controllers;

use App\Models\StaffLeaveRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LeaveRequestManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);

        $leaveRequests = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => StaffLeaveRequest::count(),
            'pending' => StaffLeaveRequest::pending()->count(),
            'approved' => StaffLeaveRequest::approved()->count(),
            'rejected' => StaffLeaveRequest::rejected()->count(),
        ];

        return view('leave-requests.index', compact('leaveRequests', 'stats'));
    }

    public function exportCsv(Request $request)
    {
        $this->authorize('manage_leave_requests');

        $leaveRequests = $this->buildFilteredQuery($request)->get();
        $filename = 'relatorio-licencas-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($leaveRequests) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Professor',
                'Email',
                'Tipo',
                'Inicio',
                'Fim',
                'Dias',
                'Status',
                'Solicitado em',
                'Analisado em',
                'Analisado por',
                'Motivo da rejeicao',
            ]);

            foreach ($leaveRequests as $leaveRequest) {
                fputcsv($handle, [
                    $leaveRequest->id,
                    $leaveRequest->staff?->full_name ?? '',
                    $leaveRequest->staff?->email ?? '',
                    $leaveRequest->leave_type_name,
                    optional($leaveRequest->start_date)->format('Y-m-d'),
                    optional($leaveRequest->end_date)->format('Y-m-d'),
                    $leaveRequest->days_requested,
                    $leaveRequest->status,
                    $leaveRequest->created_at?->format('Y-m-d H:i:s'),
                    optional($leaveRequest->approved_at)->format('Y-m-d H:i:s'),
                    $leaveRequest->approvedBy?->name ?? '',
                    $leaveRequest->rejection_reason ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('manage_leave_requests');

        $leaveRequests = $this->buildFilteredQuery($request)->get();

        $pdf = Pdf::loadView('leave-requests.export-pdf', [
            'leaveRequests' => $leaveRequests,
            'filters' => $request->only(['teacher', 'status', 'leave_type', 'date_from', 'date_to']),
            'generatedAt' => now(),
            'generatedBy' => auth()->user()?->name,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('relatorio-licencas-' . now()->format('Ymd-His') . '.pdf');
    }

    public function show(StaffLeaveRequest $leaveRequest)
    {
        $this->authorize('manage_leave_requests');

        $leaveRequest->load(['staff.user', 'approvedBy']);
        $activities = Activity::forSubject($leaveRequest)
            ->with('causer')
            ->latest()
            ->limit(20)
            ->get();

        return view('leave-requests.show', compact('leaveRequest', 'activities'));
    }

    public function approve(StaffLeaveRequest $leaveRequest)
    {
        $this->authorize('approve_leave_requests');

        if ($leaveRequest->status !== 'pending') {
            return back()->with('warning', 'Este pedido já foi analisado.');
        }

        $leaveRequest->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        activity('leave_requests')
            ->performedOn($leaveRequest)
            ->causedBy(auth()->user())
            ->withProperties([
                'decision' => 'approved',
                'staff_id' => $leaveRequest->staff_id,
                'leave_type' => $leaveRequest->leave_type,
                'period' => [
                    'start_date' => optional($leaveRequest->start_date)->toDateString(),
                    'end_date' => optional($leaveRequest->end_date)->toDateString(),
                ],
            ])
            ->log('leave_request_approved');

        return back()->with('success', 'Pedido de licença aprovado.');
    }

    public function reject(Request $request, StaffLeaveRequest $leaveRequest)
    {
        $this->authorize('approve_leave_requests');

        if ($leaveRequest->status !== 'pending') {
            return back()->with('warning', 'Este pedido já foi analisado.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        activity('leave_requests')
            ->performedOn($leaveRequest)
            ->causedBy(auth()->user())
            ->withProperties([
                'decision' => 'rejected',
                'reason' => $validated['rejection_reason'],
                'staff_id' => $leaveRequest->staff_id,
                'leave_type' => $leaveRequest->leave_type,
                'period' => [
                    'start_date' => optional($leaveRequest->start_date)->toDateString(),
                    'end_date' => optional($leaveRequest->end_date)->toDateString(),
                ],
            ])
            ->log('leave_request_rejected');

        return back()->with('success', 'Pedido de licença rejeitado.');
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = StaffLeaveRequest::with(['staff.user', 'approvedBy'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('teacher')) {
            $search = $request->teacher;
            $query->whereHas('staff', function ($teacherQuery) use ($search) {
                $teacherQuery->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }
}
