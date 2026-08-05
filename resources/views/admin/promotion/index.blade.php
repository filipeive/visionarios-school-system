@extends('layouts.app')

@section('title', 'Passagem de Classe')
@section('page-title', 'Passagem de Classe (Promoção)')
@section('page-title-icon', 'fas fa-graduation-cap')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.academic-years.index') }}">Gestão Acadêmica</a></li>
    <li class="breadcrumb-item active">Passagem de Classe</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <!-- Card de Seleção de Turma & Ações Globais -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <form action="{{ route('admin.promotion.index') }}" method="GET" class="row g-3 flex-grow-1 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Selecionar Turma Origem</label>
                            <select name="class_id" class="form-select rounded-xl border-slate-200" onchange="this.form.submit()">
                                <option value="">Selecione a turma...</option>
                                @if(is_iterable($classes))
                                    @foreach($classes as $class)
                                        <option value="{{ optional($class)->id }}" {{ $classId == optional($class)->id ? 'selected' : '' }}>
                                            {{ optional($class)->name }} ({{ optional($class)->grade_level_name }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary-school w-100 rounded-xl">
                                <i class="fas fa-search me-2"></i> Carregar Turma
                            </button>
                        </div>
                    </form>

                    <!-- Botão de Restauração (Undo) -->
                    <form action="{{ route('admin.promotion.reset') }}" method="POST" onsubmit="return confirm('Deseja restaurar todas as matrículas do ano letivo para o estado Ativo?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning rounded-xl text-nowrap mt-2 mt-md-0" title="Desfazer transição">
                            <i class="fas fa-undo me-1"></i> Restaurar Matrículas
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if($classId && isset($students))
            @php
                $totalStudents = count($students);
                $approvedCount = collect($students)->filter(fn($s) => $s->is_eligible)->count();
                $failedCount = collect($students)->filter(fn($s) => $s->calculated_mf !== null && !$s->is_eligible)->count();
                $noGradesCount = collect($students)->filter(fn($s) => $s->calculated_mf === null && $s->partial_mf === null)->count();
            @endphp

            <!-- Stat KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold">Total na Turma</div>
                                <h4 class="mb-0 text-primary font-weight-bold">{{ $totalStudents }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold">Elegíveis (Aprovados)</div>
                                <h4 class="mb-0 text-success font-weight-bold">{{ $approvedCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-danger">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-danger-subtle text-danger rounded-circle p-3 me-3">
                                <i class="fas fa-times-circle fa-lg"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold">Reprovados / Risco</div>
                                <h4 class="mb-0 text-danger font-weight-bold">{{ $failedCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-warning">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-warning-subtle text-warning rounded-circle p-3 me-3">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold">Sem Avaliação</div>
                                <h4 class="mb-0 text-warning font-weight-bold">{{ $noGradesCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Alunos da Turma -->
            <div class="school-table-container">
                <div class="school-table-header">
                    <h3 class="school-table-title">
                        <i class="fas fa-graduation-cap text-primary me-2"></i>
                        Alunos da Turma
                        <span class="badge bg-primary ms-2" style="font-size: 0.55em;">{{ $totalStudents }}</span>
                    </h3>
                    <a href="{{ route('grades.batch-create', ['class_id' => $classId]) }}" class="btn btn-secondary-school">
                        <i class="fas fa-edit me-1"></i> Lançar / Editar Notas
                    </a>
                </div>

                <div class="school-card-body p-0">
                    <form action="{{ route('admin.promotion.process') }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-school">
                                <thead>
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" class="form-check-input" id="check-all">
                                        </th>
                                        <th>Nº Estudante</th>
                                        <th>Nome do Aluno</th>
                                        <th>Média Final (MF)</th>
                                        <th>Resultado Sugerido</th>
                                        <th>Status Atual</th>
                                        <th class="text-end">Ação Rápida</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                                    class="form-check-input student-check">
                                            </td>
                                            <td>
                                                <code>{{ $student->student_number }}</code>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-slate-800">{{ $student->full_name }}</div>
                                            </td>
                                            <td>
                                                @if($student->calculated_mf !== null)
                                                    <span class="fw-bold fs-6 {{ $student->is_eligible ? 'text-success' : 'text-danger' }}">
                                                        {{ number_format($student->calculated_mf, 1) }}
                                                    </span>
                                                @elseif($student->partial_mf !== null)
                                                    <span class="fw-bold text-warning" title="Média parcial disponível">
                                                        {{ number_format($student->partial_mf, 1) }} <small class="text-muted text-xs">(Parcial)</small>
                                                    </span>
                                                @else
                                                    <span class="text-muted small">Sem Notas</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($student->calculated_mf !== null)
                                                    @if($student->is_eligible)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                                            <i class="fas fa-check-circle me-1"></i> Aprovado
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">
                                                            <i class="fas fa-times-circle me-1"></i> Reprovado
                                                        </span>
                                                    @endif
                                                @elseif($student->partial_mf !== null)
                                                    @if($student->is_eligible)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                                            <i class="fas fa-check me-1"></i> Aprovado (Parcial)
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">
                                                            <i class="fas fa-exclamation-triangle me-1"></i> Em Risco
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3 py-1">
                                                        <i class="fas fa-clock me-1"></i> Incompleto
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                                    {{ ucfirst($student->status) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('grades.batch-create', ['class_id' => $classId]) }}" class="btn btn-sm btn-outline-primary" title="Lançar notas">
                                                    <i class="fas fa-edit me-1"></i> Lançar Notas
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="fas fa-graduation-cap fa-3x mb-3 text-slate-300"></i>
                                                <h5>Nenhum aluno encontrado</h5>
                                                <p class="text-xs">Não existem alunos matriculados nesta turma.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(!empty($students))
                            <div class="p-3 border-top d-flex justify-content-end gap-3 bg-light">
                                <button type="submit" name="action" value="retain" class="btn btn-outline-danger rounded-xl">
                                    <i class="fas fa-history me-1"></i> Reter Selecionados
                                </button>
                                <button type="submit" name="action" value="promote" class="btn btn-success rounded-xl font-semibold">
                                    <i class="fas fa-arrow-up me-1"></i> Promover / Graduar Selecionados
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('check-all');
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            document.querySelectorAll('.student-check').forEach(cb => cb.checked = this.checked);
        });
    }
});
</script>
@endpush