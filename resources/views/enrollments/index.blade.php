@extends('layouts.app')

@section('title', 'Gestão de Matrículas')
@section('page-title', 'Matrículas & Inscrições')
@section('page-title-icon', 'fas fa-clipboard-list')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Matrículas</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <!-- Seletor de Ano Lectivo & Ações Rápidas -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <h6 class="mb-0 text-xs font-bold text-slate-700 uppercase tracking-wider">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            Ano Lectivo:
                        </h6>
                        <div class="btn-group" role="group">
                            @foreach($availableYears as $year)
                                <a href="{{ route('enrollments.index', ['year' => $year]) }}"
                                    class="btn btn-sm rounded-xl {{ $selectedYear == $year ? 'btn-primary-school' : 'btn-outline-secondary' }}">
                                    {{ $year }}
                                    @if($year == $currentYear)
                                        <span class="badge bg-light text-dark ms-1" style="font-size: 0.75em;">Atual</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @can('manage_enrollments')
                            <a href="{{ route('admin.enrollments.renewals') }}" class="btn btn-outline-warning rounded-xl">
                                <i class="fas fa-sync me-1"></i> Renovações
                            </a>
                        @endcan
                        @can('create_enrollments')
                            <a href="{{ route('enrollments.create') }}" class="btn btn-secondary-school rounded-xl">
                                <i class="fas fa-plus me-1"></i> Nova Matrícula
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas Rápidas (Stat KPI Cards) -->
        @php
            $yearEnrollments = \App\Models\Enrollment::where('school_year', $selectedYear);
            $activeCount = (clone $yearEnrollments)->where('status', 'active')->count();
            $pendingCount = (clone $yearEnrollments)->where('status', 'pending')->count();
            $totalYearCount = (clone $yearEnrollments)->count();
            $totalRevenue = (clone $yearEnrollments)->where('status', 'active')->sum('monthly_fee');
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                            <i class="fas fa-clipboard-check fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Matrículas Ativas</div>
                            <h4 class="mb-0 text-success font-weight-bold">{{ $activeCount }}</h4>
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
                            <h4 class="mb-0 text-warning font-weight-bold">{{ $pendingCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-money-bill-wave fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Receita Mensal</div>
                            <h4 class="mb-0 text-primary font-weight-bold">{{ number_format($totalRevenue, 0, ',', '.') }} <small class="text-xs text-muted">MT</small></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-user-graduate fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Total no Ano</div>
                            <h4 class="mb-0 text-info font-weight-bold">{{ $totalYearCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Harmonizados -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form action="{{ route('enrollments.index') }}" method="GET" class="row g-3 align-items-end">
                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                    <div class="col-md-3">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Pesquisar</label>
                        <input type="text" name="search" class="form-control rounded-xl border-slate-200"
                            placeholder="Nome ou nº de estudante..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Status</label>
                        <select name="status" class="form-select rounded-xl border-slate-200">
                            <option value="">Todos os Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativas</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pagamento Pendente</option>
                            <option value="pending_renewal" {{ request('status') == 'pending_renewal' ? 'selected' : '' }}>Pendente Renovação</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Concluídas</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativas</option>
                            <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferidas</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Canceladas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Turma</label>
                        <select name="class_id" class="form-select rounded-xl border-slate-200">
                            <option value="">Todas as Turmas</option>
                            @foreach($classes as $class)
                                <option value="{{ optional($class)->id }}" {{ request('class_id') == optional($class)->id ? 'selected' : '' }}>
                                    {{ optional($class)->name }} ({{ optional($class)->grade_level }}º Ano)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-school flex-grow rounded-xl">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'class_id']))
                            <a href="{{ route('enrollments.index', ['year' => $selectedYear]) }}" class="btn btn-outline-secondary rounded-xl" title="Limpar Filtros">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Matrículas -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-list text-primary me-2"></i>
                    Lista de Matrículas — {{ $selectedYear }}
                    <span class="badge bg-primary ms-2" style="font-size: 0.55em;">{{ $enrollments->total() }}</span>
                </h3>
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Nº Estudante</th>
                            <th>Aluno</th>
                            <th>Turma</th>
                            <th>Ano Letivo</th>
                            <th>Data Matrícula</th>
                            <th>Mensalidade</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $enrollment)
                            <tr>
                                <td>
                                    <code>{{ $enrollment->student->student_number ?? 'N/A' }}</code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-sm me-3" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 13px;">
                                            {{ substr($enrollment->student->first_name ?? 'A', 0, 1) }}{{ substr($enrollment->student->last_name ?? 'B', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-slate-800">
                                                {{ $enrollment->student->first_name ?? '' }} {{ $enrollment->student->last_name ?? '' }}
                                            </div>
                                            <small class="text-muted">
                                                @if($enrollment->student?->birthdate)
                                                    {{ $enrollment->student->age }} anos
                                                @else
                                                    Idade não informada
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        {{ $enrollment->class->name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $enrollment->school_year }}</span>
                                </td>
                                <td>
                                    {{ $enrollment->enrollment_date?->format('d/m/Y') ?? 'N/A' }}
                                </td>
                                <td>
                                    <strong class="text-success">{{ number_format($enrollment->monthly_fee, 2, ',', '.') }} MT</strong>
                                </td>
                                <td>
                                    @php
                                        $statusSubtle = [
                                            'active' => 'bg-success-subtle text-success border border-success-subtle',
                                            'inactive' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                            'transferred' => 'bg-info-subtle text-info border border-info-subtle',
                                            'cancelled' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                            'pending' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                            'pending_renewal' => 'bg-info-subtle text-info border border-info-subtle',
                                            'completed' => 'bg-secondary-subtle text-secondary border',
                                            'suspended' => 'bg-dark-subtle text-dark border'
                                        ];
                                        $statusLabels = [
                                            'active' => 'Ativa',
                                            'inactive' => 'Inativa',
                                            'transferred' => 'Transferida',
                                            'cancelled' => 'Cancelada',
                                            'pending' => 'Pendente',
                                            'pending_renewal' => 'Renovação Pendente',
                                            'completed' => 'Concluída',
                                            'suspended' => 'Suspensa'
                                        ];
                                    @endphp
                                    <span class="badge rounded-pill px-3 py-1 {{ $statusSubtle[$enrollment->status] ?? 'bg-secondary-subtle text-secondary border' }}">
                                        {{ $statusLabels[$enrollment->status] ?? ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('enrollments.show', $enrollment->id) }}"
                                            class="btn btn-outline-primary" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_enrollments')
                                            <a href="{{ route('enrollments.edit', $enrollment->id) }}"
                                                class="btn btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-clipboard-list fa-3x text-slate-300 mb-3"></i>
                                    <h5>Nenhuma matrícula encontrada</h5>
                                    <p class="text-xs mb-3">Não existem matrículas para o ano lectivo {{ $selectedYear }}.</p>
                                    @can('create_enrollments')
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.enrollments.renewals') }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-sync me-1"></i> Renovações
                                            </a>
                                            <a href="{{ route('enrollments.create') }}" class="btn btn-sm btn-primary-school">
                                                <i class="fas fa-plus me-1"></i> Criar Primeira Matrícula
                                            </a>
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($enrollments->hasPages())
                <div class="p-3 border-top">
                    {{ $enrollments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection