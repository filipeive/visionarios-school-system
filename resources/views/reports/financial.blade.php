@extends('layouts.app')

@section('title', 'Relatórios Financeiros')
@section('page-title', 'Visão Geral Financeira')
@section('page-title-icon', 'fas fa-wallet')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item active">Financeiro</li>
@endsection

@section('page-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('reports.export.payments') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800 transition">
            <i class="fas fa-file-excel"></i>
            Exportar CSV / Excel
        </a>
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
            <i class="fas fa-print"></i>
            Imprimir
        </button>
    </div>
@endsection

@section('content')
    <div class="space-y-6">

        <!-- Executive Summary Header Cards (Resumos Automáticos Inteligentes) -->
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50/90 p-4 shadow-sm">
                <div class="flex items-center gap-2 text-emerald-800 font-bold text-sm mb-1">
                    <i class="fas fa-chart-line"></i> O que aconteceu?
                </div>
                <p class="text-xs text-emerald-900 leading-relaxed">
                    {{ $financialSummary['what_happened'] }}
                </p>
            </div>

            <div class="rounded-xl border border-sky-200 bg-sky-50/90 p-4 shadow-sm">
                <div class="flex items-center gap-2 text-sky-800 font-bold text-sm mb-1">
                    <i class="fas fa-arrow-trend-up"></i> Qual a tendência?
                </div>
                <p class="text-xs text-sky-900 leading-relaxed">
                    {{ $financialSummary['trend'] }}
                </p>
            </div>

            <div class="rounded-xl border border-amber-200 bg-amber-50/90 p-4 shadow-sm">
                <div class="flex items-center gap-2 text-amber-800 font-bold text-sm mb-1">
                    <i class="fas fa-triangle-exclamation"></i> O que precisa de atenção?
                </div>
                <p class="text-xs text-amber-900 leading-relaxed">
                    {{ $financialSummary['attention'] }}
                </p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            
            <!-- Table: Recent Payments -->
            <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="text-base font-bold text-slate-900 font-heading">
                        <i class="fas fa-history text-emerald-700 me-2"></i> Útimos Pagamentos Efetuados
                    </h3>
                    <a href="{{ route('payments.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Ver Todos</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Aluno</th>
                                <th class="px-4 py-3 text-left font-semibold">Data</th>
                                <th class="px-4 py-3 text-left font-semibold">Valor</th>
                                <th class="px-4 py-3 text-left font-semibold">Método</th>
                                <th class="px-4 py-3 text-left font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentPayments as $payment)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $payment->student->first_name }} {{ $payment->student->last_name }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 font-bold text-emerald-700">
                                        {{ number_format($payment->amount, 2, ',', '.') }} MT
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block rounded bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-700 uppercase">
                                            {{ $payment->payment_method ?? 'M-Pesa' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-bold
                                            {{ $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            <i class="fas {{ $payment->status === 'paid' ? 'fa-check' : 'fa-clock' }}"></i>
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">Nenhum pagamento registado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Revenue Side Column -->
            <div class="space-y-6">
                <!-- Monthly Revenue List -->
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-bold text-slate-900 font-heading">
                            <i class="fas fa-calendar-alt text-emerald-700 me-2"></i> Receita por Mês
                        </h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($monthlyRevenue as $revenue)
                            <div class="flex items-center justify-between p-4 hover:bg-slate-50 transition">
                                <span class="text-sm font-semibold text-slate-700">
                                    {{ Carbon\Carbon::parse($revenue->month . '-01')->format('F Y') }}
                                </span>
                                <span class="text-sm font-black text-emerald-700">
                                    {{ number_format($revenue->total, 2, ',', '.') }} MT
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Inadimplência Card -->
                <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-5 shadow-sm">
                    <div class="flex items-center gap-2 text-amber-900 font-bold text-base mb-2">
                        <i class="fas fa-exclamation-triangle text-amber-600"></i> Gestão de Inadimplência
                    </div>
                    <p class="text-xs text-amber-800 mb-4 leading-relaxed">
                        Existem {{ $overdueCount }} mensalidades pendentes no valor total de 
                        <strong>{{ number_format($overdueTotal, 2, ',', '.') }} MT</strong>.
                    </p>
                    <a href="{{ route('reports.financial.defaulters') }}" class="block w-full text-center rounded-lg bg-amber-600 py-2 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition">
                        Ver Lista de Alunos Devedores
                    </a>
                </div>
            </div>

        </div>

    </div>
@endsection