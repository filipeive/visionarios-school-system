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
        <!-- Filtros -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form action="{{ route('enrollments.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativas</option>
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
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ano Letivo</label>
                        <input type="number" name="year" class="form-control" value="{{ request('year', $currentYear) }}" min="2020" max="2030">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-school">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="school-stats mb-4">
            <div class="stat-card students">
                <div class="stat-icon students">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\Enrollment::where('status', 'active')->count() }}</div>
                    <div class="stat-label">Matrículas Ativas</div>
                </div>
            </div>

            <div class="stat-card teachers">
                <div class="stat-icon teachers">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\Enrollment::where('status', 'pending')->count() }}</div>
                    <div class="stat-label">Pendentes</div>
                </div>
            </div>

            <div class="stat-card payments">
                <div class="stat-icon payments">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    @php
                        $totalRevenue = \App\Models\Enrollment::where('status', 'active')->sum('monthly_fee');
                    @endphp
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
                    Lista de Matrículas
                </h3>
                @can('create_enrollments')
                <a href="{{ route('enrollments.create') }}" class="btn btn-secondary-school">
                    <i class="fas fa-plus"></i> Nova Matrícula
                </a>
                @endcan
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
                                        <div class="user-avatar" style="width: 35px; height: 35px; font-size: 12px; margin-right: 10px;">
                                            {{ substr($enrollment->student->first_name, 0, 1) }}{{ substr($enrollment->student->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</strong>
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
                                <td>{{ $enrollment->enrollment_date->format('d/m/Y') }}</td>
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
                                            'pending' => 'warning'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$enrollment->status] ?? 'secondary' }}">
                                        {{ ucfirst($enrollment->status) }}
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
                                    <p class="text-muted">Nenhuma matrícula encontrada.</p>
                                    @can('create_enrollments')
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