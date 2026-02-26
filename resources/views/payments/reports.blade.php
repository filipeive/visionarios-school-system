@extends('layouts.app')

@section('title', 'Relatorios Financeiros')
@section('page-title', 'Relatorios Financeiros')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pagamentos</a></li>
    <li class="breadcrumb-item active">Relatorios</li>
@endsection

@section('content')
    <div class="payments-reports space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" class="grid gap-3 md:grid-cols-2 md:items-end">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Ano de Referencia</label>
                    <select name="year" class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500" onchange="this.form.submit()">
                        @for($y = current_school_year() - 2; $y <= current_school_year(); $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="md:text-right">
                    <button type="button" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50" onclick="exportReport('pdf')">Exportar PDF</button>
                    <button type="button" class="ml-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700" onclick="exportReport('excel')">Exportar Excel</button>
                </div>
            </form>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Recebidos em {{ $year }}</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format($stats['total_year'], 2, ',', '.') }} MT</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total em Divida</p>
                <p class="mt-2 text-3xl font-bold text-rose-700">{{ number_format($stats['total_pending'], 2, ',', '.') }} MT</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alunos com Divida</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ $stats['total_students_debt'] }}</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="xl:col-span-2 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-3 text-sm font-semibold text-slate-900">Receita Mensal - {{ $year }}</h3>
                <div class="h-[320px]"><canvas id="monthlyRevenueChart"></canvas></div>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-3 text-sm font-semibold text-slate-900">Receita por Tipo</h3>
                <div class="h-[320px]"><canvas id="revenueByTypeChart"></canvas></div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-200 px-5 py-4"><h3 class="text-sm font-semibold text-slate-900">Resumo Mensal</h3></header>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold">Mes</th>
                                <th class="px-3 py-2 text-right font-semibold">Valor Recebido</th>
                                <th class="px-3 py-2 text-center font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php
                                $meses = ['Janeiro','Fevereiro','Marco','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
                            @endphp
                            @foreach($meses as $i => $mes)
                                @php $value = $monthlyRevenue[$i + 1] ?? 0; @endphp
                                <tr>
                                    <td class="px-3 py-2 text-slate-700">{{ $mes }}</td>
                                    <td class="px-3 py-2 text-right font-semibold text-slate-900">{{ number_format($value, 2, ',', '.') }} MT</td>
                                    <td class="px-3 py-2 text-center">
                                        @if($value > 0)
                                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Recebido</span>
                                        @elseif($i + 1 <= date('n') && $year == current_school_year())
                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Pendente</span>
                                        @else
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-slate-900">TOTAL</th>
                                <th class="px-3 py-2 text-right font-semibold text-slate-900">{{ number_format(array_sum($monthlyRevenue), 2, ',', '.') }} MT</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-200 px-5 py-4"><h3 class="text-sm font-semibold text-slate-900">Inadimplencia por Turma</h3></header>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold">Turma</th>
                                <th class="px-3 py-2 text-center font-semibold">Alunos</th>
                                <th class="px-3 py-2 text-right font-semibold">Valor em Divida</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($defaultersByClass as $item)
                                <tr>
                                    <td class="px-3 py-2 text-slate-700">{{ $item->name }}</td>
                                    <td class="px-3 py-2 text-center"><span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">{{ $item->count }}</span></td>
                                    <td class="px-3 py-2 text-right font-semibold text-rose-700">{{ number_format($item->total, 2, ',', '.') }} MT</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-3 py-6 text-center text-sm text-emerald-700">Nenhuma inadimplencia registrada.</td></tr>
                            @endforelse
                        </tbody>
                        @if($defaultersByClass->count() > 0)
                            <tfoot class="bg-slate-50">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-900">TOTAL</th>
                                    <th class="px-3 py-2 text-center font-semibold text-slate-900">{{ $defaultersByClass->sum('count') }}</th>
                                    <th class="px-3 py-2 text-right font-semibold text-rose-700">{{ number_format($defaultersByClass->sum('total'), 2, ',', '.') }} MT</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </article>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="mb-3 text-sm font-semibold text-slate-900">Acoes Rapidas</h3>
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('payments.overdue') }}" class="rounded-lg border border-slate-300 px-4 py-3 text-center text-sm text-slate-700 hover:bg-slate-50">Pagamentos em Atraso</a>
                <a href="{{ route('payments.references') }}" class="rounded-lg border border-slate-300 px-4 py-3 text-center text-sm text-slate-700 hover:bg-slate-50">Gerar Referencias</a>
                <a href="{{ route('payments.index', ['status' => 'paid']) }}" class="rounded-lg border border-slate-300 px-4 py-3 text-center text-sm text-slate-700 hover:bg-slate-50">Pagamentos Confirmados</a>
                <a href="{{ route('reports.export.payments') }}" class="rounded-lg border border-slate-300 px-4 py-3 text-center text-sm text-slate-700 hover:bg-slate-50">Exportar Dados</a>
            </div>
        </section>
    </div>
@endsection

@push('styles')
<style>
    [data-bs-theme="dark"] .payments-reports .bg-white { background-color: var(--card-bg) !important; }
    [data-bs-theme="dark"] .payments-reports .bg-slate-50 { background-color: rgba(148, 163, 184, 0.08) !important; }
    [data-bs-theme="dark"] .payments-reports .border-slate-100,
    [data-bs-theme="dark"] .payments-reports .border-slate-200,
    [data-bs-theme="dark"] .payments-reports .border-slate-300 { border-color: var(--border-color) !important; }
    [data-bs-theme="dark"] .payments-reports .text-slate-900,
    [data-bs-theme="dark"] .payments-reports .text-slate-800,
    [data-bs-theme="dark"] .payments-reports .text-slate-700,
    [data-bs-theme="dark"] .payments-reports .text-slate-600,
    [data-bs-theme="dark"] .payments-reports .text-slate-500 { color: var(--text-secondary) !important; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyData = @json($monthlyRevenue);
        const typeData = @json($revenueByType);
        const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        new Chart(document.getElementById('monthlyRevenueChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Receita (MT)',
                    data: months.map((_, i) => monthlyData[i + 1] || 0),
                    backgroundColor: 'rgba(25, 67, 124, 0.8)',
                    borderColor: '#19437C',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('pt-MZ') + ' MT';
                            }
                        }
                    }
                }
            }
        });

        const typeLabels = {
            matricula: 'Matricula',
            mensalidade: 'Mensalidade',
            material: 'Material',
            uniforme: 'Uniforme',
            outro: 'Outro'
        };

        new Chart(document.getElementById('revenueByTypeChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(typeData).map(k => typeLabels[k] || k),
                datasets: [{
                    data: Object.values(typeData),
                    backgroundColor: ['#19437C', '#4BA83C', '#F9A825', '#17a2b8', '#6c757d'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15 }
                    }
                }
            }
        });
    });

    function exportReport(format) {
        const year = document.querySelector('select[name="year"]').value;
        const target = format === 'pdf'
            ? `/reports/export/payments?year=${year}&format=pdf`
            : `/reports/export/payments?year=${year}&format=excel`;
        window.location.href = target;
    }
</script>
@endpush
