{{-- resources/views/grades/batch-create.blade.php --}}

@extends('layouts.school')

@section('title', 'Notas em Lote')
@section('page-title', 'Notas em Lote')
@section('title-icon', 'fas fa-layer-group')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('grades.index') }}">Notas</a></li>
    <li class="breadcrumb-item active">Notas em Lote</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="vision-card">
            <div class="vision-card-header">
                <h3 class="vision-card-title">
                    <i class="fas fa-layer-group"></i>
                    Atribuir Notas em Lote
                </h3>
            </div>

            <div class="vision-card-body">
                <!-- Filtros para Seleção -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <select name="class_id" class="form-select" required>
                            <option value="">Selecione a Turma</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }} ({{ $class->grade_level }}º Ano)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="subject_id" class="form-select" required>
                            <option value="">Selecione a Disciplina</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $subjectId == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="term" class="form-select" required>
                            <option value="1" {{ $term == 1 ? 'selected' : '' }}>1º Trimestre</option>
                            <option value="2" {{ $term == 2 ? 'selected' : '' }}>2º Trimestre</option>
                            <option value="3" {{ $term == 3 ? 'selected' : '' }}>3º Trimestre</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="year" class="form-control" 
                               value="{{ $year }}" min="2020" max="2030" required>
                    </div>
                    <div class="col-md-2">
                        <select name="assessment_type" class="form-select" required>
                            <option value="test" {{ $assessmentType == 'test' ? 'selected' : '' }}>Teste</option>
                            <option value="assignment" {{ $assessmentType == 'assignment' ? 'selected' : '' }}>Trabalho</option>
                            <option value="exam" {{ $assessmentType == 'exam' ? 'selected' : '' }}>Exame</option>
                            <option value="project" {{ $assessmentType == 'project' ? 'selected' : '' }}>Projeto</option>
                            <option value="participation" {{ $assessmentType == 'participation' ? 'selected' : '' }}>Participação</option>
                        </select>
                    </div>
                </form>

                @if($classId && $subjectId)
                <form method="POST" action="{{ route('grades.batch-store') }}">
                    @csrf
                    
                    <input type="hidden" name="class_id" value="{{ $classId }}">
                    <input type="hidden" name="subject_id" value="{{ $subjectId }}">
                    <input type="hidden" name="term" value="{{ $term }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="assessment_type" value="{{ $assessmentType }}">

                    <div class="table-responsive">
                        <table class="table table-vision">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>Aluno</th>
                                    <th width="120">Nota Anterior</th>
                                    <th width="150">Nova Nota</th>
                                    <th>Observações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    @php
                                        $existingGrade = $existingGrades->get($student->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $student->student_number }}</small>
                                        </td>
                                        <td>
                                            @if($existingGrade)
                                                <span class="badge bg-{{ $existingGrade->grade >= 10 ? 'success' : 'danger' }}">
                                                    {{ number_format($existingGrade->grade, 1) }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $existingGrade->date_recorded->format('d/m/Y') }}</small>
                                            @else
                                                <span class="text-muted">Nenhuma</span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="hidden" name="grades[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                            <input type="number" 
                                                   name="grades[{{ $student->id }}][grade]" 
                                                   class="form-control form-control-sm" 
                                                   min="0" 
                                                   max="20" 
                                                   step="0.1"
                                                   value="{{ $existingGrade->grade ?? '' }}"
                                                   placeholder="0-20">
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="grades[{{ $student->id }}][comments]" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ $existingGrade->comments ?? '' }}"
                                                   placeholder="Observações...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Voltar
                        </a>
                        <button type="submit" class="btn-vision btn-vision-primary">
                            <i class="fas fa-save me-2"></i> Salvar Todas as Notas
                        </button>
                    </div>
                </form>
                @else
                <div class="text-center text-muted py-5">
                    <i class="fas fa-layer-group fa-3x mb-3"></i>
                    <h5>Selecione uma turma e disciplina</h5>
                    <p>Por favor, selecione uma turma e uma disciplina para começar a atribuir notas.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit do formulário de filtros
document.querySelectorAll('select[name], input[name]').forEach(element => {
    element.addEventListener('change', function() {
        if (this.form && this.form.method === 'GET') {
            this.form.submit();
        }
    });
});
</script>
@endpush