@extends('layouts.app')

@section('title', 'Gestao de Pagamentos')
@section('page-title', 'Pagamentos')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Pagamentos</li>
@endsection

@section('content')
    @php
        $totalPenalties = \App\Models\Payment::where('penalty', '>', 0)->sum('penalty');
        $countWithPenalties = \App\Models\Payment::where('penalty', '>', 0)->count();
    @endphp

    <div class="payments-index space-y-6" x-data="paymentsIndex()">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Recebido</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format($stats['paid'], 2, ',', '.') }} MT</p>
                <p class="mt-2 text-sm text-emerald-700">Pagos</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pendente</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ number_format($stats['pending'], 2, ',', '.') }} MT</p>
                <p class="mt-2 text-sm text-amber-700">{{ $stats['count_pending'] }} pagamentos</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Em Atraso</p>
                <p class="mt-2 text-3xl font-bold text-rose-700">{{ number_format($stats['overdue'], 2, ',', '.') }} MT</p>
                <p class="mt-2 text-sm text-rose-700">{{ $stats['count_overdue'] }} em atraso</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Multas Aplicadas</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalPenalties, 2, ',', '.') }} MT</p>
                <p class="mt-2 text-sm text-slate-600">Total em multas</p>
            </article>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('payments.index') }}" class="grid gap-3 md:grid-cols-12 md:items-end">
                <div class="md:col-span-3">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Pesquisar</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500"
                        placeholder="Nome, matricula ou referencia...">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
                    <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">Todos</option>
                        <option value="pending" @selected(request('status') == 'pending')>Pendente</option>
                        <option value="paid" @selected(request('status') == 'paid')>Pago</option>
                        <option value="overdue" @selected(request('status') == 'overdue')>Em Atraso</option>
                        <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelado</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Tipo</label>
                    <select name="type" class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">Todos</option>
                        <option value="matricula" @selected(request('type') == 'matricula')>Matricula</option>
                        <option value="mensalidade" @selected(request('type') == 'mensalidade')>Mensalidade</option>
                        <option value="material" @selected(request('type') == 'material')>Material</option>
                        <option value="uniforme" @selected(request('type') == 'uniforme')>Uniforme</option>
                        <option value="outro" @selected(request('type') == 'outro')>Outro</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Mes</label>
                    <select name="month" class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">Todos</option>
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" @selected(request('month') == $num)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3 flex gap-2">
                    <button type="submit" class="flex-1 rounded-lg bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800">Filtrar</button>
                    <a href="{{ route('payments.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Limpar</a>
                </div>
            </form>
        </section>

        <section class="flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap gap-2">
                @can('create_payments')
                    <a href="{{ route('payments.create') }}" class="rounded-lg bg-sky-700 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-800">Novo Pagamento</a>
                @endcan
                <a href="{{ route('payments.overdue') }}" class="rounded-lg bg-amber-500 px-3 py-2 text-sm font-semibold text-white hover:bg-amber-600">Em Atraso ({{ $stats['count_overdue'] }})</a>
                <a href="{{ route('payments.with-penalties') }}" class="rounded-lg bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700">Com Multas @if($countWithPenalties > 0)<span class="ml-1 rounded-full bg-white px-2 py-0.5 text-xs text-rose-700">{{ $countWithPenalties }}</span>@endif</a>
                <a href="{{ route('payments.reports') }}" class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Relatorios</a>
            </div>
            <a href="{{ route('payments.references') }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Gerar Referencias</a>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">Lista de Pagamentos</h3>
                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $payments->total() }} registros</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold">Referencia</th>
                            <th class="px-3 py-2 text-left font-semibold">Aluno</th>
                            <th class="px-3 py-2 text-left font-semibold">Turma</th>
                            <th class="px-3 py-2 text-left font-semibold">Tipo</th>
                            <th class="px-3 py-2 text-left font-semibold">Mes/Ano</th>
                            <th class="px-3 py-2 text-right font-semibold">Valor</th>
                            <th class="px-3 py-2 text-left font-semibold">Vencimento</th>
                            <th class="px-3 py-2 text-left font-semibold">Status</th>
                            <th class="px-3 py-2 text-center font-semibold">Acoes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            <tr class="{{ $payment->penalty > 0 ? 'bg-amber-50/40' : '' }} {{ $payment->is_blocked ? 'bg-rose-50/50' : '' }}">
                                <td class="px-3 py-2">
                                    <p class="font-mono text-xs font-semibold text-slate-800">{{ $payment->reference_number }}</p>
                                    @if($payment->penalty > 0)
                                        <p class="text-xs text-rose-700">Multa: {{ $payment->penalty_percentage }}%</p>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        @if($payment->student->photo_url)
                                            <img src="{{ $payment->student->photo_url }}" class="h-8 w-8 rounded-full object-cover" alt="{{ $payment->student->full_name }}">
                                        @else
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-xs text-slate-500">
                                                {{ strtoupper(substr($payment->student->full_name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $payment->student->full_name }}</p>
                                            <p class="text-xs text-slate-500">{{ $payment->student->student_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2 text-slate-700">{{ $payment->enrollment?->class?->name ?? 'N/A' }}</td>
                                <td class="px-3 py-2">
                                    @php
                                        $typeClass = $payment->type === 'matricula' ? 'bg-sky-100 text-sky-700' : ($payment->type === 'mensalidade' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700');
                                    @endphp
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $typeClass }}">{{ ucfirst($payment->type) }}</span>
                                </td>
                                <td class="px-3 py-2 text-slate-700">
                                    @if($payment->month)
                                        {{ $payment->month_name }}/{{ $payment->year }}
                                    @else
                                        {{ $payment->year }}
                                    @endif
                                    @if($payment->days_late > 0)
                                        <p class="text-xs text-rose-700">{{ $payment->days_late }} dias atrasado</p>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <p class="font-semibold text-slate-900">{{ number_format($payment->total_amount, 2, ',', '.') }} MT</p>
                                    @if($payment->discount > 0)
                                        <p class="text-xs text-emerald-700">-{{ number_format($payment->discount, 2, ',', '.') }} MT</p>
                                    @endif
                                    @if($payment->penalty > 0)
                                        <p class="text-xs text-rose-700">+{{ number_format($payment->penalty, 2, ',', '.') }} MT</p>
                                    @endif
                                </td>
                                <td class="px-3 py-2 {{ $payment->due_date && $payment->due_date < now() && $payment->status != 'paid' ? 'font-semibold text-rose-700' : 'text-slate-700' }}">{{ $payment->due_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                <td class="px-3 py-2">
                                    @php
                                        $statusClass = $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($payment->status === 'overdue' ? 'bg-rose-100 text-rose-700' : ($payment->status === 'cancelled' ? 'bg-slate-100 text-slate-700' : 'bg-amber-100 text-amber-700'));
                                    @endphp
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                                    @if($payment->is_blocked)
                                        <p class="mt-1 inline-block rounded-full bg-slate-900 px-2 py-0.5 text-[10px] font-semibold text-white">BLOQUEADO</p>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <div class="inline-flex flex-wrap justify-center gap-1">
                                        <a href="{{ route('payments.show', $payment) }}" class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Ver</a>

                                        @if($payment->status == 'pending' || $payment->status == 'overdue')
                                            @can('process_payments')
                                                <button type="button" class="rounded-lg bg-emerald-600 px-2 py-1 text-xs font-semibold text-white hover:bg-emerald-700"
                                                    @click="openProcessModal({{ $payment->id }})">Processar</button>
                                                @if($payment->penalty == 0 && $payment->days_late >= 15)
                                                    <button type="button" class="rounded-lg bg-amber-500 px-2 py-1 text-xs font-semibold text-white hover:bg-amber-600"
                                                        @click="openPenaltyModal({{ $payment->id }})">Multa</button>
                                                @endif
                                            @endcan
                                        @endif

                                        @if($payment->penalty > 0)
                                            @can('process_payments')
                                                <button type="button" class="rounded-lg border border-rose-300 px-2 py-1 text-xs text-rose-700 hover:bg-rose-50"
                                                    @click="openRemovePenaltyModal({{ $payment->id }})">Remover Multa</button>
                                            @endcan
                                        @endif

                                        <a href="{{ route('payments.download-reference', $payment) }}" target="_blank" class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Imprimir</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-8 text-center text-sm text-slate-500">
                                    Nenhum pagamento encontrado.
                                    <div class="mt-3">
                                        <a href="{{ route('payments.create') }}" class="rounded-lg bg-sky-700 px-3 py-2 text-xs font-semibold text-white hover:bg-sky-800">Criar Primeiro Pagamento</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">{{ $payments->links() }}</div>
            @endif
        </section>

        <div x-show="processModal.open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" style="display: none;">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl" @click.outside="processModal.open = false">
                <h4 class="text-base font-semibold text-slate-900">Processar Pagamento</h4>
                <form :action="processModal.action" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Metodo de Pagamento *</label>
                        <select name="payment_method" class="w-full rounded-lg border-slate-300 text-sm" required>
                            <option value="">Selecione...</option>
                            <option value="cash">Dinheiro</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="emola">e-Mola</option>
                            <option value="bank">Transferencia Bancaria</option>
                            <option value="multicaixa">Multicaixa</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">ID da Transacao</label>
                        <input type="text" name="transaction_id" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Ex: MP123456789">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Data do Pagamento *</label>
                        <input type="date" name="payment_date" class="w-full rounded-lg border-slate-300 text-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Observacoes</label>
                        <textarea name="notes" class="w-full rounded-lg border-slate-300 text-sm" rows="2"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="processModal.open = false" class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="penaltyModal.open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" style="display: none;">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl" @click.outside="penaltyModal.open = false">
                <h4 class="text-base font-semibold text-slate-900">Aplicar Multa</h4>
                <div class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-700" x-html="penaltyModal.info"></div>
                <form :action="penaltyModal.action" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Porcentagem da Multa *</label>
                        <select x-model="penaltyModal.option" class="w-full rounded-lg border-slate-300 text-sm" required>
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
                        <input type="number" x-model="penaltyModal.custom" min="0" max="100" step="0.01" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Digite a porcentagem...">
                    </div>
                    <input type="hidden" name="penalty_percentage" :value="resolvedPenaltyPercentage()">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Motivo da Multa *</label>
                        <textarea name="reason" rows="3" required class="w-full rounded-lg border-slate-300 text-sm" placeholder="Explique o motivo..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="penaltyModal.open = false" class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700">Cancelar</button>
                        <button type="submit" @click="validatePenaltySubmit($event)" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white">Aplicar Multa</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="removePenaltyModal.open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" style="display: none;">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl" @click.outside="removePenaltyModal.open = false">
                <h4 class="text-base font-semibold text-slate-900">Remover Multa</h4>
                <div class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-700" x-html="removePenaltyModal.info"></div>
                <form :action="removePenaltyModal.action" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Motivo da Remocao *</label>
                        <textarea name="reason" rows="3" required class="w-full rounded-lg border-slate-300 text-sm" placeholder="Explique o motivo..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="removePenaltyModal.open = false" class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white">Remover Multa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        [data-bs-theme="dark"] .payments-index .bg-white { background-color: var(--card-bg) !important; }
        [data-bs-theme="dark"] .payments-index .bg-slate-50 { background-color: rgba(148, 163, 184, 0.08) !important; }
        [data-bs-theme="dark"] .payments-index .border-slate-100,
        [data-bs-theme="dark"] .payments-index .border-slate-200,
        [data-bs-theme="dark"] .payments-index .border-slate-300 { border-color: var(--border-color) !important; }
        [data-bs-theme="dark"] .payments-index .text-slate-900,
        [data-bs-theme="dark"] .payments-index .text-slate-800,
        [data-bs-theme="dark"] .payments-index .text-slate-700,
        [data-bs-theme="dark"] .payments-index .text-slate-600,
        [data-bs-theme="dark"] .payments-index .text-slate-500 { color: var(--text-secondary) !important; }
    </style>
@endpush

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
                        <strong>Referencia:</strong> ${data.reference_number || 'N/A'}<br>
                        <strong>Aluno:</strong> ${data.student?.full_name || 'N/A'}<br>
                        <strong>Valor Original:</strong> ${this.formatMoney(data.amount)} MT<br>
                        <strong>Dias em Atraso:</strong> <span class="text-rose-700">${data.days_late || 0} dias</span><br>
                        <strong>Multa Sugerida:</strong> <span class="text-amber-700">${data.suggested_penalty_percentage || 0}%</span>
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
                        <strong>Referencia:</strong> ${data.reference_number || 'N/A'}<br>
                        <strong>Aluno:</strong> ${data.student?.full_name || 'N/A'}<br>
                        <strong>Valor Original:</strong> ${this.formatMoney(data.amount)} MT<br>
                        <strong>Multa Atual:</strong> <span class="text-rose-700">${data.penalty_percentage || 0}% (${this.formatMoney(data.penalty)} MT)</span><br>
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
                    window.VisionariosSchool?.showToast?.('Informe uma porcentagem valida de multa.', 'warning');
                }
            }
        };
    }
</script>
@endpush
