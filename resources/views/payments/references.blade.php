@extends('layouts.app')

@section('title', 'Referências de Pagamento')
@section('page-title', 'Gerar Referências')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pagamentos</a></li>
    <li class="breadcrumb-item active">Referências</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Formulário de Geração --}}
        <div class="col-lg-4 mb-4">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-plus-circle"></i> Gerar Nova Referência
                </div>
                <div class="school-card-body">
                    <form action="{{ route('payments.generate-reference') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Aluno *</label>
                            <select name="student_id" class="form-select" required>
                                <option value="">Selecione o aluno...</option>
                                @foreach(\App\Models\Student::active()->with('currentEnrollment.class')->whereHas('currentEnrollment')->orderBy('first_name')->get() as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->student_number }} - {{ $student->full_name }}
                                        ({{ $student->currentEnrollment?->class?->name ?? 'Sem turma' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo *</label>
                            <select name="type" class="form-select" required>
                                <option value="mensalidade">Mensalidade</option>
                                <option value="matricula">Taxa de Matrícula</option>
                                <option value="material">Material Escolar</option>
                                <option value="uniforme">Uniforme</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold">Mês</label>
                                <select name="month" class="form-select">
                                    @foreach(['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'] as $i => $mes)
                                        <option value="{{ $i + 1 }}" {{ date('n') == $i + 1 ? 'selected' : '' }}>{{ $mes }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold">Ano *</label>
                                <select name="year" class="form-select" required>
                                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary-school w-100">
                            <i class="fas fa-receipt"></i> Gerar Referência
                        </button>
                    </form>
                </div>
            </div>

            {{-- Geração em Massa --}}
            <div class="school-card mt-4">
                <div class="school-card-header">
                    <i class="fas fa-layer-group"></i> Geração em Massa
                </div>
                <div class="school-card-body">
                    <p class="text-muted small mb-3">
                        Gere referências de mensalidade para todos os alunos de uma turma de uma só vez.
                    </p>
                    <form action="{{ route('payments.generate-reference') }}" method="POST" id="bulk-form">
                        @csrf
                        <input type="hidden" name="bulk" value="1">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Turma</label>
                            <select name="class_id" class="form-select" required>
                                <option value="">Selecione a turma...</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold">Mês</label>
                                <select name="bulk_month" class="form-select">
                                    @foreach(['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'] as $i => $mes)
                                        <option value="{{ $i + 1 }}" {{ date('n') == $i + 1 ? 'selected' : '' }}>{{ $mes }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold">Ano</label>
                                <select name="bulk_year" class="form-select">
                                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary-school w-100">
                            <i class="fas fa-magic"></i> Gerar para Toda Turma
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Lista de Referências Pendentes --}}
        <div class="col-lg-8">
            <div class="school-card mb-4">
                <div class="school-card-body">
                    <form method="GET" class="row g-2 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Filtrar por Turma</label>
                            <select name="class_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Todas as turmas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-outline-primary" onclick="printSelected()">
                                <i class="fas fa-print"></i> Imprimir Selecionados
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="school-table-container">
                <div class="school-table-header">
                    <h5 class="school-table-title">
                        <i class="fas fa-list"></i> Referências Pendentes
                    </h5>
                    <span class="badge bg-light text-dark">{{ $references->total() }} registros</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-school table-hover mb-0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all" class="form-check-input"></th>
                                <th>Referência</th>
                                <th>Aluno</th>
                                <th>Turma</th>
                                <th>Tipo</th>
                                <th>Período</th>
                                <th class="text-end">Valor</th>
                                <th>Vencimento</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($references as $ref)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input ref-checkbox" value="{{ $ref->id }}">
                                </td>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded fw-bold">{{ $ref->reference_number }}</code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $ref->student->photo_url }}" class="rounded-circle me-2" width="30" height="30">
                                        <div>
                                            <div class="fw-semibold small">{{ $ref->student->full_name }}</div>
                                            <small class="text-muted">{{ $ref->student->student_number }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $ref->enrollment?->class?->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $ref->type == 'mensalidade' ? 'success' : 'primary' }}">
                                        {{ ucfirst($ref->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($ref->month)
                                        {{ $ref->month_name }}/{{ $ref->year }}
                                    @else
                                        {{ $ref->year }}
                                    @endif
                                </td>
                                <td class="text-end">
                                    <strong>{{ number_format($ref->total_amount, 2, ',', '.') }} MT</strong>
                                </td>
                                <td>
                                    <span class="{{ $ref->due_date < now() ? 'text-danger fw-bold' : '' }}">
                                        {{ $ref->due_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('payments.show', $ref) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('payments.download-reference', $ref) }}" class="btn btn-sm btn-outline-secondary" title="Imprimir" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-receipt fa-3x mb-3"></i>
                                        <p>Nenhuma referência pendente encontrada</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($references->hasPages())
                <div class="card-footer bg-white">
                    {{ $references->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.ref-checkbox').forEach(cb => cb.checked = this.checked);
});

function printSelected() {
    const selected = Array.from(document.querySelectorAll('.ref-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        VisionariosSchool.showToast('Selecione pelo menos uma referência', 'warning');
        return;
    }
    // Abrir página de impressão em massa
    window.open(`/payments/print-bulk?ids=${selected.join(',')}`, '_blank');
}
</script>
@endpush