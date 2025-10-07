@extends('layouts.app')

@section('title', $class->name)
@section('page-title', $class->name)
@section('page-title-icon', 'fas fa-chalkboard')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Turmas</a></li>
    <li class="breadcrumb-item active">{{ $class->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Card de Informações da Turma -->
        <div class="school-card mb-4">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-info-circle"></i>
                    Informações da Turma
                </h3>
            </div>
            <div class="school-card-body">
                <div class="mb-3">
                    <strong>Nome:</strong> {{ $class->name }}
                </div>
                <div class="mb-3">
                    <strong>Nível:</strong> 
                    <span class="badge bg-primary">{{ $class->grade_level_name }}</span>
                </div>
                <div class="mb-3">
                    <strong>Ano Letivo:</strong> {{ $class->school_year }}
                </div>
                <div class="mb-3">
                    <strong>Sala:</strong> {{ $class->classroom ?? 'N/A' }}
                </div>
                <div class="mb-3">
                    <strong>Professor:</strong>
                    @if($class->teacher)
                        <div class="d-flex align-items-center mt-1">
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
                </div>
                <div class="mb-3">
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $class->is_active ? 'success' : 'secondary' }}">
                        {{ $class->is_active ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>
                
                @can('edit_classes')
                <div class="mt-4">
                    <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-secondary-school btn-sm">
                        <i class="fas fa-edit"></i> Editar Turma
                    </a>
                </div>
                @endcan
            </div>
        </div>

        <!-- Card de Estatísticas -->
        <div class="school-card">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-chart-bar"></i>
                    Estatísticas
                </h3>
            </div>
            <div class="school-card-body">
                <div class="mb-3">
                    <strong>Capacidade:</strong>
                    <div class="d-flex align-items-center mt-1">
                        <div class="progress flex-grow-1 me-2" style="height: 10px;">
                            <div class="progress-bar bg-{{ $stats['capacity_percentage'] > 80 ? 'danger' : ($stats['capacity_percentage'] > 60 ? 'warning' : 'success') }}" 
                                 style="width: {{ $stats['capacity_percentage'] }}%"></div>
                        </div>
                        <span class="text-muted small">{{ $stats['capacity_percentage'] }}%</span>
                    </div>
                    <small class="text-muted">{{ $stats['total_students'] }}/{{ $class->max_students }} alunos</small>
                </div>
                
                <div class="mb-3">
                    <strong>Gênero:</strong>
                    <div class="mt-1">
                        <span class="badge bg-primary me-2">♂ {{ $stats['male_students'] }}</span>
                        <span class="badge bg-pink">♀ {{ $stats['female_students'] }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Idade Média:</strong> {{ $stats['average_age'] }} anos
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Card de Ações Rápidas -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('classes.students', $class->id) }}" class="btn btn-primary-school btn-lg w-100">
                            <i class="fas fa-users fa-2x mb-2"></i><br>
                            Alunos
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('attendances.mark-by-class', $class->id) }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-clipboard-check fa-2x mb-2"></i><br>
                            Presenças
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('grades.class-report', $class->id) }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                            Notas
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-calendar-alt fa-2x mb-2"></i><br>
                            Calendário
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Alunos -->
        <div class="school-card mb-4">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-users"></i>
                    Alunos da Turma ({{ $stats['total_students'] }})
                </h3>
                <a href="{{ route('classes.students', $class->id) }}" class="btn btn-secondary-school btn-sm">
                    <i class="fas fa-eye"></i> Ver Todos
                </a>
            </div>
            <div class="school-card-body">
                @if($class->students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-school">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Número</th>
                                    <th>Gênero</th>
                                    <th>Idade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($class->students->take(5) as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar-sm me-2">
                                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $student->student_number }}</td>
                                        <td>
                                            <span class="badge bg-{{ $student->gender == 'male' ? 'primary' : 'pink' }}">
                                                {{ $student->gender == 'male' ? '♂' : '♀' }}
                                            </span>
                                        </td>
                                        <td>{{ $student->birthdate ? $student->birthdate->diffInYears(now()) : 'N/A' }} anos</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($class->students->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('classes.students', $class->id) }}" class="btn btn-outline-primary btn-sm">
                                Ver mais {{ $class->students->count() - 5 }} alunos
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum aluno matriculado nesta turma.</p>
                        <a href="{{ route('classes.students', $class->id) }}" class="btn btn-primary-school">
                            <i class="fas fa-user-plus"></i> Adicionar Alunos
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Card de Próximos Aniversários -->
        @if($upcomingBirthdays->count() > 0)
        <div class="school-card">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-birthday-cake"></i>
                    Próximos Aniversários
                </h3>
            </div>
            <div class="school-card-body">
                @foreach($upcomingBirthdays as $student)
                    <div class="d-flex align-items-center mb-3">
                        <div class="user-avatar-sm me-3 bg-warning">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                            <div class="text-muted small">
                                {{ $student->birthdate->format('d/m') }} 
                                ({{ $student->birthdate->diffInYears(now()) + 1 }} anos)
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
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
.bg-pink {
    background-color: #e83e8c !important;
}
</style>
@endsection