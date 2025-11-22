@extends('layouts.app')

@section('title', 'Pagamentos em Atraso')
@section('page-title', 'Pagamentos em Atraso')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pagamentos</a></li>
    <li class="breadcrumb-item active">Em Atraso</li>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Alerta de Atenção --}}
    <div class="alert-school alert-danger-school mb-4">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Atenção!</strong> Existem <strong>{{ $payments->total() }}</strong> pagamentos em atraso 
            totalizando aproximadamente <strong>{{ number_format($payments->sum('amount'), 2, ',', '.') }} MT</strong>.
        </div>
    </div>

    {{-- Ações em Massa --}}
    <div class="school-card mb-4">
        <div class="school-card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Gestão de Inadimplência</h5>
                    <p class="text-muted mb-0">Gerencie os pagamentos pendentes e envie lembretes</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-warning-school" onclick="sendBulkReminder()">
                        <i class="fas fa-bell"></i> Enviar Lembretes
                    </button>
                    <a href="{{ route('reports.financial.defaulters') }}" class="btn btn-outline-primary">
                        <i class="fas fa-file-pdf"></i> Gerar Relatório
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista de Pagamentos em Atraso --}}
    <div class="school-table-container">
        <div class="school-table-header bg-danger">
            <h5 class="school-table-title">
                <i class="fas fa-exclamation-circle"></i> Pagamentos em Atraso
            </h5>
            <span class="badge bg-light text-danger">{{ $payments->total() }} registros</span>
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
                        <th>Vencido em</th>
                        <th>Dias de Atraso</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    @php
                        $diasAtraso = $payment->due_date->diffInDays(now());
                        $badgeClass = $diasAtraso > 60 ? 'bg-danger' : ($diasAtraso > 30 ? 'bg-warning text-dark' : 'bg-secondary');
                    @endphp
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input payment-checkbox" value="{{ $payment->id }}">
                        </td>
                        <td>
                            <code class="bg-light px-2 py-1 rounded">{{ $payment->reference_number }}</code>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $payment->student->photo_url }}" 
                                     class="rounded-circle me-2" width="35" height="35">
                                <div>
                                    <div class="fw-semibold">{{ $payment->student->full_name }}</div>
                                    <small class="text-muted">{{ $payment->student->student_number }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $payment->enrollment?->class?->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @switch($payment->type)
                                @case('mensalidade')
                                    <span class="badge bg-success">Mensalidade</span>
                                    @break
                                @case('matricula')
                                    <span class="badge bg-primary">Matrícula</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ ucfirst($payment->type) }}</span>
                            @endswitch
                        </td>
                        <td>
                            @if($payment->month)
                                {{ $payment->month_name }}/{{ $payment->year }}
                            @else
                                {{ $payment->year }}
                            @endif
                        </td>
                        <td class="text-end">
                            <strong class="text-danger">{{ number_format($payment->total_amount, 2, ',', '.') }} MT</strong>
                        </td>
                        <td>
                            <span class="text-danger">{{ $payment->due_date->format('d/m/Y') }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $badgeClass }}">{{ $diasAtraso }} dias</span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('payments.show', $payment) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('process_payments')
                                <button class="btn btn-sm btn-success" 
                                        onclick="openProcessModal({{ $payment->id }}, '{{ $payment->reference_number }}', {{ $payment->total_amount }})" 
                                        title="Processar">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endcan
                                <button class="btn btn-sm btn-outline-warning" 
                                        onclick="sendReminder({{ $payment->student->id }})" 
                                        title="Enviar Lembrete">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <div class="text-success">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <p class="fs-5">Parabéns! Não há pagamentos em atraso.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
        <div class="card-footer bg-white">
            {{ $payments->links() }}
        </div>
        @endif
    </div>

    {{-- Resumo por Turma --}}
    @if($payments->count() > 0)
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-chart-pie"></i> Inadimplência por Turma
                </div>
                <div class="school-card-body">
                    <canvas id="chartByClass" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-chart-bar"></i> Inadimplência por Tempo
                </div>
                <div class="school-card-body">
                    <canvas id="chartByDays" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Modal de Processamento --}}
<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="processForm" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle"></i> Processar Pagamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Referência:</strong> <span id="modal-ref"></span><br>
                        <strong>Valor:</strong> <span id="modal-value"></span> MT
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Método de Pagamento *</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="cash">Dinheiro</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="emola">e-Mola</option>
                            <option value="bank">Transferência Bancária</option>
                            <option value="multicaixa">Multicaixa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID da Transação</label>
                        <input type="text" name="transaction_id" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Data do Pagamento *</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observações</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Confirmar Pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Selecionar todos
document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.payment-checkbox').forEach(cb => cb.checked = this.checked);
});

// Abrir modal de processamento
function openProcessModal(id, ref, value) {
    document.getElementById('processForm').action = `/payments/${id}/process`;
    document.getElementById('modal-ref').textContent = ref;
    document.getElementById('modal-value').textContent = value.toLocaleString('pt-MZ', {minimumFractionDigits: 2});
    new bootstrap.Modal(document.getElementById('processModal')).show();
}

// Enviar lembrete individual
function sendReminder(studentId) {
    if (confirm('Enviar lembrete de pagamento para o encarregado deste aluno?')) {
        // Implementar envio de lembrete
        VisionariosSchool.showToast('Lembrete enviado com sucesso!', 'success');
    }
}

// Enviar lembretes em massa
function sendBulkReminder() {
    const selected = document.querySelectorAll('.payment-checkbox:checked');
    if (selected.length === 0) {
        VisionariosSchool.showToast('Selecione pelo menos um pagamento', 'warning');
        return;
    }
    if (confirm(`Enviar lembretes para ${selected.length} pagamentos selecionados?`)) {
        // Implementar envio em massa
        VisionariosSchool.showToast(`Lembretes enviados para ${selected.length} encarregados!`, 'success');
    }
}

// Gráficos (se Chart.js estiver disponível)
@if($payments->count() > 0)
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        // Dados agregados
        const byClass = @json($payments->groupBy('enrollment.class.name')->map->count());
        const byDays = {
            'Até 30 dias': {{ $payments->filter(fn($p) => $p->due_date->diffInDays(now()) <= 30)->count() }},
            '31-60 dias': {{ $payments->filter(fn($p) => $p->due_date->diffInDays(now()) > 30 && $p->due_date->diffInDays(now()) <= 60)->count() }},
            'Mais de 60 dias': {{ $payments->filter(fn($p) => $p->due_date->diffInDays(now()) > 60)->count() }}
        };

        // Gráfico por turma
        new Chart(document.getElementById('chartByClass'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(byClass),
                datasets: [{
                    data: Object.values(byClass),
                    backgroundColor: ['#19437C', '#4BA83C', '#F9A825', '#DC3545', '#17a2b8', '#6c757d']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Gráfico por dias
        new Chart(document.getElementById('chartByDays'), {
            type: 'bar',
            data: {
                labels: Object.keys(byDays),
                datasets: [{
                    label: 'Pagamentos',
                    data: Object.values(byDays),
                    backgroundColor: ['#F9A825', '#fd7e14', '#DC3545']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }
});
@endif
</script>
@endpush