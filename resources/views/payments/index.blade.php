@extends('layouts.app')

@section('title', 'Gestão de Pagamentos')
@section('page-title', 'Pagamentos & Propinas')
@section('page-title-icon', 'fas fa-money-bill-wave')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Pagamentos</li>
@endsection

@section('content')
    @php
        $totalPenalties = \App\Models\Payment::where('penalty', '>', 0)->sum('penalty');
        $countWithPenalties = \App\Models\Payment::where('penalty', '>', 0)->count();
    @endphp

<div class="payments-index row" x-data="paymentsIndex()">
    <div class="col-12">
        <!-- Stat KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                            <i class="fas fa-arrow-trend-up fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Total Recebido</div>
                            <h4 class="mb-0 text-success font-weight-bold">{{ number_format($stats['paid'], 2, ',', '.') }} <small class="text-xs text-muted">MT</small></h4>
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
                            <div class="text-muted small text-uppercase font-weight-bold">Pendente</div>
                            <h4 class="mb-0 text-warning font-weight-bold">{{ number_format($stats['pending'], 2, ',', '.') }} <small class="text-xs text-muted">MT</small></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-danger">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger-subtle text-danger rounded-circle p-3 me-3">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Em Atraso</div>
                            <h4 class="mb-0 text-danger font-weight-bold">{{ number_format($stats['overdue'], 2, ',', '.') }} <small class="text-xs text-muted">MT</small></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-scale-balanced fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Multas Aplicadas</div>
                            <h4 class="mb-0 text-info font-weight-bold">{{ number_format($totalPenalties, 2, ',', '.') }} <small class="text-xs text-muted">MT</small></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Harmonizados -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form method="GET" action="{{ route('payments.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Pesquisar</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control rounded-xl border-slate-200"
                            placeholder="Nome, matrícula ou referência...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Status</label>
                        <select name="status" class="form-select rounded-xl border-slate-200">
                            <option value="">Todos</option>
                            <option value="pending" @selected(request('status') == 'pending')>Pendente</option>
                            <option value="paid" @selected(request('status') == 'paid')>Pago</option>
                            <option value="overdue" @selected(request('status') == 'overdue')>Em Atraso</option>
                            <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Tipo</label>
                        <select name="type" class="form-select rounded-xl border-slate-200">
                            <option value="">Todos</option>
                            <option value="matricula" @selected(request('type') == 'matricula')>Matrícula</option>
                            <option value="mensalidade" @selected(request('type') == 'mensalidade')>Mensalidade</option>
                            <option value="material" @selected(request('type') == 'material')>Material</option>
                            <option value="uniforme" @selected(request('type') == 'uniforme')>Uniforme</option>
                            <option value="outro" @selected(request('type') == 'outro')>Outro</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Mês</label>
                        <select name="month" class="form-select rounded-xl border-slate-200">
                            <option value="">Todos</option>
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" @selected(request('month') == $num)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-school flex-grow rounded-xl">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'type', 'month']))
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary rounded-xl" title="Limpar Filtros">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela Principal de Pagamentos -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-list text-primary me-2"></i>
                    Lista de Pagamentos
                    <span class="badge bg-primary ms-2" style="font-size: 0.55em;">{{ $payments->total() }}</span>
                </h3>
                <div class="d-flex flex-wrap gap-2">
                    @can('create_payments')
                        <a href="{{ route('payments.create') }}" class="btn btn-secondary-school">
                            <i class="fas fa-plus me-1"></i> Novo Pagamento
                        </a>
                    @endcan
                    <a href="{{ route('payments.overdue') }}" class="btn btn-outline-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i> Em Atraso ({{ $stats['count_overdue'] }})
                    </a>
                    <a href="{{ route('payments.references') }}" class="btn btn-outline-primary">
                        <i class="fas fa-receipt me-1"></i> Referências
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Referência</th>
                            <th>Aluno</th>
                            <th>Turma</th>
                            <th>Tipo</th>
                            <th>Mês/Ano</th>
                            <th class="text-end">Valor</th>
                            <th>Vencimento</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="{{ $payment->penalty > 0 ? 'bg-warning-subtle' : '' }}">
                                <td>
                                    <code>{{ $payment->reference_number }}</code>
                                    @if($payment->penalty > 0)
                                        <br><small class="text-danger fw-bold">Multa: {{ $payment->penalty_percentage }}%</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($payment->student->photo_url)
                                            <img src="{{ $payment->student->photo_url }}" class="rounded-circle me-2" style="width: 34px; height: 34px; object-fit: cover;" alt="{{ $payment->student->full_name }}">
                                        @else
                                            <div class="rounded-circle bg-primary-subtle text-primary fw-bold me-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-size: 13px;">
                                                {{ strtoupper(substr($payment->student->full_name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-slate-800">{{ $payment->student->full_name }}</div>
                                            <small class="text-muted"><code>{{ $payment->student->student_number }}</code></small>
                                        </div>
                                    </div>
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
                                    @if($payment->days_late > 0)
                                        <br><small class="text-danger font-bold">{{ $payment->days_late }} dias atrasado</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <strong class="text-slate-900 fs-6">{{ number_format($payment->total_amount, 2, ',', '.') }} MT</strong>
                                    @if($payment->discount > 0)
                                        <br><small class="text-success">-{{ number_format($payment->discount, 2, ',', '.') }} MT</small>
                                    @endif
                                    @if($payment->penalty > 0)
                                        <br><small class="text-danger">+{{ number_format($payment->penalty, 2, ',', '.') }} MT</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ $payment->due_date && $payment->due_date < now() && $payment->status != 'paid' ? 'fw-bold text-danger' : 'text-muted' }}">
                                        {{ $payment->due_date?->format('d/m/Y') ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->status === 'paid')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                            <i class="fas fa-check me-1"></i> Pago
                                        </span>
                                    @elseif($payment->status === 'overdue')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">
                                            <i class="fas fa-exclamation-circle me-1"></i> Atrasado
                                        </span>
                                    @elseif($payment->status === 'cancelled')
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">
                                            Cancelado
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">
                                            <i class="fas fa-clock me-1"></i> Pendente
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-outline-primary" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($payment->status == 'pending' || $payment->status == 'overdue')
                                            @can('process_payments')
                                                <button type="button" class="btn btn-outline-success" title="Processar Pagamento"
                                                    @click="openProcessModal({{ $payment->id }})"><i class="fas fa-check"></i></button>
                                                @if($payment->penalty == 0 && $payment->days_late >= 15)
                                                    <button type="button" class="btn btn-outline-warning" title="Aplicar Multa"
                                                        @click="openPenaltyModal({{ $payment->id }})"><i class="fas fa-scale-balanced"></i></button>
                                                @endif
                                            @endcan
                                        @endif

                                        @if($payment->penalty > 0)
                                            @can('process_payments')
                                                <button type="button" class="btn btn-outline-danger" title="Remover Multa"
                                                    @click="openRemovePenaltyModal({{ $payment->id }})"><i class="fas fa-times"></i></button>
                                            @endcan
                                        @endif

                                        <a href="{{ route('payments.download-reference', $payment) }}" target="_blank" class="btn btn-outline-secondary" title="Imprimir Referência">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-money-bill-wave fa-3x mb-3 text-slate-300"></i>
                                    <h5>Nenhum pagamento encontrado</h5>
                                    <p class="text-xs">Tente ajustar os filtros de pesquisa.</p>
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

        <!-- Modais em Alpine.js -->
        <div x-show="processModal.open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" style="display: none;">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl" @click.outside="processModal.open = false">
                <h4 class="text-base font-semibold text-slate-900">Processar Pagamento</h4>
                <form :action="processModal.action" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Método de Pagamento *</label>
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
                        <input type="text" name="transaction_id" class="form-control rounded-xl text-sm" placeholder="Ex: MP123456789">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Data do Pagamento *</label>
                        <input type="date" name="payment_date" class="form-control rounded-xl text-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Observações</label>
                        <textarea name="notes" class="form-control rounded-xl text-sm" rows="2"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" @click="processModal.open = false" class="btn btn-outline-secondary btn-sm rounded-xl">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-sm rounded-xl font-semibold">Confirmar Pagamento</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="penaltyModal.open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" style="display: none;">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl" @click.outside="penaltyModal.open = false">
                <h4 class="text-base font-semibold text-slate-900">Aplicar Multa</h4>
                <div class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-700 mb-3" x-html="penaltyModal.info"></div>
                <form :action="penaltyModal.action" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Porcentagem da Multa *</label>
                        <select x-model="penaltyModal.option" class="form-select rounded-xl text-sm" required>
                            <option value="">Selecione...</option>
                            <option value="10">10% (15-29 dias)</option>
                            <option value="25">25% (30-59 dias)</option>
                            <option value="50">50% (60-89 dias)</option>
                            <option value="100">100% (90+ dias)</option>
                            <option value="custom">Personalizado...</option>
                        </select>
                    </div>
                    <div x-show="penaltyModal.option === 'custom'" style="display:none;">
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Porcentagem Personalizada</label>
                        <input type="number" x-model="penaltyModal.custom" min="0" max="100" step="0.01" class="form-control rounded-xl text-sm" placeholder="Digite a porcentagem...">
                    </div>
                    <input type="hidden" name="penalty_percentage" :value="resolvedPenaltyPercentage()">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Motivo da Multa *</label>
                        <textarea name="reason" rows="3" required class="form-control rounded-xl text-sm" placeholder="Explique o motivo..."></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" @click="penaltyModal.open = false" class="btn btn-outline-secondary btn-sm rounded-xl">Cancelar</button>
                        <button type="submit" @click="validatePenaltySubmit($event)" class="btn btn-warning btn-sm rounded-xl font-semibold">Aplicar Multa</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="removePenaltyModal.open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" style="display: none;">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl" @click.outside="removePenaltyModal.open = false">
                <h4 class="text-base font-semibold text-slate-900">Remover Multa</h4>
                <div class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-700 mb-3" x-html="removePenaltyModal.info"></div>
                <form :action="removePenaltyModal.action" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Motivo da Remoção *</label>
                        <textarea name="reason" rows="3" required class="form-control rounded-xl text-sm" placeholder="Explique o motivo..."></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" @click="removePenaltyModal.open = false" class="btn btn-outline-secondary btn-sm rounded-xl">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-sm rounded-xl font-semibold">Remover Multa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function paymentsIndex() {
        return {
            processModal: { open: false, action: '' },
            penaltyModal: { open: false, action: '', info: '', option: '', custom: '' },
            removePenaltyModal: { open: false, action: '', info: '' },

            openProcessModal(paymentId) {
                this.processModal.action = `/payments/${paymentId}/process`;
                this.processModal.open = true;
            },

            async fetchPayment(paymentId) {
                const response = await fetch(`/payments/${paymentId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!response.ok) throw new Error('Erro ao carregar pagamento');
                return response.json();
            },

            formatMoney(value) {
                return parseFloat(value || 0).toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            },

            async openPenaltyModal(paymentId) {
                try {
                    const data = await this.fetchPayment(paymentId);
                    this.penaltyModal.info = `
                        <strong>Referência:</strong> ${data.reference_number || 'N/A'}<br>
                        <strong>Aluno:</strong> ${data.student?.full_name || 'N/A'}<br>
                        <strong>Valor Original:</strong> ${this.formatMoney(data.amount)} MT<br>
                        <strong>Dias em Atraso:</strong> <span class="text-danger font-bold">${data.days_late || 0} dias</span><br>
                        <strong>Multa Sugerida:</strong> <span class="text-warning font-bold">${data.suggested_penalty_percentage || 0}%</span>
                    `;
                    this.penaltyModal.action = `/payments/${paymentId}/apply-penalty`;
                    this.penaltyModal.option = String(data.suggested_penalty_percentage || '');
                    this.penaltyModal.custom = '';
                    this.penaltyModal.open = true;
                } catch {
                    window.location.href = `/payments/${paymentId}`;
                }
            },

            async openRemovePenaltyModal(paymentId) {
                try {
                    const data = await this.fetchPayment(paymentId);
                    this.removePenaltyModal.info = `
                        <strong>Referência:</strong> ${data.reference_number || 'N/A'}<br>
                        <strong>Aluno:</strong> ${data.student?.full_name || 'N/A'}<br>
                        <strong>Valor Original:</strong> ${this.formatMoney(data.amount)} MT<br>
                        <strong>Multa Atual:</strong> <span class="text-danger font-bold">${data.penalty_percentage || 0}% (${this.formatMoney(data.penalty)} MT)</span><br>
                        <strong>Total com Multa:</strong> ${this.formatMoney(data.total_amount)} MT
                    `;
                    this.removePenaltyModal.action = `/payments/${paymentId}/remove-penalty`;
                    this.removePenaltyModal.open = true;
                } catch {
                    window.location.href = `/payments/${paymentId}`;
                }
            },

            resolvedPenaltyPercentage() {
                return this.penaltyModal.option === 'custom' ? this.penaltyModal.custom : this.penaltyModal.option;
            },

            validatePenaltySubmit(event) {
                const value = this.resolvedPenaltyPercentage();
                if (!value || parseFloat(value) < 0 || parseFloat(value) > 100) {
                    event.preventDefault();
                    window.ZamEdu?.showToast?.('Informe uma porcentagem válida de multa.', 'warning');
                }
            }
        };
    }
</script>
@endpush
