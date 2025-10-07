@extends('layouts.app')

@section('title', 'Turmas - ' . $subject->name)
@section('page-title', 'Turmas da Disciplina')
@section('page-title-icon', 'fas fa-chalkboard')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Disciplinas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('subjects.show', $subject->id) }}">{{ $subject->name }}</a></li>
    <li class="breadcrumb-item active">Turmas</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Informações da Disciplina -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>{{ $subject->name }} ({{ $subject->code }})</h4>
                        <p class="text-muted mb-0">
                            <strong>Nível:</strong> {{ $gradeLevels[$subject->grade_level] ?? $subject->grade_level }} |
                            <strong>Horas:</strong> {{ $subject->weekly_hours }}h/semana |
                            <strong>Turmas Associadas:</strong> {{ $subject->classSubjects->count() }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('subjects.show', $subject->id) }}" class="btn btn-secondary-school">
                            <i class="fas fa-arrow-left"></i> Voltar à Disciplina
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Associar a Nova Turma -->
        @can('manage_subjects')
        <div class="school-card mb-4">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-link"></i>
                    Associar a Nova Turma
                </h3>
            </div>
            <div class="school-card-body">
                <form action="{{ route('subjects.assign-to-class', $subject->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="class_id" class="form-label">Turma *</label>
                                <select class="form-select @error('class_id') is-invalid @enderror" 
                                        id="class_id" name="class_id" required>
                                    <option value="">Selecione a turma...</option>
                                    @foreach($availableClasses as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} ({{ $class->grade_level_name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="teacher_id" class="form-label">Professor *</label>
                                <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                        id="teacher_id" name="teacher_id" required>
                                    <option value="">Selecione o professor...</option>
                                    @foreach($availableTeachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->first_name }} {{ $teacher->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary-school w-100">
                                    <i class="fas fa-plus"></i> Associar Turma
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endcan

        <!-- Turmas Associadas -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-list"></i>
                    Turmas Associadas ({{ $subject->classSubjects->count() }})
                </h3>
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
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subject->classSubjects as $classSubject)
                            <tr>
                                <td>
                                    <strong>{{ $classSubject->class->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $classSubject->class->classroom ?? 'Sala não definida' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $classSubject->class->grade_level_name }}</span>
                                </td>
                                <td>
                                    @if($classSubject->teacher)
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">
                                                {{ substr($classSubject->teacher->first_name, 0, 1) }}{{ substr($classSubject->teacher->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                {{ $classSubject->teacher->first_name }} {{ $classSubject->teacher->last_name }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Não atribuído</span>
                                    @endif
                                </td>
                                <td>{{ $classSubject->class->school_year }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $classSubject->class->students_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $classSubject->class->is_active ? 'success' : 'secondary' }}">
                                        {{ $classSubject->class->is_active ? 'Ativa' : 'Inativa' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('classes.show', $classSubject->class->id) }}" 
                                           class="btn btn-sm btn-primary-school" title="Ver Turma">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('manage_subjects')
                                        <form action="{{ route('subjects.remove-from-class', [$subject->id, $classSubject->class->id]) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="Remover Associação" 
                                                    onclick="return confirm('Tem certeza que deseja remover esta disciplina da turma?')">
                                                <i class="fas fa-unlink"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Esta disciplina não está associada a nenhuma turma.</p>
                                    @can('manage_subjects')
                                    <p class="text-muted small">Use o formulário acima para associar a disciplina a uma turma.</p>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Estatísticas por Turma -->
        @if($subject->classSubjects->count() > 0)
        <div class="school-card mt-4">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-chart-bar"></i>
                    Desempenho por Turma
                </h3>
            </div>
            <div class="school-card-body">
                <div class="row">
                    @foreach($subject->classSubjects as $classSubject)
                        @php
                            $classGrades = $subject->grades->where('class_id', $classSubject->class_id);
                            $average = $classGrades->avg('grade') ?? 0;
                            $count = $classGrades->count();
                        @endphp
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $classSubject->class->name }}</h6>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-{{ $average >= 10 ? 'success' : 'danger' }}">
                                            Média: {{ number_format($average, 1) }}
                                        </span>
                                        <small class="text-muted">{{ $count }} notas</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $average >= 10 ? 'success' : 'danger' }}" 
                                             style="width: {{ min($average * 5, 100) }}%"></div>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            Professor: {{ $classSubject->teacher_name }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
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
</style>
@endsection