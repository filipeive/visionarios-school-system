@extends('layouts.app')

@section('title', 'Relatório de Frequência')
@section('page-title', 'Relatório de Frequência')
@section('page-title-icon', 'fas fa-calendar-check')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.academic') }}">Académico</a></li>
    <li class="breadcrumb-item active">Frequência</li>
@endsection

@section('page-actions')
    <div class="flex items-center gap-2">
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
            <form action="{{ route('reports.academic.attendance') }}" method="GET" class="grid gap-4 md:grid-cols-4 items-end">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Turma</label>
                    <select name="class_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Todas as Turmas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
                        <i class="fas fa-filter me-1"></i> Filtrar Registos
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Card MOPHY Style -->
        <div class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl bg-teal-100/70 p-3 text-teal-600 text-lg">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800 font-heading">Histórico de Assiduidade</h3>
                        <p class="text-xs text-slate-400">Registos diários de presença e faltas</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Aluno</th>
                            <th class="px-4 py-3 text-left">Turma</th>
                            <th class="px-4 py-3 text-left">Data</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-left">Observação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 font-bold text-slate-900">
                                    {{ $attendance->student ? ($attendance->student->first_name . ' ' . $attendance->student->last_name) : 'N/A' }}
                                </td>
                                <td class="px-4 py-3.5 text-slate-600 font-medium">{{ $attendance->class ? $attendance->class->name : 'N/A' }}</td>
                                <td class="px-4 py-3.5 text-slate-700 font-semibold">
                                    {{ $attendance->attendance_date ? $attendance->attendance_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-extrabold
                                        {{ $attendance->status === 'present' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $attendance->status === 'late' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $attendance->status === 'excused' ? 'bg-sky-100 text-sky-800' : '' }}
                                        {{ $attendance->status === 'absent' ? 'bg-rose-100 text-rose-800' : '' }}">
                                        <i class="fas {{ $attendance->status === 'present' ? 'fa-check' : ($attendance->status === 'absent' ? 'fa-xmark' : 'fa-clock') }}"></i>
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-slate-500">{{ $attendance->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">Nenhum registo de assiduidade encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $attendances->links() }}
            </div>
        </div>

    </div>
@endsection