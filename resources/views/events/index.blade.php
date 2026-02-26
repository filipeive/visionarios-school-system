@extends('layouts.app')

@section('title', 'Gestao de Eventos')
@section('page-title', 'Gestao de Eventos')
@section('title-icon', 'fas fa-calendar-alt')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Eventos</li>
@endsection

@section('content')
    @php
        $typeLabels = [
            'meeting' => 'Reuniao',
            'celebration' => 'Celebracao',
            'exam' => 'Exame',
            'activity' => 'Atividade',
        ];
        $audienceLabels = [
            'all' => 'Geral',
            'students' => 'Alunos',
            'parents' => 'Pais',
            'teachers' => 'Professores',
        ];
    @endphp

    <div class="events-shell space-y-6">
        <section class="grid gap-4 md:grid-cols-3">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Proximos Eventos</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ \App\Models\Event::upcoming()->count() }}</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Eventos Hoje</p>
                <p class="mt-2 text-3xl font-bold text-sky-700">{{ \App\Models\Event::today()->count() }}</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Esta Semana</p>
                <p class="mt-2 text-3xl font-bold text-indigo-700">{{ \App\Models\Event::thisWeek()->count() }}</p>
            </article>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <form action="{{ route('events.index') }}" method="GET" class="grid w-full gap-3 lg:max-w-4xl lg:grid-cols-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Pesquisar</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500"
                            placeholder="Titulo do evento...">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Tipo</label>
                        <select name="type" class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                            <option value="">Todos</option>
                            @foreach ($typeLabels as $key => $label)
                                <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Publico</label>
                        <select name="audience" class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                            <option value="">Todos</option>
                            @foreach ($audienceLabels as $key => $label)
                                <option value="{{ $key }}" @selected(request('audience') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="rounded-lg bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800">Filtrar</button>
                        @if (request()->anyFilled(['search', 'type', 'audience']))
                            <a href="{{ route('events.index') }}"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Limpar</a>
                        @endif
                    </div>
                </form>

                <div class="flex gap-2">
                    <a href="{{ route('events.calendar') }}"
                        class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Calendario</a>
                    @can('create_events')
                        <a href="{{ route('events.create') }}"
                            class="rounded-lg bg-sky-700 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-800">Novo Evento</a>
                    @endcan
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Data</th>
                            <th class="px-4 py-3 text-left font-semibold">Horario</th>
                            <th class="px-4 py-3 text-left font-semibold">Evento</th>
                            <th class="px-4 py-3 text-left font-semibold">Tipo</th>
                            <th class="px-4 py-3 text-left font-semibold">Publico</th>
                            <th class="px-4 py-3 text-left font-semibold">Criado por</th>
                            <th class="px-4 py-3 text-right font-semibold">Acoes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($events as $event)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $event->event_date->format('d/m/Y') }}</p>
                                    <p class="text-xs text-slate-500">{{ $event->event_date->translatedFormat('l') }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-700">{{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $event->title }}</p>
                                    <p class="max-w-[240px] truncate text-xs text-slate-500">{{ $event->description }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $typeClass = $event->type === 'meeting'
                                            ? 'bg-sky-100 text-sky-700'
                                            : ($event->type === 'celebration'
                                                ? 'bg-amber-100 text-amber-700'
                                                : ($event->type === 'exam'
                                                    ? 'bg-rose-100 text-rose-700'
                                                    : 'bg-emerald-100 text-emerald-700'));
                                    @endphp
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $typeClass }}">{{ $typeLabels[$event->type] ?? ucfirst($event->type) }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-700">{{ $audienceLabels[$event->target_audience] ?? $event->target_audience }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $event->createdBy->name ?? 'Sistema' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex gap-1">
                                        <a href="{{ route('events.show', $event) }}"
                                            class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Ver</a>
                                        @can('edit_events')
                                            <a href="{{ route('events.edit', $event) }}"
                                                class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Editar</a>
                                        @endcan
                                        @can('delete_events')
                                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Tem certeza que deseja excluir este evento?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-lg border border-rose-300 px-2 py-1 text-xs text-rose-700 hover:bg-rose-50">Excluir</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">Nenhum evento encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($events->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">{{ $events->links() }}</div>
            @endif
        </section>
    </div>
@endsection

@push('styles')
    <style>
        [data-bs-theme="dark"] .events-shell .bg-white {
            background-color: var(--card-bg) !important;
        }

        [data-bs-theme="dark"] .events-shell .bg-slate-50 {
            background-color: rgba(148, 163, 184, 0.08) !important;
        }

        [data-bs-theme="dark"] .events-shell .border-slate-100,
        [data-bs-theme="dark"] .events-shell .border-slate-200,
        [data-bs-theme="dark"] .events-shell .border-slate-300 {
            border-color: var(--border-color) !important;
        }

        [data-bs-theme="dark"] .events-shell .text-slate-900 {
            color: var(--text-primary) !important;
        }

        [data-bs-theme="dark"] .events-shell .text-slate-700,
        [data-bs-theme="dark"] .events-shell .text-slate-600,
        [data-bs-theme="dark"] .events-shell .text-slate-500 {
            color: var(--text-secondary) !important;
        }
    </style>
@endpush
