@extends('layouts.app')

@section('title', 'Gestão de Licenças')
@section('page-title', 'Gestão de Licenças')
@section('title-icon', 'fas fa-calendar-times')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Licenças</li>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon"><i class="fas fa-list"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-warning text-white">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ $stats['pending'] }}</div>
                    <div class="stat-label">Pendentes</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ $stats['approved'] }}</div>
                    <div class="stat-label">Aprovadas</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-danger text-white">
                <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ $stats['rejected'] }}</div>
                    <div class="stat-label">Rejeitadas</div>
                </div>
            </div>
        </div>
    </div>

    <div class="school-card mb-4">
        <div class="school-card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-filter me-2"></i>Filtros</span>
            <div class="d-flex gap-2">
                <a href="{{ route('staff-leave-requests.export.csv', request()->query()) }}"
                    class="btn btn-sm btn-outline-success">
                    <i class="fas fa-file-csv me-1"></i>CSV
                </a>
                <a href="{{ route('staff-leave-requests.export.pdf', request()->query()) }}"
                    class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>PDF
                </a>
            </div>
        </div>
        <div class="school-card-body">
            <form method="GET" action="{{ route('staff-leave-requests.index') }}" class="row g-3">
                <div class="col-md-2">
                    <input type="text" name="teacher" class="form-control" placeholder="Professor"
                        value="{{ request('teacher') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Todos os status</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pendente</option>
                        <option value="approved" @selected(request('status') === 'approved')>Aprovada</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejeitada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="leave_type" class="form-select">
                        <option value="">Todos os tipos</option>
                        <option value="sick" @selected(request('leave_type') === 'sick')>Licença Médica</option>
                        <option value="vacation" @selected(request('leave_type') === 'vacation')>Férias</option>
                        <option value="personal" @selected(request('leave_type') === 'personal')>Assunto Pessoal</option>
                        <option value="maternity" @selected(request('leave_type') === 'maternity')>Maternidade</option>
                        <option value="other" @selected(request('leave_type') === 'other')>Outro</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"
                        title="Data inicial">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"
                        title="Data final">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-school btn-primary-school w-100">
                        <i class="fas fa-search me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('staff-leave-requests.index') }}" class="btn btn-outline-secondary">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="school-card">
        <div class="school-card-header">
            <i class="fas fa-clipboard-list me-2"></i>Pedidos de Licença
        </div>
        <div class="school-card-body">
            @if ($leaveRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-school table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Professor</th>
                                <th>Tipo</th>
                                <th>Período</th>
                                <th>Dias</th>
                                <th>Status</th>
                                <th>Solicitado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaveRequests as $leaveRequest)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $leaveRequest->staff?->first_name }} {{ $leaveRequest->staff?->last_name }}
                                        </strong>
                                        <small class="d-block text-muted">{{ $leaveRequest->staff?->email }}</small>
                                    </td>
                                    <td>{{ $leaveRequest->leave_type_name }}</td>
                                    <td>
                                        {{ $leaveRequest->start_date?->format('d/m/Y') }}
                                        <i class="fas fa-arrow-right mx-1 text-muted small"></i>
                                        {{ $leaveRequest->end_date?->format('d/m/Y') }}
                                    </td>
                                    <td>{{ $leaveRequest->days_requested }}</td>
                                    <td>
                                        @if ($leaveRequest->status === 'approved')
                                            <span class="badge bg-success">Aprovada</span>
                                        @elseif($leaveRequest->status === 'rejected')
                                            <span class="badge bg-danger">Rejeitada</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pendente</span>
                                        @endif
                                    </td>
                                    <td>{{ $leaveRequest->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <a href="{{ route('staff-leave-requests.show', $leaveRequest) }}"
                                                class="btn btn-sm btn-outline-primary w-100">
                                                <i class="fas fa-eye me-1"></i>Detalhes
                                            </a>
                                            @if ($leaveRequest->reason)
                                                <small class="text-muted" title="{{ $leaveRequest->reason }}">
                                                    {{ \Illuminate\Support\Str::limit($leaveRequest->reason, 45) }}
                                                </small>
                                            @endif
                                            @if ($leaveRequest->status === 'pending')
                                                @can('approve_leave_requests')
                                                    <form method="POST"
                                                        action="{{ route('staff-leave-requests.approve', $leaveRequest) }}">
                                                        @csrf
                                                        <button class="btn btn-sm btn-success w-100">
                                                            <i class="fas fa-check me-1"></i>Aprovar
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('staff-leave-requests.reject', $leaveRequest) }}">
                                                        @csrf
                                                        <input type="text" name="rejection_reason" class="form-control form-control-sm mb-1"
                                                            placeholder="Motivo da rejeição" required>
                                                        <button class="btn btn-sm btn-danger w-100">
                                                            <i class="fas fa-times me-1"></i>Rejeitar
                                                        </button>
                                                    </form>
                                                @endcan
                                            @elseif($leaveRequest->status === 'rejected' && $leaveRequest->rejection_reason)
                                                <small class="text-danger">{{ $leaveRequest->rejection_reason }}</small>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $leaveRequests->links() }}</div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                    <p class="mb-0">Nenhum pedido de licença encontrado com os filtros atuais.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
