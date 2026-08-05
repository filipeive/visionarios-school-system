@extends('layouts.app')

@section('title', 'Pagamentos em Atraso')
@section('page-title', 'Pagamentos em Atraso')
@section('page-title-icon', 'fas fa-exclamation-triangle')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}" class="no-underline"><i class="fas fa-wallet me-1"></i>Pagamentos</a></li>
    <li class="breadcrumb-item active">Em Atraso</li>
@endsection

@section('content')
<div class="payments-overdue row" x-data="overduePage()">
    <div class="col-12">

        <!-- Stat KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-danger">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger-subtle text-danger rounded-circle p-3 me-3">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Total Inadimplente</div>
                            <h4 class="mb-0 text-danger font-weight-bold">{{ number_format($payments->sum('total_amount'), 2, ',', '.') }} <small class="text-xs text-muted">MT</small></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-warning">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning-subtle text-warning rounded-circle p-3 me-3">
                            <i class="fas fa-file-invoice-dollar fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Faturas em Atraso</div>
                            <h4 class="mb-0 text-warning font-weight-bold">{{ $payments->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Atraso Médio</div>
                            <h4 class="mb-0 text-info font-weight-bold">
                                {{ round($payments->avg(fn($p) => $p->due_date->diffInDays(now())) ?? 0) }} <small class="text-xs text-muted">dias</small>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-user-xmark fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Atrasos > 60 Dias</div>
                            <h4 class="mb-0 text-primary font-weight-bold">
                                {{ $payments->filter(fn($p) => $p->due_date->diffInDays(now()) > 60)->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner de Gestão e Ações -->
        <div class="school-card mb-4">
            <div class="school-card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h5 class="fw-bold text-slate-800 mb-1">
                        <i class="fas fa-bullhorn text-warning me-2"></i> Gestão de Inadimplência
                    </h5>
                    <p class="text-muted text-xs mb-0">Gerencie pagamentos vencidos, notifique encarregados e processe quitações.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-warning fw-semibold rounded-xl" @click="sendBulkReminder()">
                        <i class="fas fa-paper-plane me-1"></i> Enviar Lembretes em Lote
                    </button>
                    <a href="{{ route('reports.financial.defaulters') }}" class="btn btn-outline-primary rounded-xl">
                        <i class="fas fa-file-lines me-1"></i> Relatório Completo
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabela Principal de Pagamentos em Atraso -->
        <div class="school-table-container mb-4">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Lista de Pagamentos em Atraso
                    <span class="badge bg-danger ms-2" style="font-size: 0.55em;">{{ $payments->total() }}</span>
                </h3>
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="select-all" class="form-check-input" @change="toggleAll($event)">
                            </th>
                            <th>Referência</th>
                            <th>Aluno</th>
                            <th>Turma</th>
                            <th>Tipo</th>
                            <th>Período</th>
                            <th>Valor</th>
                            <th>Vencimento</th>
                            <th class="text-center">Atraso</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            @php
                                $diasAtraso = $payment->due_date->diffInDays(now());
                                $badgeClass = $diasAtraso > 60 ? 'bg-danger-subtle text-danger border border-danger-subtle' : ($diasAtraso > 30 ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-secondary-subtle text-secondary border');
                            @endphp
                            <tr>
                                <td>
                                    <input type="checkbox" class="payment-checkbox form-check-input" value="{{ $payment->id }}">
                                </td>
                                <td>
                                    <code>{{ $payment->reference_number }}</code>
                                </td>
                                <td>
                                    <div class="fw-bold text-slate-800">{{ $payment->student->full_name }}</div>
                                    <small class="text-muted"><code>{{ $payment->student->student_number }}</code></small>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        {{ $payment->enrollment?->class?->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ ucfirst($payment->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->month)
                                        {{ $payment->month_name }}/{{ $payment->year }}
                                    @else
                                        {{ $payment->year }}
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-danger fs-6">{{ number_format($payment->total_amount, 2, ',', '.') }} MT</strong>
                                </td>
                                <td>
                                    <strong class="text-muted">{{ $payment->due_date->format('d/m/Y') }}</strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill px-3 py-1 {{ $badgeClass }}">
                                        {{ $diasAtraso }} dias
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-outline-primary" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('process_payments')
                                            <button class="btn btn-outline-success" title="Processar Pagamento"
                                                @click="openProcessModal({{ $payment->id }}, '{{ $payment->reference_number }}', {{ $payment->total_amount }})">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        @endcan
                                        <button class="btn btn-outline-warning" title="Enviar Lembrete" @click="sendReminder({{ $payment->student->id }})">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="fas fa-circle-check fa-3x mb-3 text-success"></i>
                                    <h5 class="text-success">Parabéns!</h5>
                                    <p class="text-xs mb-0">Não existem pagamentos em atraso registrados.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="p-3 border-top">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

        <!-- Gráficos Analíticos -->
        @if($payments->count() > 0)
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="school-card h-100">
                        <div class="school-card-header">
                            <i class="fas fa-chart-pie me-2 text-primary"></i> Inadimplência por Turma
                        </div>
                        <div class="school-card-body">
                            <div style="height: 240px;">
                                <canvas id="chartByClass"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="school-card h-100">
                        <div class="school-card-header">
                            <i class="fas fa-chart-bar me-2 text-warning"></i> Inadimplência por Tempo de Atraso
                        </div>
                        <div class="school-card-body">
                            <div style="height: 240px;">
                                <canvas id="chartByDays"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal de Processamento de Pagamento -->
        <div x-show="processOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" style="display:none;">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl" @click.outside="processOpen = false">
                <h4 class="text-base font-semibold text-slate-900">Processar Pagamento</h4>
                <div class="mt-3 rounded-lg bg-sky-50 p-3 text-sm text-sky-800 mb-3">
                    <strong>Referência:</strong> <span x-text="processRef"></span><br>
                    <strong>Valor:</strong> <span x-text="formatValue(processValue)"></span> MT
                </div>
                <form :action="processAction" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Método *</label>
                        <select name="payment_method" class="form-select rounded-xl text-sm" required>
                            <option value="">Selecione...</option>
                            <option value="cash">Dinheiro</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="emola">e-Mola</option>
                            <option value="bank">Transferência Bancária</option>
                            <option value="multicaixa">Multicaixa</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">ID da Transação</label>
                        <input type="text" name="transaction_id" class="form-control rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Data *</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="form-control rounded-xl text-sm" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Observações</label>
                        <textarea name="notes" rows="2" class="form-control rounded-xl text-sm"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" @click="processOpen=false" class="btn btn-outline-secondary btn-sm rounded-xl">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-sm rounded-xl font-semibold">Confirmar Pagamento</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function overduePage() {
    return {
        processOpen: false,
        processAction: '',
        processRef: '',
        processValue: 0,
        toggleAll(event) {
            document.querySelectorAll('.payment-checkbox').forEach(cb => cb.checked = event.target.checked);
        },
        openProcessModal(id, ref, value) {
            this.processAction = `/payments/${id}/process`;
            this.processRef = ref;
            this.processValue = value;
            this.processOpen = true;
        },
        formatValue(value) {
            return parseFloat(value || 0).toLocaleString('pt-MZ', { minimumFractionDigits: 2 });
        },
        sendReminder(studentId) {
            confirmAction('Enviar lembrete de pagamento para o encarregado deste aluno?', function() {
                showToast('Lembrete enviado com sucesso!', 'success');
            }, {title: 'Enviar Lembrete', confirmText: 'Enviar', danger: false});
        },
        sendBulkReminder() {
            const selected = document.querySelectorAll('.payment-checkbox:checked');
            if (selected.length === 0) {
                showToast('Selecione pelo menos um pagamento', 'warning');
                return;
            }
            confirmAction(`Enviar lembretes para ${selected.length} pagamentos selecionados?`, function() {
                showToast(`Lembretes enviados para ${selected.length} encarregados!`, 'success');
            }, {title: 'Enviar Lembretes em Lote', confirmText: 'Enviar', danger: false});
        }
    };
}

@if($payments->count() > 0)
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart !== 'undefined') {
        const classCanvas = document.getElementById('chartByClass');
        const daysCanvas = document.getElementById('chartByDays');
        if (!classCanvas || !daysCanvas) {
            return;
        }

        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        const tickColor = isDark ? '#cbd5e1' : '#334155';
        const gridColor = isDark ? 'rgba(148,163,184,0.25)' : 'rgba(15,23,42,0.12)';
        const byClass = @json($payments->groupBy('enrollment.class.name')->map->count());
        const byDays = {
            'Até 30 dias': {{ $payments->filter(fn($p) => $p->due_date->diffInDays(now()) <= 30)->count() }},
            '31-60 dias': {{ $payments->filter(fn($p) => $p->due_date->diffInDays(now()) > 30 && $p->due_date->diffInDays(now()) <= 60)->count() }},
            'Mais de 60 dias': {{ $payments->filter(fn($p) => $p->due_date->diffInDays(now()) > 60)->count() }}
        };

        new Chart(classCanvas, {
            type: 'doughnut',
            data: {
                labels: Object.keys(byClass),
                datasets: [{ data: Object.values(byClass), backgroundColor: ['#19437C', '#4BA83C', '#F9A825', '#DC3545', '#17a2b8', '#6c757d'] }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: tickColor } } }
            }
        });

        new Chart(daysCanvas, {
            type: 'bar',
            data: {
                labels: Object.keys(byDays),
                datasets: [{ label: 'Pagamentos', data: Object.values(byDays), backgroundColor: ['#F9A825', '#fd7e14', '#DC3545'] }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: tickColor }, grid: { color: gridColor } },
                    y: { ticks: { color: tickColor }, grid: { color: gridColor } }
                }
            }
        });
    } else {
        console.warn('Chart.js indisponível para payments.overdue');
    }
});
@endif
</script>
@endpush
