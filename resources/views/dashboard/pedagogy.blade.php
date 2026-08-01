@extends('layouts.app')

@section('title', 'Dashboard Pedagógico')
@section('page-title', 'Dashboard Pedagógico')
@section('page-title-icon', 'fas fa-graduation-cap')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('page-actions')
    <button type="button"
        class="inline-flex items-center gap-2 rounded-full bg-emerald-700 px-5 py-2 text-xs font-bold text-white shadow-md hover:bg-emerald-800 transition"
        onclick="window.location.reload()">
        <i class="fas fa-sync-alt"></i>
        Atualizar
    </button>
@endsection

@section('content')
    <div class="space-y-6 bg-[#F4F6FA] -m-4 p-6 rounded-3xl min-h-screen">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total de Alunos</span>
                    <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600 text-sm"><i class="fas fa-user-graduate"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_students']) }}</p>
                    <p class="mt-1 text-xs text-slate-500 font-semibold">{{ $stats['total_classes'] }} turmas ativas</p>
                </div>
            </article>

            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Professores Ativos</span>
                    <span class="rounded-xl bg-blue-50 p-2.5 text-blue-600 text-sm"><i class="fas fa-chalkboard-user"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_teachers']) }}</p>
                    <p class="mt-1 text-xs text-blue-600 font-semibold">{{ $stats['total_subjects'] }} disciplinas</p>
                </div>
            </article>

            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Presença Média</span>
                    <p class="mt-2 text-2xl font-black text-slate-800">{{ $stats['average_attendance'] }}%</p>
                    <p class="mt-1 text-[11px] text-teal-600 font-semibold">Este Mês</p>
                </div>
                <div class="relative w-14 h-14 flex items-center justify-center shrink-0">
                    <svg class="w-14 h-14 transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="text-teal-500" stroke-linecap="round" stroke-dasharray="{{ $stats['average_attendance'] }}, 100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <span class="absolute text-[10px] font-extrabold text-teal-700"><i class="fas fa-check"></i></span>
                </div>
            </article>

            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Média Global</span>
                    <span class="rounded-xl bg-purple-50 p-2.5 text-purple-600 text-sm"><i class="fas fa-award"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-purple-700">{{ $stats['class_performance_avg'] }} <span class="text-xs text-slate-400 font-normal">/ 20</span></p>
                    <p class="mt-1 text-xs text-purple-600 font-semibold">Desempenho Académico</p>
                </div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="xl:col-span-2 rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                    <h3 class="text-base font-extrabold text-slate-800 font-heading">Desempenho por Turma</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">Turma</th>
                                <th class="px-4 py-3 text-left">Professor</th>
                                <th class="px-4 py-3 text-left">Alunos</th>
                                <th class="px-4 py-3 text-left">Média</th>
                                <th class="px-4 py-3 text-left">Progresso</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($classPerformance as $class)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3.5 font-extrabold text-slate-900">{{ $class['name'] }}</td>
                                    <td class="px-4 py-3.5 text-slate-600 font-medium">{{ $class['teacher_name'] }}</td>
                                    <td class="px-4 py-3.5 text-slate-700 font-bold">{{ $class['student_count'] }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="inline-block rounded-full bg-emerald-100 px-3 py-0.5 text-xs font-black text-emerald-800">
                                            {{ number_format($class['avg_grade'], 1) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="w-24 bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ min(100, ($class['avg_grade'] / 20) * 100) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                    <h3 class="text-base font-extrabold text-slate-800 font-heading">Próximos Exames</h3>
                </div>
                <div class="space-y-3">
                    @forelse($upcomingExams as $exam)
                        <div class="p-3.5 rounded-2xl bg-slate-50/70 border border-slate-100">
                            <p class="text-sm font-bold text-slate-900">{{ $exam->title }}</p>
                            <p class="text-xs text-slate-400 mt-1">
                                <i class="fas fa-calendar me-1"></i> {{ $exam->event_date->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    @empty
                        <p class="p-5 text-center text-sm text-slate-400">Nenhum exame agendado.</p>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
@endsection
