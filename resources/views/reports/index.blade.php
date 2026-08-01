@extends('layouts.app')

@section('title', 'Central de Relatórios & Analytics')
@section('page-title', 'Central de Relatórios')
@section('page-title-icon', 'fas fa-chart-pie')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Relatórios</li>
@endsection

@section('content')
    <div class="space-y-6 bg-[#F4F6FA] -m-4 p-6 rounded-3xl min-h-screen">

        <!-- Top Header Banner MOPHY Style -->
        <div class="rounded-3xl bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 p-6 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 backdrop-blur-md px-3 py-1 text-xs font-bold text-amber-300 border border-white/20">
                    <i class="fas fa-file-invoice"></i> Central MOPHY Analytics
                </span>
                <h2 class="mt-2 text-2xl font-black font-heading text-white">Hub de Relatórios Estratégicos</h2>
                <p class="text-xs text-emerald-100 max-w-lg mt-0.5">
                    Aceda a balanços financeiros, pautas académicas, relatórios de assiduidade e ferramentas de exportação em tempo real.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.export.students') }}" class="inline-flex items-center gap-2 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-md px-4 py-2 text-xs font-bold text-white border border-white/30 transition">
                    <i class="fas fa-download"></i> Alunos (CSV)
                </a>
                <a href="{{ route('reports.export.payments') }}" class="inline-flex items-center gap-2 rounded-full bg-white text-emerald-800 hover:bg-emerald-50 px-4 py-2 text-xs font-black shadow-md transition">
                    <i class="fas fa-file-excel text-emerald-600"></i> Pagamentos (CSV)
                </a>
            </div>
        </div>

        <!-- MOPHY Stat Summary Cards Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex items-center gap-4 hover:shadow-md transition">
                <div class="rounded-2xl bg-emerald-50 p-3.5 text-emerald-600 text-xl shrink-0">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Alunos Ativos</span>
                    <p class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($stats['total_students']) }}</p>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex items-center gap-4 hover:shadow-md transition">
                <div class="rounded-2xl bg-blue-50 p-3.5 text-blue-600 text-xl shrink-0">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Professores</span>
                    <p class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($stats['total_teachers']) }}</p>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex items-center gap-4 hover:shadow-md transition">
                <div class="rounded-2xl bg-purple-50 p-3.5 text-purple-600 text-xl shrink-0">
                    <i class="fas fa-school"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Turmas Ativas</span>
                    <p class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($stats['total_classes']) }}</p>
                </div>
            </div>

            <div class="rounded-2xl bg-gradient-to-br from-emerald-800 to-teal-900 p-5 text-white shadow-lg flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-200">Receita Mensal</span>
                    <p class="text-xl font-black text-white mt-0.5">{{ number_format($stats['monthly_revenue'], 0, ',', '.') }} MT</p>
                </div>
                <span class="rounded-full bg-white/20 p-3 text-white text-base"><i class="fas fa-wallet"></i></span>
            </div>
        </div>

        <!-- MOPHY Report Category Grid -->
        <div class="grid gap-6 md:grid-cols-2">
            
            <!-- Category 1: Académico -->
            <div class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                        <div class="rounded-2xl bg-emerald-100/70 p-3 text-emerald-600 text-lg">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-800 font-heading">Relatórios Académicos</h3>
                            <p class="text-xs text-slate-400">Acompanhamento do rendimento escolar e assiduidade</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('reports.academic') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/80 hover:bg-emerald-50/70 hover:text-emerald-900 transition group">
                            <div class="flex items-center gap-3">
                                <span class="rounded-xl bg-white p-2 text-emerald-600 shadow-sm"><i class="fas fa-list-ul text-xs"></i></span>
                                <span class="text-sm font-bold text-slate-800 group-hover:text-emerald-800">Visão Geral Académica</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-emerald-600"></i>
                        </a>

                        <a href="{{ route('reports.academic.performance') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/80 hover:bg-emerald-50/70 hover:text-emerald-900 transition group">
                            <div class="flex items-center gap-3">
                                <span class="rounded-xl bg-white p-2 text-emerald-600 shadow-sm"><i class="fas fa-chart-line text-xs"></i></span>
                                <span class="text-sm font-bold text-slate-800 group-hover:text-emerald-800">Desempenho por Disciplina & Alunos</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-emerald-600"></i>
                        </a>

                        <a href="{{ route('reports.academic.attendance') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/80 hover:bg-emerald-50/70 hover:text-emerald-900 transition group">
                            <div class="flex items-center gap-3">
                                <span class="rounded-xl bg-white p-2 text-teal-600 shadow-sm"><i class="fas fa-calendar-check text-xs"></i></span>
                                <span class="text-sm font-bold text-slate-800 group-hover:text-emerald-800">Relatório de Assiduidade</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-emerald-600"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Category 2: Financeiro -->
            <div class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                        <div class="rounded-2xl bg-emerald-100/70 p-3 text-emerald-600 text-lg">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-800 font-heading">Relatórios Financeiros</h3>
                            <p class="text-xs text-slate-400">Balanço de mensalidades, cobranças e inadimplência</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('reports.financial') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/80 hover:bg-emerald-50/70 hover:text-emerald-900 transition group">
                            <div class="flex items-center gap-3">
                                <span class="rounded-xl bg-white p-2 text-emerald-600 shadow-sm"><i class="fas fa-receipt text-xs"></i></span>
                                <span class="text-sm font-bold text-slate-800 group-hover:text-emerald-800">Visão Geral Financeira</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-emerald-600"></i>
                        </a>

                        <a href="{{ route('reports.financial.revenue') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/80 hover:bg-emerald-50/70 hover:text-emerald-900 transition group">
                            <div class="flex items-center gap-3">
                                <span class="rounded-xl bg-white p-2 text-emerald-600 shadow-sm"><i class="fas fa-chart-pie text-xs"></i></span>
                                <span class="text-sm font-bold text-slate-800 group-hover:text-emerald-800">Evolução Mensal de Arrecadação</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-emerald-600"></i>
                        </a>

                        <a href="{{ route('reports.financial.defaulters') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-amber-50/80 hover:bg-amber-100/90 hover:text-amber-900 transition group">
                            <div class="flex items-center gap-3">
                                <span class="rounded-xl bg-white p-2 text-amber-600 shadow-sm"><i class="fas fa-exclamation-triangle text-xs"></i></span>
                                <span class="text-sm font-bold text-amber-900">Lista de Alunos Inadimplentes</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-amber-500 group-hover:text-amber-700"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection