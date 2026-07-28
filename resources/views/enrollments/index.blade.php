@extends('layouts.app')

@section('title', 'Gestão de Matrículas')
@section('page-title', 'Matrículas')
@section('page-title-icon', 'fas fa-clipboard-list')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Matrículas</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <!-- Seletor de Ano Lectivo -->
            <div class="school-card mb-4">
                <div class="school-card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                Ano Lectivo:
                            </h5>
                            <div class="btn-group" role="group">
                                @foreach($availableYears as $year)
                                    <a href="{{ route('enrollments.index', ['year' => $year]) }}"
                                        class="btn {{ $selectedYear == $year ? 'btn-primary-school' : 'btn-outline-secondary' }}">
                                        {{ $year }}
                                        @if($year == $currentYear)
                                            <span class="badge bg-light text-dark ms-1" style="font-size: 0.65em;">Atual</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @can('create_enrollments')
                            <a href="{{ route('enrollments.create') }}" class="btn btn-secondary-school">
                                <i class="fas fa-plus me-1"></i> Nova Matrícula
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="school-card mb-4">
                <div class="school-card-body">
                    <form action="{{ route('enrollments.index') }}" method="GET" class="row g-3">
                        <input type="hidden" name="year" value="{{ $selectedYear }}">
                        <div class="col-md-3">
                            <label class="form-label">Pesquisar</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Nome ou nº de estudante..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
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
                            <label class="form-label">Turma</label>
                            <select name="class_id" class="form-select">
                                <option value="">Todas</option>
                                @foreach($classes as $class)
                                    <option value="{{ optional($class)->id }}" {{ request('class_id') == optional($class)->id ? 'selected' : '' }}>
                                        {{ optional($class)->name }} ({{ optional($class)->grade_level }}º Ano)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-school flex-grow-1">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                                <a href="{{ route('enrollments.index', ['year' => $selectedYear]) }}" class="btn btn-outline-secondary" title="Limpar filtros">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cards de Estatísticas -->
            @php
                $yearEnrollments = \App\Models\Enrollment::where('school_year', $selectedYear);
                $activeCount = (clone $yearEnrollments)->where('status', 'active')->count();
                $pendingCount = (clone $yearEnrollments)->where('status', 'pending')->count();
                $totalRevenue = (clone $yearEnrollments)->where('status', 'active')->sum('monthly_fee');
            @endphp
            <div class="school-stats mb-4">
                <div class="stat-card students">
                    <div class="stat-icon students">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $activeCount }}</div>
                        <div class="stat-label">Matrículas Ativas ({{ $selectedYear }})</div>
                    </div>
                </div>

                <div class="stat-card teachers">
                    <div class="stat-icon teachers">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $pendingCount }}</div>
                        <div class="stat-label">Pendentes ({{ $selectedYear }})</div>
                    </div>
                </div>

                <div class="stat-card payments">
                    <div class="stat-icon payments">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        <div class="stat-label">Receita Mensal (MZN)</div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Matrículas -->
            <div class="school-table-container">
                <div class="school-table-header">
                    <h3 class="school-table-title">
                        <i class="fas fa-list"></i>
                        Lista de Matrículas — {{ $selectedYear }}
                    </h3>
                    <span class="badge bg-primary" style="font-size: 0.9em;">
                        {{ $enrollments->total() }} {{ $enrollments->total() == 1 ? 'matrícula' : 'matrículas' }}
                    </span>
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
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <strong>{{ $enrollment->student->student_number ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar"
                                                style="width: 35px; height: 35px; font-size: 12px; margin-right: 10px;">
                                                {{ substr($enrollment->student->first_name, 0, 1) }}{{ substr($enrollment->student->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $enrollment->student->first_name }}
                                                    {{ $enrollment->student->last_name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    @if($enrollment->student->birthdate)
                                                        {{ $enrollment->student->age }} anos
                                                    @else
                                                        Idade não informada
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $enrollment->class->name }}</td>
                                    <td>{{ $enrollment->school_year }}</td>
                                    <td>{{ $enrollment->enrollment_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td>
                                        <strong>{{ number_format($enrollment->monthly_fee, 2, ',', '.') }} MZN</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'active' => 'success',
                                                'inactive' => 'secondary',
                                                'transferred' => 'info',
                                                'cancelled' => 'danger',
                                                'pending' => 'warning',
                                                'pending_renewal' => 'info',
                                                'completed' => 'secondary',
                                                'suspended' => 'dark'
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
                                        <span class="badge bg-{{ $statusColors[$enrollment->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$enrollment->status] ?? ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('enrollments.show', $enrollment->id) }}"
                                                class="btn btn-sm btn-primary-school" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit_enrollments')
                                                <a href="{{ route('enrollments.edit', $enrollment->id) }}"
                                                    class="btn btn-sm btn-secondary-school" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Nenhuma matrícula encontrada para o ano lectivo {{ $selectedYear }}.</p>
                                        @can('create_enrollments')
                                            <a href="{{ route('admin.enrollments.renewals') }}" class="btn btn-outline-info">
                                                <i class="fas fa-sync me-2"></i> Renovações
                                            </a>
                                            <a href="{{ route('enrollments.create') }}" class="btn btn-primary-school">
                                                <i class="fas fa-plus"></i> Criar Primeira Matrícula
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                @if($enrollments->hasPages())
                    <div class="school-card-body border-top">
                        {{ $enrollments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection