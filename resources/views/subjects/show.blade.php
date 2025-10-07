@extends('layouts.app')

@section('title', $subject->name)
@section('page-title', $subject->name)
@section('page-title-icon', 'fas fa-book')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Disciplinas</a></li>
    <li class="breadcrumb-item active">{{ $subject->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Informações da Disciplina -->
        <div class="school-card mb-4">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-info-circle"></i>
                    Informações da Disciplina
                </h3>
            </div>
            <div class="school-card-body">
                <div class="mb-3">
                    <strong>Nome:</strong> {{ $subject->name }}
                </div>
                <div class="mb-3">
                    <strong>Código:</strong> 
                    <code>{{ $subject->code }}</code>
                </div>
                <div class="mb-3">
                    <strong>Nível:</strong> 
                    <span class="badge bg-primary">{{ $gradeLevels[$subject->grade_level] ?? $subject->grade_level }}</span>
                </div>
                <div class="mb-3">
                    <strong>Horas Semanais:</strong> {{ $subject->weekly_hours }}h
                </div>
                <div class="mb-3">
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $subject->is_active ? 'success' : 'secondary' }}">
                        {{ $subject->is_active ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>
                @if($subject->description)
                <div class="mb-3">
                    <strong>Descrição:</strong>
                    <p class="mb-0 text-muted">{{ $subject->description }}</p>
                </div>
                @endif
                
                @can('edit_subjects')
                <div class="mt-4">
                    <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-secondary-school btn-sm">
                        <i class="fas fa-edit"></i> Editar Disciplina
                    </a>
                </div>
                @endcan
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="school-card">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-chart-bar"></i>
                    Estatísticas
                </h3>
            </div>
            <div class="school-card-body">
                <div class="mb-3">
                    <strong>Turmas:</strong>
                    <span class="badge bg-info float-end">{{ $stats['total_classes'] }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Notas Registradas:</strong>
                    <span class="badge bg-warning float-end">{{ $stats['total_grades'] }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Média Geral:</strong>
                    <span class="badge bg-{{ $stats['average_grade'] >= 10 ? 'success' : 'danger' }} float-end">
                        {{ number_format($stats['average_grade'], 1) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Professores:</strong>
                    <span class="badge bg-primary float-end">{{ $stats['active_teachers'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Ações Rápidas -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('subjects.classes', $subject->id) }}" class="btn btn-primary-school btn-lg w-100">
                            <i class="fas fa-chalkboard fa-2x mb-2"></i><br>
                            Turmas
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('subjects.grades', $subject->id) }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-medal fa-2x mb-2"></i><br>
                            Notas
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                            Relatórios
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-print fa-2x mb-2"></i><br>
                            Exportar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Turmas que Usam esta Disciplina -->
        <div class="school-card mb-4">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-chalkboard"></i>
                    Turmas ({{ $classesUsingSubject->count() }})
                </h3>
                <a href="{{ route('subjects.classes', $subject->id) }}" class="btn btn-secondary-school btn-sm">
                    <i class="fas fa-eye"></i> Ver Todas
                </a>
            </div>
            <div class="school-card-body">
                @if($classesUsingSubject->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-school">
                            <thead>
                                <tr>
                                    <th>Turma</th>
                                    <th>Nível</th>
                                    <th>Professor</th>
                                    <th>Ano Letivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classesUsingSubject->take(5) as $class)
                                    @php
                                        $classSubject = $subject->classSubjects->where('class_id', $class->id)->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $class->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $class->grade_level_name }}</span>
                                        </td>
                                        <td>
                                            @if($classSubject && $classSubject->teacher)
                                                {{ $classSubject->teacher->first_name }} {{ $classSubject->teacher->last_name }}
                                            @else
                                                <span class="text-muted">Não atribuído</span>
                                            @endif
                                        </td>
                                        <td>{{ $class->school_year }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($classesUsingSubject->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('subjects.classes', $subject->id) }}" class="btn btn-outline-primary btn-sm">
                                Ver mais {{ $classesUsingSubject->count() - 5 }} turmas
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Esta disciplina não está associada a nenhuma turma.</p>
                        <a href="{{ route('subjects.classes', $subject->id) }}" class="btn btn-primary-school">
                            <i class="fas fa-link"></i> Associar a Turmas
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Professores que Lecionam esta Disciplina -->
        @if($teachersTeachingSubject->count() > 0)
        <div class="school-card mb-4">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-users"></i>
                    Professores ({{ $teachersTeachingSubject->count() }})
                </h3>
            </div>
            <div class="school-card-body">
                <div class="row">
                    @foreach($teachersTeachingSubject->take(6) as $teacher)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="user-avatar-lg mx-auto mb-2">
                                        {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                    </div>
                                    <h6 class="card-title mb-1">{{ $teacher->first_name }} {{ $teacher->last_name }}</h6>
                                    <p class="text-muted small mb-2">{{ $teacher->email }}</p>
                                    @if($teacher->phone)
                                        <p class="small text-muted">{{ $teacher->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Notas Recentes -->
        <div class="school-card">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-medal"></i>
                    Notas Recentes
                </h3>
                <a href="{{ route('subjects.grades', $subject->id) }}" class="btn btn-secondary-school btn-sm">
                    <i class="fas fa-list"></i> Ver Todas
                </a>
            </div>
            <div class="school-card-body">
                @if($subject->grades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Nota</th>
                                    <th>Tipo</th>
                                    <th>Data</th>
                                    <th>Professor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subject->grades->take(5) as $grade)
                                    <tr>
                                        <td>
                                            {{ $grade->student->first_name }} {{ $grade->student->last_name }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $grade->grade >= 10 ? 'success' : 'danger' }}">
                                                {{ number_format($grade->grade, 1) }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($grade->assessment_type)
                                                @case('continuous') Contínua @break
                                                @case('test') Teste @break
                                                @case('exam') Exame @break
                                                @default {{ $grade->assessment_type }}
                                            @endswitch
                                        </td>
                                        <td>{{ $grade->date_recorded->format('d/m/Y') }}</td>
                                        <td>
                                            @if($grade->teacher)
                                                {{ $grade->teacher->first_name }} {{ $grade->teacher->last_name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-medal fa-2x mb-2"></i>
                        <p>Nenhuma nota registrada para esta disciplina.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.user-avatar-lg {
    width: 60px;
    height: 60px;
    background: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    font-weight: 600;
}
</style>
@endsection