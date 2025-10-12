{{-- resources/views/grades/create.blade.php --}}
@extends('layouts.school')

@section('title', 'Cadastrar Nova Nota')
@section('page-title', 'Cadastrar Nova Nota')
@section('title-icon', 'fas fa-plus-circle')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('grades.index') }}">Notas</a></li>
    <li class="breadcrumb-item active">Nova Nota</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-plus-circle"></i>
                Cadastrar Nova Nota
            </div>
            <div class="school-card-body">
                <form method="POST" action="{{ route('grades.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Aluno *</label>
                                <select name="student_id" id="student_id" class="form-select" required>
                                    <option value="">Selecione o aluno</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->full_name }} - {{ $student->student_number }}
                                            @if($student->currentEnrollment)
                                                ({{ $student->currentEnrollment->class->name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subject_id" class="form-label">Disciplina *</label>
                                <select name="subject_id" id="subject_id" class="form-select" required>
                                    <option value="">Selecione a disciplina</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="grade" class="form-label">Nota *</label>
                                <input type="number" name="grade" id="grade" class="form-control" 
                                       min="0" max="20" step="0.1" value="{{ old('grade') }}" 
                                       placeholder="0.0 - 20.0" required>
                                @error('grade')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="assessment_type" class="form-label">Tipo de Avaliação *</label>
                                <select name="assessment_type" id="assessment_type" class="form-select" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="test" {{ old('assessment_type') == 'test' ? 'selected' : '' }}>Teste</option>
                                    <option value="assignment" {{ old('assessment_type') == 'assignment' ? 'selected' : '' }}>Trabalho</option>
                                    <option value="exam" {{ old('assessment_type') == 'exam' ? 'selected' : '' }}>Exame</option>
                                    <option value="project" {{ old('assessment_type') == 'project' ? 'selected' : '' }}>Projeto</option>
                                    <option value="participation" {{ old('assessment_type') == 'participation' ? 'selected' : '' }}>Participação</option>
                                </select>
                                @error('assessment_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="term" class="form-label">Trimestre *</label>
                                <select name="term" id="term" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <option value="1" {{ old('term') == '1' ? 'selected' : '' }}>1º Trimestre</option>
                                    <option value="2" {{ old('term') == '2' ? 'selected' : '' }}>2º Trimestre</option>
                                    <option value="3" {{ old('term') == '3' ? 'selected' : '' }}>3º Trimestre</option>
                                </select>
                                @error('term')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="year" class="form-label">Ano *</label>
                                <input type="number" name="year" id="year" class="form-control" 
                                       value="{{ old('year', $currentYear) }}" min="2020" max="2030" required>
                                @error('year')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_recorded" class="form-label">Data da Avaliação *</label>
                                <input type="date" name="date_recorded" id="date_recorded" 
                                       class="form-control" value="{{ old('date_recorded', date('Y-m-d')) }}" required>
                                @error('date_recorded')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="comments" class="form-label">Observações</label>
                                <textarea name="comments" id="comments" class="form-control" 
                                          rows="2" placeholder="Observações sobre a nota...">{{ old('comments') }}</textarea>
                                @error('comments')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('grades.index') }}" class="btn btn-school btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-school btn-primary-school">
                            <i class="fas fa-save me-2"></i> Salvar Nota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação em tempo real da nota
    const gradeInput = document.getElementById('grade');
    gradeInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value < 0) this.value = 0;
        if (value > 20) this.value = 20;
    });

    // Formatação automática do ano
    const yearInput = document.getElementById('year');
    yearInput.addEventListener('blur', function() {
        if (this.value.length === 2) {
            this.value = '20' + this.value;
        }
    });
});
</script>
@endpush