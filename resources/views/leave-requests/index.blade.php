@extends('layouts.app')

@section('title', 'Gestão de Licenças & Ausências')
@section('page-title', 'Licenças & Ausências')
@section('page-title-icon', 'fas fa-calendar-times')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item active">Licenças</li>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('staff-leave-requests.export.csv', request()->query()) }}" class="btn btn-outline-success">
            <i class="fas fa-file-csv me-1"></i> Exportar CSV
        </a>
        <a href="{{ route('staff-leave-requests.export.pdf', request()->query()) }}" class="btn btn-outline-danger">
            <i class="fas fa-file-pdf me-1"></i> Exportar PDF
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <!-- Stat KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-list fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Total de Pedidos</div>
                            <h4 class="mb-0 text-primary font-weight-bold">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-warning">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning-subtle text-warning rounded-circle p-3 me-3">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Pendentes</div>
                            <h4 class="mb-0 text-warning font-weight-bold">{{ $stats['pending'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Aprovadas</div>
                            <h4 class="mb-0 text-success font-weight-bold">{{ $stats['approved'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-danger">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger-subtle text-danger rounded-circle p-3 me-3">
                            <i class="fas fa-times-circle fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Rejeitadas</div>
                            <h4 class="mb-0 text-danger font-weight-bold">{{ $stats['rejected'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Harmonizados -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form method="GET" action="{{ route('staff-leave-requests.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Professor / Funcionário</label>
                        <input type="text" name="teacher"
                            class="form-control rounded-xl border-slate-200"
                            placeholder="Nome do docente..."
                            value="{{ request('teacher') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Status</label>
                        <select name="status" class="form-select rounded-xl border-slate-200">
                            <option value="">Todos os status</option>
                            <option value="pending" @selected(request('status') === 'pending')>Pendente</option>
                            <option value="approved" @selected(request('status') === 'approved')>Aprovada</option>
                            <option value="rejected" @selected(request('status') === 'rejected')>Rejeitada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Tipo de Licença</label>
                        <select name="leave_type" class="form-select rounded-xl border-slate-200">
                            <option value="">Todos os tipos</option>
                            <option value="sick" @selected(request('leave_type') === 'sick')>Licença Médica</option>
                            <option value="vacation" @selected(request('leave_type') === 'vacation')>Férias</option>
                            <option value="personal" @selected(request('leave_type') === 'personal')>Assunto Pessoal</option>
                            <option value="maternity" @selected(request('leave_type') === 'maternity')>Maternidade</option>
                            <option value="other" @selected(request('leave_type') === 'other')>Outro</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Data Inicial</label>
                        <input type="date" name="date_from" class="form-control rounded-xl border-slate-200" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-school flex-grow rounded-xl">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                        @if(request()->anyFilled(['teacher', 'status', 'leave_type', 'date_from', 'date_to']))
                            <a href="{{ route('staff-leave-requests.index') }}" class="btn btn-outline-secondary rounded-xl" title="Limpar Filtros">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Pedidos de Licença -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-calendar-times text-primary me-2"></i>
                    Lista de Pedidos de Licença
                    <span class="badge bg-primary ms-2" style="font-size: 0.55em;">{{ $leaveRequests->total() }}</span>
                </h3>
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Funcionário / Docente</th>
                            <th>Tipo de Licença</th>
                            <th>Período</th>
                            <th>Dias</th>
                            <th>Status</th>
                            <th>Solicitado em</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaveRequests as $leaveRequest)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 13px;">
                                            {{ strtoupper(substr($leaveRequest->staff?->first_name ?? 'F', 0, 1) . substr($leaveRequest->staff?->last_name ?? 'D', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-slate-800">
                                                {{ $leaveRequest->staff?->first_name }} {{ $leaveRequest->staff?->last_name }}
                                            </div>
                                            <small class="text-muted">{{ $leaveRequest->staff?->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $leaveRequest->leave_type_name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-slate-800">
                                        {{ $leaveRequest->start_date?->format('d/m/Y') }}
                                        <i class="fas fa-arrow-right mx-1 text-muted text-xs"></i>
                                        {{ $leaveRequest->end_date?->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $leaveRequest->days_requested }} dias</strong>
                                </td>
                                <td>
                                    @if ($leaveRequest->status === 'approved')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                            <i class="fas fa-check-circle me-1"></i> Aprovada
                                        </span>
                                    @elseif($leaveRequest->status === 'rejected')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">
                                            <i class="fas fa-times-circle me-1"></i> Rejeitada
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">
                                            <i class="fas fa-clock me-1"></i> Pendente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $leaveRequest->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('staff-leave-requests.show', $leaveRequest) }}"
                                            class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                            <i class="fas fa-eye me-1"></i> Detalhes
                                        </a>
                                        @if ($leaveRequest->status === 'pending')
                                            @can('approve_leave_requests')
                                                <form method="POST" action="{{ route('staff-leave-requests.approve', $leaveRequest) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Aprovar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('staff-leave-requests.reject', $leaveRequest) }}" class="d-inline" onsubmit="return confirm('Deseja rejeitar este pedido de licença?')">
                                                    @csrf
                                                    <input type="hidden" name="rejection_reason" value="Rejeitado via gestão rápida">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Rejeitar">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 text-slate-300"></i>
                                    <h5>Nenhum pedido de licença encontrado</h5>
                                    <p class="text-xs">Não existem licenças registradas com os filtros selecionados.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leaveRequests->hasPages())
                <div class="p-3 border-top">
                    {{ $leaveRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
