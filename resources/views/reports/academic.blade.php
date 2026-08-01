@extends('layouts.app')

@section('title', 'Relatórios Académicos')
@section('page-title', 'Visão Geral Académica')
@section('page-title-icon', 'fas fa-graduation-cap')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item active">Académico</li>
@endsection

@section('page-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('reports.export.students') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-700 px-4 py-2 text-xs font-bold text-white shadow-md hover:bg-emerald-800 transition">
            <i class="fas fa-file-export"></i>
            Exportar Alunos (CSV)
        </a>
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition">
            <i class="fas fa-print"></i>
            Imprimir
        </button>
    </div>
@endsection

@section('content')
    <div class="space-y-6 bg-[#F4F6FA] -m-4 p-6 rounded-3xl min-h-screen">

        <!-- Executive Summary Diagnóstico MOPHY Style -->
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border-0 bg-emerald-50/90 p-4 shadow-[0_8px_20px_rgba(0,0,0,0.03)]">
                <div class="flex items-center gap-2 text-emerald-800 font-extrabold text-xs mb-1">
                    <i class="fas fa-chart-line"></i> O que aconteceu?
                </div>
                <p class="text-xs text-emerald-950 font-medium leading-relaxed">
                    {{ $academicSummary['what_happened'] }}
                </p>
            </div>

            <div class="rounded-2xl border-0 bg-sky-50/90 p-4 shadow-[0_8px_20px_rgba(0,0,0,0.03)]">
                <div class="flex items-center gap-2 text-sky-800 font-extrabold text-xs mb-1">
                    <i class="fas fa-arrow-trend-up"></i> Qual a tendência?
                </div>
                <p class="text-xs text-sky-950 font-medium leading-relaxed">
                    {{ $academicSummary['trend'] }}
                </p>
            </div>

            <div class="rounded-2xl border-0 bg-amber-50/90 p-4 shadow-[0_8px_20px_rgba(0,0,0,0.03)]">
                <div class="flex items-center gap-2 text-amber-800 font-extrabold text-xs mb-1">
                    <i class="fas fa-triangle-exclamation"></i> Recomendações
                </div>
                <p class="text-xs text-amber-950 font-medium leading-relaxed">
                    {{ $academicSummary['attention'] }}
                </p>
            </div>
        </div>

        <!-- Table: Class Summary MOPHY Card -->
        <div class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl bg-emerald-100/70 p-3 text-emerald-600 text-lg">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800 font-heading">Resumo das Turmas & Capacidade</h3>
                        <p class="text-xs text-slate-400">Lotação e média por turma</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Turma</th>
                            <th class="px-4 py-3 text-left">Nível</th>
                            <th class="px-4 py-3 text-left">Professor Titular</th>
                            <th class="px-4 py-3 text-left">Alunos Ativos</th>
                            <th class="px-4 py-3 text-left">Média Turma</th>
                            <th class="px-4 py-3 text-left">Capacidade</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($classes as $class)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 font-extrabold text-slate-900">{{ $class->name }}</td>
                                <td class="px-4 py-3.5 text-slate-600 font-medium">{{ $class->grade_level_name }}</td>
                                <td class="px-4 py-3.5 text-slate-700">
                                    {{ $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'N/A' }}
                                </td>
                                <td class="px-4 py-3.5 font-bold text-slate-800">{{ $class->active_students_count }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-block rounded-full bg-emerald-100 px-3 py-0.5 text-xs font-black text-emerald-800">
                                        {{ number_format($class->average_grade, 1) }} / 20
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    @php $percent = $class->max_students > 0 ? ($class->active_students_count / $class->max_students) * 100 : 0; @endphp
                                    <div class="w-32 bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-full rounded-full {{ $percent > 90 ? 'bg-rose-500' : ($percent > 70 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-400 mt-1 block">{{ round($percent) }}% ({{ $class->active_students_count }}/{{ $class->max_students }})</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection