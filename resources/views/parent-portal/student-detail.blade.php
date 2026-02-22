@extends('layouts.app')

@section('title', $student->full_name)
@section('page-title', $student->full_name)

@section('content')
    <div class="space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center">
                <div>
                    @if ($student->photo_url)
                        <img src="{{ $student->photo_url }}" alt="{{ $student->full_name }}"
                            class="h-24 w-24 rounded-full object-cover ring-2 ring-slate-200">
                    @else
                        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                            <i class="fas fa-user text-3xl"></i>
                        </div>
                    @endif
                </div>
                <div class="grid flex-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Nome</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $student->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Nascimento</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $student->birthdate?->format('d/m/Y') ?? 'N/A' }} ({{ $student->age }} anos)</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Turma</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $student->currentEnrollment->class->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Numero</p>
                        <p class="font-mono text-sm font-semibold text-slate-900">{{ $student->student_number }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 md:grid-cols-2">
            <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Presencas Recentes</h3>
                </header>
                <div class="divide-y divide-slate-100 p-5">
                    @forelse($student->attendances()->latest()->take(5)->get() as $attendance)
                        <div class="flex items-center justify-between py-2 first:pt-0 last:pb-0">
                            <span class="text-sm text-slate-700">{{ $attendance->attendance_date?->format('d/m/Y') ?? 'N/A' }}</span>
                            @php
                                $statusClass = $attendance->status === 'present'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : ($attendance->status === 'absent' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700');
                            @endphp
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="rounded-lg bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">Nenhum registro de presenca.</p>
                    @endforelse
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Ultimas Notas</h3>
                </header>
                <div class="divide-y divide-slate-100 p-5">
                    @forelse($student->grades()->latest()->take(5)->get() as $grade)
                        <div class="flex items-center justify-between gap-3 py-2 first:pt-0 last:pb-0">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $grade->subject->name ?? 'Disciplina' }}</p>
                                <p class="text-xs text-slate-500">{{ ucfirst($grade->assessment_type) }} - Trimestre {{ $grade->term }}</p>
                            </div>
                            <span class="text-lg font-bold {{ $grade->grade < 10 ? 'text-rose-700' : 'text-emerald-700' }}">{{ $grade->grade }}</span>
                        </div>
                    @empty
                        <p class="rounded-lg bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">Nenhuma nota registrada.</p>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
@endsection
