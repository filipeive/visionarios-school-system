@extends('layouts.app')

@section('title', 'Pagamentos em Atraso')
@section('page-title', 'Pagamentos em Atraso')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pagamentos</a></li>
    <li class="breadcrumb-item active">Em Atraso</li>
@endsection

@section('content')
    <div class="payments-overdue space-y-6" x-data="overduePage()">
        <section class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
            <p class="text-sm"><strong>Atencao:</strong> existem <strong>{{ $payments->total() }}</strong> pagamentos em atraso totalizando aproximadamente <strong>{{ number_format($payments->sum('amount'), 2, ',', '.') }} MT</strong>.</p>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Gestao de Inadimplencia</h3>
                    <p class="text-xs text-slate-500">Gerencie pagamentos pendentes e envie lembretes.</p>
                </div>
                <div class="flex gap-2">
                    <button class="rounded-lg bg-amber-500 px-3 py-2 text-sm font-semibold text-white hover:bg-amber-600" @click="sendBulkReminder()">Enviar Lembretes</button>
                    <a href="{{ route('reports.financial.defaulters') }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Gerar Relatorio</a>
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 bg-rose-600 px-5 py-4 text-white">
                <h3 class="text-sm font-semibold">Pagamentos em Atraso</h3>
                <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-rose-700">{{ $payments->total() }} registros</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-3 py-2 text-left"><input type="checkbox" id="select-all" class="rounded border-slate-300" @change="toggleAll($event)"></th>
                            <th class="px-3 py-2 text-left font-semibold">Referencia</th>
                            <th class="px-3 py-2 text-left font-semibold">Aluno</th>
                            <th class="px-3 py-2 text-left font-semibold">Turma</th>
                            <th class="px-3 py-2 text-left font-semibold">Tipo</th>
                            <th class="px-3 py-2 text-left font-semibold">Periodo</th>
                            <th class="px-3 py-2 text-right font-semibold">Valor</th>
                            <th class="px-3 py-2 text-left font-semibold">Vencido em</th>
                            <th class="px-3 py-2 text-center font-semibold">Dias</th>
                            <th class="px-3 py-2 text-center font-semibold">Acoes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            @php
                                $diasAtraso = $payment->due_date->diffInDays(now());
                                $badgeClass = $diasAtraso > 60 ? 'bg-rose-100 text-rose-700' : ($diasAtraso > 30 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700');
                            @endphp
                            <tr>
                                <td class="px-3 py-2"><input type="checkbox" class="payment-checkbox rounded border-slate-300" value="{{ $payment->id }}"></td>
                                <td class="px-3 py-2 font-mono text-xs font-semibold text-slate-800">{{ $payment->reference_number }}</td>
                                <td class="px-3 py-2">
                                    <p class="text-sm font-semibold text-slate-900">{{ $payment->student->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $payment->student->student_number }}</p>
                                </td>
                                <td class="px-3 py-2 text-slate-700">{{ $payment->enrollment?->class?->name ?? 'N/A' }}</td>
                                <td class="px-3 py-2 text-slate-700">{{ ucfirst($payment->type) }}</td>
                                <td class="px-3 py-2 text-slate-700">@if($payment->month){{ $payment->month_name }}/{{ $payment->year }}@else{{ $payment->year }}@endif</td>
                                <td class="px-3 py-2 text-right font-semibold text-rose-700">{{ number_format($payment->total_amount, 2, ',', '.') }} MT</td>
                                <td class="px-3 py-2 font-semibold text-rose-700">{{ $payment->due_date->format('d/m/Y') }}</td>
                                <td class="px-3 py-2 text-center"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">{{ $diasAtraso }} dias</span></td>
                                <td class="px-3 py-2 text-center">
                                    <div class="inline-flex gap-1">
                                        <a href="{{ route('payments.show', $payment) }}" class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Ver</a>
                                        @can('process_payments')
                                            <button class="rounded-lg bg-emerald-600 px-2 py-1 text-xs font-semibold text-white hover:bg-emerald-700"
                                                @click="openProcessModal({{ $payment->id }}, '{{ $payment->reference_number }}', {{ $payment->total_amount }})">Processar</button>
                                        @endcan
                                        <button class="rounded-lg border border-amber-300 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50" @click="sendReminder({{ $payment->student->id }})">Lembrete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="px-3 py-8 text-center text-sm text-emerald-700">Parabens! Nao ha pagamentos em atraso.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">{{ $payments->links() }}</div>
            @endif
        </section>

        @if($payments->count() > 0)
            <section class="grid gap-6 md:grid-cols-2">
                <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold text-slate-900">Inadimplencia por Turma</h3>
                    <div class="h-[220px]"><canvas id="chartByClass"></canvas></div>
                </article>
                <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold text-slate-900">Inadimplencia por Tempo</h3>
                    <div class="h-[220px]"><canvas id="chartByDays"></canvas></div>
                </article>
            </section>
        @endif

        <div x-show="processOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" style="display:none;">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl" @click.outside="processOpen = false">
                <h4 class="text-base font-semibold text-slate-900">Processar Pagamento</h4>
                <div class="mt-3 rounded-lg bg-sky-50 p-3 text-sm text-sky-800">
                    <strong>Referencia:</strong> <span x-text="processRef"></span><br>
                    <strong>Valor:</strong> <span x-text="formatValue(processValue)"></span> MT
                </div>
                <form :action="processAction" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <div><label class="mb-1 block text-xs font-semibold text-slate-600">Metodo *</label>
                        <select name="payment_method" class="w-full rounded-lg border-slate-300 text-sm" required>
                            <option value="">Selecione...</option>
                            <option value="cash">Dinheiro</option><option value="mpesa">M-Pesa</option><option value="emola">e-Mola</option><option value="bank">Transferencia Bancaria</option><option value="multicaixa">Multicaixa</option>
                        </select>
                    </div>
                    <div><label class="mb-1 block text-xs font-semibold text-slate-600">ID da Transacao</label><input type="text" name="transaction_id" class="w-full rounded-lg border-slate-300 text-sm"></div>
                    <div><label class="mb-1 block text-xs font-semibold text-slate-600">Data *</label><input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border-slate-300 text-sm" required></div>
                    <div><label class="mb-1 block text-xs font-semibold text-slate-600">Observacoes</label><textarea name="notes" rows="2" class="w-full rounded-lg border-slate-300 text-sm"></textarea></div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="processOpen=false" class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    [data-bs-theme="dark"] .payments-overdue .bg-white { background-color: var(--card-bg) !important; }
    [data-bs-theme="dark"] .payments-overdue .bg-slate-50,
    [data-bs-theme="dark"] .payments-overdue .bg-slate-100 { background-color: rgba(148, 163, 184, 0.08) !important; }
    [data-bs-theme="dark"] .payments-overdue .border-slate-200,
    [data-bs-theme="dark"] .payments-overdue .border-slate-300 { border-color: var(--border-color) !important; }
    [data-bs-theme="dark"] .payments-overdue .text-slate-900,
    [data-bs-theme="dark"] .payments-overdue .text-slate-800,
    [data-bs-theme="dark"] .payments-overdue .text-slate-700,
    [data-bs-theme="dark"] .payments-overdue .text-slate-600,
    [data-bs-theme="dark"] .payments-overdue .text-slate-500 { color: var(--text-secondary) !important; }
</style>
@endpush

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
            if (confirm('Enviar lembrete de pagamento para o encarregado deste aluno?')) {
                window.VisionariosSchool?.showToast?.('Lembrete enviado com sucesso!', 'success');
            }
        },
        sendBulkReminder() {
            const selected = document.querySelectorAll('.payment-checkbox:checked');
            if (selected.length === 0) {
                window.VisionariosSchool?.showToast?.('Selecione pelo menos um pagamento', 'warning');
                return;
            }
            if (confirm(`Enviar lembretes para ${selected.length} pagamentos selecionados?`)) {
                window.VisionariosSchool?.showToast?.(`Lembretes enviados para ${selected.length} encarregados!`, 'success');
            }
        }
    };
}

@if($payments->count() > 0)
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart !== 'undefined') {
        const byClass = @json($payments->groupBy('enrollment.class.name')->map->count());
        const byDays = {
            'Ate 30 dias': {{ $payments->filter(fn($p) => $p->due_date->diffInDays(now()) <= 30)->count() }},
            '31-60 dias': {{ $payments->filter(fn($p) => $p->due_date->diffInDays(now()) > 30 && $p->due_date->diffInDays(now()) <= 60)->count() }},
            'Mais de 60 dias': {{ $payments->filter(fn($p) => $p->due_date->diffInDays(now()) > 60)->count() }}
        };

        new Chart(document.getElementById('chartByClass'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(byClass),
                datasets: [{ data: Object.values(byClass), backgroundColor: ['#19437C', '#4BA83C', '#F9A825', '#DC3545', '#17a2b8', '#6c757d'] }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        new Chart(document.getElementById('chartByDays'), {
            type: 'bar',
            data: {
                labels: Object.keys(byDays),
                datasets: [{ label: 'Pagamentos', data: Object.values(byDays), backgroundColor: ['#F9A825', '#fd7e14', '#DC3545'] }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }
});
@endif
</script>
@endpush
