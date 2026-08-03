@extends('layouts.app')

@section('title', 'Lançamento de Notas em Lote')
@section('page-title', 'Lançamento em Lote')
@section('title-icon', 'fas fa-layer-group')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('grades.index') }}">Gestão de Notas</a></li>
    <li class="breadcrumb-item active">Notas em Lote</li>
@endsection

@section('content')
<div class="container-fluid p-0">

    <!-- Card de Configuração do Lançamento -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <form action="{{ route('grades.batch-create') }}" method="GET" id="batchFilterForm" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-xs font-bold text-muted uppercase">Turma</label>
                    <select name="class_id" class="form-select form-select-sm" required onchange="this.form.submit()">
                        <option value="">Selecione a Turma...</option>
                        @foreach($classes as $c)
                            <option value="{{ optional($c)->id }}" {{ $classId == optional($c)->id ? 'selected' : '' }}>
                                {{ optional($c)->name }} ({{ optional($c)->grade_level_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-xs font-bold text-muted uppercase">Disciplina</label>
                    <select name="subject_id" class="form-select form-select-sm" required onchange="this.form.submit()">
                        <option value="">Selecione a Disciplina...</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ $subjectId == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-xs font-bold text-muted uppercase">Trimestre</label>
                    <select name="term" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="1" {{ $term == 1 ? 'selected' : '' }}>1º Trimestre</option>
                        <option value="2" {{ $term == 2 ? 'selected' : '' }}>2º Trimestre</option>
                        <option value="3" {{ $term == 3 ? 'selected' : '' }}>3º Trimestre</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-xs font-bold text-muted uppercase">Tipo de Avaliação</label>
                    <select name="assessment_type" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="ACS1" {{ $assessmentType == 'ACS1' ? 'selected' : '' }}>ACS 1 (Contínua 1)</option>
                        <option value="ACS2" {{ $assessmentType == 'ACS2' ? 'selected' : '' }}>ACS 2 (Contínua 2)</option>
                        <option value="ACS3" {{ $assessmentType == 'ACS3' ? 'selected' : '' }}>ACS 3 (Contínua 3)</option>
                        <option value="ACP" {{ $assessmentType == 'ACP' ? 'selected' : '' }}>ACP (Parcial)</option>
                        <option value="ACF" {{ $assessmentType == 'ACF' ? 'selected' : '' }}>ACF / Exame</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <a href="{{ route('grades.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="fas fa-arrow-left me-1"></i> Voltar à Pauta
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($classId && $subjectId && count($students) > 0)
        <!-- Formulário de Lançamento em Lote -->
        <form method="POST" action="{{ route('grades.batch-store') }}" id="batchGradesForm">
            @csrf
            <input type="hidden" name="class_id" value="{{ $classId }}">
            <input type="hidden" name="subject_id" value="{{ $subjectId }}">
            <input type="hidden" name="term" value="{{ $term }}">
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="assessment_type" value="{{ $assessmentType }}">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-edit text-warning me-2"></i> Lançamento de Notas: {{ $assessmentType }}
                        </h5>
                        <p class="text-muted small mb-0">Digite as notas de 0 a 20 valores. Use as teclas Tab ou Enter para navegar para o próximo aluno.</p>
                    </div>

                    <button type="submit" class="btn btn-success font-weight-bold px-4 shadow-sm">
                        <i class="fas fa-save me-2"></i> Guardar Pauta de Notas
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">#</th>
                                    <th>Aluno</th>
                                    <th style="width: 140px;" class="text-center">Nota Gravada</th>
                                    <th style="width: 180px;" class="text-center">Nova Nota (0.0 a 20.0)</th>
                                    <th>Observações / Comentários</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                    @php
                                        $existingGrade = $existingGrades->get($student->id);
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3 bg-success-subtle text-success fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                                    {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold text-dark">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                    <div class="text-muted small">N.º {{ $student->student_number }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($existingGrade)
                                                <span class="badge {{ $existingGrade->grade >= 10 ? 'bg-success' : 'bg-danger' }} px-3 py-2 fs-6">
                                                    {{ number_format($existingGrade->grade, 1) }}
                                                </span>
                                            @else
                                                <span class="text-muted small">Pendente</span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="hidden" name="grades[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                            <input type="number" 
                                                   name="grades[{{ $student->id }}][grade]" 
                                                   class="form-control text-center font-weight-bold grade-input" 
                                                   min="0" 
                                                   max="20" 
                                                   step="0.1" 
                                                   value="{{ old('grades.' . $student->id . '.grade', isset($existingGrade->grade) ? number_format($existingGrade->grade, 1, '.', '') : '') }}"
                                                   placeholder="0 - 20"
                                                   style="font-size: 1.1rem;">
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="grades[{{ $student->id }}][comments]" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ old('grades.' . $student->id . '.comments', $existingGrade->comments ?? '') }}"
                                                   placeholder="Observação individual...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Total: {{ count($students) }} alunos nesta turma</span>
                    <button type="submit" class="btn btn-success font-weight-bold px-4 shadow-sm">
                        <i class="fas fa-save me-2"></i> Guardar Pauta de Notas
                    </button>
                </div>
            </div>
        </form>
    @else
        <div class="card border-0 shadow-sm py-5 text-center text-muted">
            <i class="fas fa-layer-group fa-4x text-secondary mb-3"></i>
            <h5>Selecione a Turma e a Disciplina</h5>
            <p class="mb-0">Escolha uma turma e disciplina no painel acima para iniciar o lançamento de notas.</p>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Destacar graficamente nota >= 10 ou < 10 ao digitar
    const inputs = document.querySelectorAll('.grade-input');
    inputs.forEach((input, idx) => {
        input.addEventListener('input', function() {
            const val = parseFloat(this.value);
            if (!isNaN(val)) {
                if (val >= 10) {
                    this.classList.add('border-success', 'text-success');
                    this.classList.remove('border-danger', 'text-danger');
                } else {
                    this.classList.add('border-danger', 'text-danger');
                    this.classList.remove('border-success', 'text-success');
                }
            } else {
                this.classList.remove('border-success', 'text-success', 'border-danger', 'text-danger');
            }
        });

        // Navegação com Enter para o próximo aluno
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (inputs[idx + 1]) {
                    inputs[idx + 1].focus();
                    inputs[idx + 1].select();
                }
            }
        });
    });
});
</script>
@endpush