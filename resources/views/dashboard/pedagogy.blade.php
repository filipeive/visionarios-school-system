@extends('layouts.app')

@section('title', 'Dashboard Pedagógico')
@section('page-title', 'Dashboard Pedagógico')
@section('title-icon', 'fas fa-graduation-cap')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('page-actions')
    <button type="button"
        class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-800"
        onclick="window.location.reload()">
        <i class="fas fa-sync-alt"></i>
        Atualizar
    </button>
@endsection

@section('content')
    <div class="space-y-6">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total de Alunos</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($stats['total_students']) }}</p>
                <p class="mt-2 text-sm text-slate-600">{{ $stats['total_classes'] }} turmas</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Professores Ativos</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($stats['total_teachers']) }}</p>
                <p class="mt-2 text-sm text-slate-600">{{ $stats['total_subjects'] }} disciplinas</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Presenca Media</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $stats['average_attendance'] }}%</p>
                <p class="mt-2 text-sm text-slate-600">Este mes</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Media Global</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ $stats['class_performance_avg'] }}</p>
                <p class="mt-2 text-sm text-slate-600">Desempenho academico</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Desempenho por Turma</h3>
                </header>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Turma</th>
                                <th class="px-4 py-3 text-left font-semibold">Professor</th>
                                <th class="px-4 py-3 text-left font-semibold">Alunos</th>
                                <th class="px-4 py-3 text-left font-semibold">Media</th>
                                <th class="px-4 py-3 text-left font-semibold">Progresso</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($classPerformance as $perf)
                                <tr>
                                    <td class="px-4 py-3 text-slate-800">{{ $perf['class_name'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $perf['teacher_name'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $perf['total_students'] }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $perf['average_grade'] }}</td>
                                    <td class="px-4 py-3">
                                        <div class="h-2 w-40 rounded-full bg-slate-100">
                                            <div class="h-2 rounded-full bg-sky-700"
                                                style="width: {{ ($perf['average_grade'] / 20) * 100 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">Sem dados de desempenho por turma.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h3 class="text-sm font-semibold text-slate-900">Proximas Avaliacoes</h3>
                        <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">{{ $stats['upcoming_exams'] }}</span>
                    </header>
                    <ul class="divide-y divide-slate-100">
                        @forelse($upcomingExams as $exam)
                            <li class="px-5 py-3">
                                <p class="text-sm font-semibold text-slate-900">{{ $exam->title }}</p>
                                <p class="text-xs text-slate-500">{{ $exam->class->name ?? 'N/A' }} • {{ $exam->event_date?->format('d/m/Y') ?? 'N/A' }}</p>
                            </li>
                        @empty
                            <li class="px-5 py-5 text-center text-sm text-slate-500">Nenhuma avaliacao agendada.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Lancamentos Pendentes</p>
                    <p class="mt-2 text-3xl font-bold text-amber-700">{{ $stats['pending_grades'] }}</p>
                    <p class="mt-2 text-sm text-slate-600">Notas aguardando lancamento pelos professores.</p>
                    <a href="{{ route('grades.index') }}"
                        class="mt-4 inline-flex items-center rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">
                        Ver detalhes
                    </a>
                </div>
            </article>
        </section>
    </div>
@endsection
