@extends('layouts.app')

@section('title', 'Relatório de Receitas')
@section('page-title', 'Relatório de Receitas')
@section('page-title-icon', 'fas fa-hand-holding-usd')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.financial') }}">Financeiro</a></li>
    <li class="breadcrumb-item active">Receitas</li>
@endsection

@section('page-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('reports.export.payments') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-700 px-4 py-2 text-xs font-bold text-white shadow-md hover:bg-emerald-800 transition">
            <i class="fas fa-file-excel"></i>
            Exportar Receitas (CSV)
        </a>
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition">
            <i class="fas fa-print"></i>
            Imprimir
        </button>
    </div>
@endsection

@section('content')
    <div class="space-y-6 bg-[#F4F6FA] -m-4 p-6 rounded-3xl min-h-screen">

        <!-- Filter Card MOPHY Style -->
        <div class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
            <form action="{{ route('reports.financial.revenue') }}" method="GET" class="grid gap-4 md:grid-cols-3 items-end">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Data De</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-2 text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Data Até</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-2 text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <button type="submit" class="w-full rounded-2xl bg-emerald-700 py-2.5 text-xs font-extrabold text-white shadow-md hover:bg-emerald-800 transition">
                        <i class="fas fa-filter me-1"></i> Filtrar Período
                    </button>
                </div>
            </form>
        </div>

        <!-- Total Revenue Highlight MOPHY Style -->
        <div class="rounded-3xl bg-gradient-to-br from-emerald-800 to-teal-900 p-7 text-white shadow-xl flex items-center justify-between">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-200">Total Arrecadado no Período</span>
                <p class="text-3xl font-black text-white mt-1">{{ number_format($totalRevenue, 2, ',', '.') }} <span class="text-sm font-normal text-emerald-200">MT</span></p>
            </div>
            <div class="rounded-2xl bg-white/20 p-4 text-white text-2xl shadow-inner">
                <i class="fas fa-sack-dollar"></i>
            </div>
        </div>

        <!-- Table Card MOPHY Style -->
        <div class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl bg-emerald-100/70 p-3 text-emerald-600 text-lg">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800 font-heading">Detalhamento de Transações</h3>
                        <p class="text-xs text-slate-400">Histórico detalhado de liquidações</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Aluno</th>
                            <th class="px-4 py-3 text-left">Data Liquidação</th>
                            <th class="px-4 py-3 text-left">Método</th>
                            <th class="px-4 py-3 text-left">Nº Referência</th>
                            <th class="px-4 py-3 text-right">Valor Arrecadado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 font-bold text-slate-900">
                                    {{ $payment->student ? ($payment->student->first_name . ' ' . $payment->student->last_name) : 'N/A' }}
                                </td>
                                <td class="px-4 py-3.5 text-slate-600 font-medium">
                                    {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-block rounded-full bg-slate-100 px-3 py-0.5 text-[11px] font-bold text-slate-700 uppercase">
                                        {{ $payment->payment_method ?? 'M-Pesa' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-slate-500 font-mono text-xs">{{ $payment->reference_number }}</td>
                                <td class="px-4 py-3.5 text-right font-black text-emerald-700">
                                    {{ number_format($payment->amount, 2, ',', '.') }} MT
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">Nenhum pagamento registado neste período.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        </div>

    </div>
@endsection