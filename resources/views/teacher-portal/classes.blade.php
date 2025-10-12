@extends('layouts.app')

@section('title', 'Minhas Turmas')
@section('page-title', 'Minhas Turmas')
@section('page-title-icon', 'fas fa-chalkboard')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Portal</a></li>
    <li class="breadcrumb-item active">Minhas Turmas</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if($classes->count() > 0)
            <div class="row">
                @foreach($classes as $class)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="school-card h-100">
                            <div class="school-card-header">
                                <i class="fas fa-chalkboard"></i>
                                {{ $class->name }}
                            </div>
                            <div class="school-card-body">
                                <div class="text-center mb-3">
                                    <div class="class-avatar mx-auto mb-3">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                    <h5>{{ $class->name }}</h5>
                                    <p class="text-muted">{{ $class->grade_level_name }}</p>
                                </div>

                                <div class="class-info">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Ano Letivo:</span>
                                        <strong>{{ $class->school_year }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Alunos:</span>
                                        <strong>{{ $class->students_count }}/{{ $class->max_students }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Sala:</span>
                                        <strong>{{ $class->classroom ?? 'N/A' }}</strong>
                                    </div>

                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-{{ $class->capacity_percentage > 80 ? 'danger' : ($class->capacity_percentage > 60 ? 'warning' : 'success') }}" 
                                             style="width: {{ $class->capacity_percentage }}%">
                                            {{ $class->capacity_percentage }}%
                                        </div>
                                    </div>
                                </div>

                                <div class="class-actions">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('teacher.classes.students', $class->id) }}" 
                                           class="btn btn-primary-school">
                                            <i class="fas fa-user-graduate"></i> Ver Alunos
                                        </a>
                                        <a href="{{ route('teacher.gradebook', $class->id) }}" 
                                           class="btn btn-secondary-school">
                                            <i class="fas fa-tasks"></i> Caderno de Notas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="school-card">
                <div class="school-card-body text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Nenhuma Turma Atribuída</h4>
                    <p class="text-muted">Você não tem turmas atribuídas no momento.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.class-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
</style>
@endsection