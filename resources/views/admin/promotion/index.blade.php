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
            <!-- Card de Filtro e Restauração -->
            <div class="school-card mb-4">
                <div class="school-card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <form action="{{ route('admin.promotion.index') }}" method="GET" class="row g-3 flex-grow-1">
                        <div class="col-md-8">
                            <label class="form-label font-weight-bold">Selecionar Turma</label>
                            <select name="class_id" class="form-select" onchange="this.form.submit()">
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
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary-school w-100">
                                <i class="fas fa-search me-2"></i> Listar Alunos
                            </button>
                        </div>
                    </form>

                    <!-- Botão de Restauração de Teste (Undo) -->
                    <form action="{{ route('admin.promotion.reset') }}" method="POST" onsubmit="return confirm('Deseja restaurar todas as matrículas e alunos do ano letivo para o estado Ativo?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm text-nowrap mt-3 mt-md-0" title="Desfazer transição e restaurar matrículas para Ativo">
                            <i class="fas fa-undo me-1"></i> Desfazer Transição / Restaurar Matrículas
                        </button>
                    </form>
                </div>
            </div>

            @if($classId)
                <div class="school-card">
                    <div class="school-card-header d-flex justify-content-between align-items-center">
                        <h3 class="school-card-title mb-0">
                            <i class="fas fa-users me-2"></i> Alunos da Turma
                        </h3>
                        <a href="{{ route('grades.batch-create', ['class_id' => $classId]) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-edit me-1"></i> Lançar / Editar Notas da Turma
                        </a>
                    </div>
                    <div class="school-card-body">
                        <form action="{{ route('admin.promotion.process') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-school table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th width="40">
                                                <input type="checkbox" class="form-check-input" id="check-all">
                                            </th>
                                            <th>Nº</th>
                                            <th>Nome do Aluno</th>
                                            <th>Média (MF)</th>
                                            <th>Resultado Sugerido</th>
                                            <th>Status Atual</th>
                                            <th class="text-end">Ação Rápida de Notas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $student)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                                        class="form-check-input student-check">
                                                </td>
                                                <td>{{ $student->student_number }}</td>
                                                <td class="fw-bold">{{ $student->full_name }}</td>
                                                <td>
                                                    @if($student->calculated_mf !== null)
                                                        <span class="fw-bold {{ $student->is_eligible ? 'text-success' : 'text-danger' }}">
                                                            {{ number_format($student->calculated_mf, 1) }}
                                                        </span>
                                                    @elseif($student->partial_mf !== null)
                                                        <span class="fw-bold text-warning" title="Média parcial calculada com base nas notas disponíveis">
                                                            {{ number_format($student->partial_mf, 1) }} <small class="text-muted">(Parcial)</small>
                                                        </span>
                                                    @else
                                                        <span class="text-muted small">Sem Notas</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($student->calculated_mf !== null)
                                                        @if($student->is_eligible)
                                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Aprovado</span>
                                                        @else
                                                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Reprovado</span>
                                                        @endif
                                                    @elseif($student->partial_mf !== null)
                                                        @if($student->is_eligible)
                                                            <span class="badge bg-success" title="Elegível com base na média parcial disponível"><i class="fas fa-check me-1"></i> Aprovado (Parcial)</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark" title="Pendente de avaliações completas"><i class="fas fa-exclamation-triangle me-1"></i> Em Risco ({{ number_format($student->partial_mf, 1) }})</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary"><i class="fas fa-clock me-1"></i> Avaliação Incompleta</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'info' }}">
                                                        {{ ucfirst($student->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('grades.batch-create', ['class_id' => $classId]) }}" class="btn btn-sm btn-outline-primary" title="Lançar ou atualizar notas">
                                                        <i class="fas fa-edit me-1"></i> Lançar Notas
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">
                                                    Nenhum aluno encontrado ou matriculado nesta turma.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if(!$students->isEmpty())
                                <div class="d-flex justify-content-end gap-3 mt-4">
                                    <button type="submit" name="action" value="retain" class="btn btn-outline-danger">
                                        <i class="fas fa-history me-2"></i> Reter Selecionados
                                    </button>
                                    <button type="submit" name="action" value="promote" class="btn btn-success">
                                        <i class="fas fa-arrow-up me-2"></i> Promover / Graduar Selecionados
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