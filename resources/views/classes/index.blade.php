@extends('layouts.app')

@section('title', 'Gestão de Turmas')
@section('page-title', 'Turmas')
@section('page-title-icon', 'fas fa-chalkboard')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Turmas</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filtros -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form action="{{ route('classes.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Nível</label>
                        <select name="grade_level" class="form-select">
                            <option value="">Todos os níveis</option>
                            @foreach($gradeLevels as $key => $level)
                                <option value="{{ $key }}" {{ request('grade_level') == $key ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Professor</label>
                        <select name="teacher_id" class="form-select">
                            <option value="">Todos os professores</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ano Letivo</label>
                        <input type="number" name="school_year" class="form-control" 
                               value="{{ request('school_year', $currentYear) }}" min="2020" max="2030">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="">Todos</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Ativas</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inativas</option>
                        </select>
                    </div>
                    <div class="col-md-2">
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
                    <i class="fas fa-chalkboard"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\ClassRoom::count() }}</div>
                    <div class="stat-label">Total de Turmas</div>
                </div>
            </div>

            <div class="stat-card teachers">
                <div class="stat-icon teachers">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\ClassRoom::active()->count() }}</div>
                    <div class="stat-label">Turmas Ativas</div>
                </div>
            </div>

            <div class="stat-card payments">
                <div class="stat-icon payments">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-content">
                    @php
                        $totalStudents = \App\Models\Enrollment::where('status', 'active')->count();
                    @endphp
                    <div class="stat-value">{{ $totalStudents }}</div>
                    <div class="stat-label">Alunos Matriculados</div>
                </div>
            </div>

            <div class="stat-card events">
                <div class="stat-icon events">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-content">
                    @php
                        $totalCapacity = \App\Models\ClassRoom::sum('max_students');
                        $usedCapacity = \App\Models\ClassRoom::withCount(['enrollments as active_students_count' => function($query) {
                            $query->where('status', 'active');
                        }])->get()->sum('active_students_count');
                        $capacityPercentage = $totalCapacity > 0 ? round(($usedCapacity / $totalCapacity) * 100, 1) : 0;
                    @endphp
                    <div class="stat-value">{{ $capacityPercentage }}%</div>
                    <div class="stat-label">Capacidade Ocupada</div>
                </div>
            </div>
        </div>

        <!-- Tabela de Turmas -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-list"></i>
                    Lista de Turmas
                </h3>
                @can('create_classes')
                <a href="{{ route('classes.create') }}" class="btn btn-secondary-school">
                    <i class="fas fa-plus"></i> Nova Turma
                </a>
                @endcan
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Nível</th>
                            <th>Professor</th>
                            <th>Ano Letivo</th>
                            <th>Alunos</th>
                            <th>Sala</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            @php
                                // Carregar contagem de alunos ativos para esta turma
                                $activeStudentsCount = $class->enrollments()->where('status', 'active')->count();
                                $capacityPercentage = $class->max_students > 0 ? round(($activeStudentsCount / $class->max_students) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $class->name }}</strong>
                                    @if($class->is_full)
                                        <span class="badge bg-danger ms-1" title="Turma Lotada">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $class->grade_level_name }}</span>
                                </td>
                                <td>
                                    @if($class->teacher)
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">
                                                {{ substr($class->teacher->first_name, 0, 1) }}{{ substr($class->teacher->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                {{ $class->teacher->first_name }} {{ $class->teacher->last_name }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Não atribuído</span>
                                    @endif
                                </td>
                                <td>{{ $class->school_year }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-3" style="width: 80px; height: 8px;">
                                            <div class="progress-bar bg-{{ $capacityPercentage > 80 ? 'danger' : ($capacityPercentage > 60 ? 'warning' : 'success') }}" 
                                                 style="width: {{ $capacityPercentage }}%"></div>
                                        </div>
                                        <span class="{{ $capacityPercentage >= 100 ? 'text-danger fw-bold' : '' }}">
                                            {{ $activeStudentsCount }}/{{ $class->max_students }}
                                            @if($class->available_slots > 0)
                                                <small class="text-muted d-block">
                                                    {{ $class->available_slots }} vaga(s)
                                                </small>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($class->classroom)
                                        <span class="badge bg-secondary">{{ $class->classroom }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $class->is_active ? 'success' : 'secondary' }}">
                                        {{ $class->is_active ? 'Ativa' : 'Inativa' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('classes.show', $class->id) }}" 
                                           class="btn btn-sm btn-primary-school" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_classes')
                                        <a href="{{ route('classes.edit', $class->id) }}" 
                                           class="btn btn-sm btn-secondary-school" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete_classes')
                                        <form action="{{ route('classes.destroy', $class->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="Excluir" 
                                                    onclick="return confirm('Tem certeza que deseja excluir esta turma? Esta ação não pode ser desfeita.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhuma turma encontrada.</p>
                                    @if(request()->anyFilled(['grade_level', 'teacher_id', 'school_year', 'is_active']))
                                        <a href="{{ route('classes.index') }}" class="btn btn-secondary btn-sm me-2">
                                            <i class="fas fa-times"></i> Limpar Filtros
                                        </a>
                                    @endif
                                    @can('create_classes')
                                    <a href="{{ route('classes.create') }}" class="btn btn-primary-school">
                                        <i class="fas fa-plus"></i> Criar Primeira Turma
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($classes->hasPages())
                <div class="school-card-body border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Mostrando {{ $classes->firstItem() }} a {{ $classes->lastItem() }} de {{ $classes->total() }} registros
                        </div>
                        {{ $classes->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.user-avatar-sm {
    width: 30px;
    height: 30px;
    background: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 11px;
    font-weight: 600;
}
.progress {
    background-color: #e9ecef;
    border-radius: 0.375rem;
}
.progress-bar {
    border-radius: 0.375rem;
    transition: width 0.6s ease;
}
</style>

<script>
// Atualizar automaticamente os contadores a cada 30 segundos
document.addEventListener('DOMContentLoaded', function() {
    function updateCounters() {
        // Esta função pode ser expandida para atualizar dados em tempo real
        console.log('Atualizando contadores...');
    }
    
    // Atualizar a cada 30 segundos
    setInterval(updateCounters, 30000);
});
</script>
@endsection