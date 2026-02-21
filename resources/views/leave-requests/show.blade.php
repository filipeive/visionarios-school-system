@extends('layouts.app')

@section('title', 'Detalhe da Licença')
@section('page-title', 'Detalhe da Licença')
@section('title-icon', 'fas fa-calendar-check')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('staff-leave-requests.index') }}">Licenças</a></li>
    <li class="breadcrumb-item active">Detalhe</li>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="school-card">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-alt me-2"></i>Pedido #{{ $leaveRequest->id }}</span>
                    @if ($leaveRequest->status === 'approved')
                        <span class="badge bg-success">Aprovada</span>
                    @elseif($leaveRequest->status === 'rejected')
                        <span class="badge bg-danger">Rejeitada</span>
                    @else
                        <span class="badge bg-warning text-dark">Pendente</span>
                    @endif
                </div>
                <div class="school-card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Professor</strong>
                            <div>{{ $leaveRequest->staff?->full_name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $leaveRequest->staff?->email }}</small>
                        </div>
                        <div class="col-md-6">
                            <strong>Tipo de licença</strong>
                            <div>{{ $leaveRequest->leave_type_name }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Início</strong>
                            <div>{{ $leaveRequest->start_date?->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Fim</strong>
                            <div>{{ $leaveRequest->end_date?->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Dias solicitados</strong>
                        <div>{{ $leaveRequest->days_requested }} dia(s)</div>
                    </div>

                    <div class="mb-3">
                        <strong>Motivo</strong>
                        <div class="border rounded p-3 mt-1">
                            {{ $leaveRequest->reason ?: 'Sem descrição.' }}
                        </div>
                    </div>

                    @if ($leaveRequest->status === 'rejected' && $leaveRequest->rejection_reason)
                        <div class="mb-3">
                            <strong>Motivo da rejeição</strong>
                            <div class="alert alert-danger mt-1 mb-0">
                                {{ $leaveRequest->rejection_reason }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-history me-2"></i>Trilha de Decisão
                </div>
                <div class="school-card-body">
                    <div class="mb-3">
                        <small class="text-muted">Solicitado em</small>
                        <div>{{ $leaveRequest->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    @if ($leaveRequest->approved_at)
                        <div class="mb-3">
                            <small class="text-muted">Analisado em</small>
                            <div>{{ $leaveRequest->approved_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div>
                            <small class="text-muted">Analisado por</small>
                            <div>{{ $leaveRequest->approvedBy?->name ?? 'Não identificado' }}</div>
                        </div>
                    @else
                        <div class="text-muted">Ainda não analisado.</div>
                    @endif
                </div>
            </div>

            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-clipboard-check me-2"></i>Auditoria
                </div>
                <div class="school-card-body">
                    @forelse ($activities as $activity)
                        <div class="border rounded p-2 mb-2">
                            <div class="small text-muted">
                                {{ $activity->created_at->format('d/m/Y H:i') }}
                                • {{ $activity->causer?->name ?? 'Sistema' }}
                            </div>
                            <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $activity->description)) }}</div>
                        </div>
                    @empty
                        <div class="text-muted">Sem eventos de auditoria.</div>
                    @endforelse
                </div>
            </div>

            @if ($leaveRequest->status === 'pending')
                @can('approve_leave_requests')
                    <div class="school-card">
                        <div class="school-card-header">
                            <i class="fas fa-gavel me-2"></i>Ações
                        </div>
                        <div class="school-card-body">
                            <form method="POST" action="{{ route('staff-leave-requests.approve', $leaveRequest) }}"
                                class="mb-3">
                                @csrf
                                <button class="btn btn-success w-100">
                                    <i class="fas fa-check me-1"></i>Aprovar Pedido
                                </button>
                            </form>

                            <form method="POST" action="{{ route('staff-leave-requests.reject', $leaveRequest) }}">
                                @csrf
                                <label class="form-label">Motivo da rejeição</label>
                                <textarea name="rejection_reason" rows="3" class="form-control mb-2" required
                                    placeholder="Descreva o motivo da rejeição..."></textarea>
                                <button class="btn btn-danger w-100">
                                    <i class="fas fa-times me-1"></i>Rejeitar Pedido
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif
        </div>
    </div>
@endsection
