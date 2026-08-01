@extends('layouts.app')

@section('title', 'Relatório de Inadimplentes')
@section('page-title', 'Relatório de Inadimplentes')
@section('page-title-icon', 'fas fa-user-times')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.financial') }}">Financeiro</a></li>
    <li class="breadcrumb-item active">Inadimplentes</li>
@endsection

@section('page-actions')
    <div class="flex items-center gap-2">
        <button type="button" onclick="showToast('Lembretes de cobrança enviados com sucesso!', 'success')" class="inline-flex items-center gap-2 rounded-full bg-amber-600 px-4 py-2 text-xs font-bold text-white shadow-md hover:bg-amber-700 transition">
            <i class="fas fa-paper-plane"></i>
            Notificar Todos por SMS/Email
        </button>
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition">
            <i class="fas fa-print"></i>
            Imprimir
        </button>
    </div>
@endsection

@section('content')
    <div class="space-y-6 bg-[#F4F6FA] -m-4 p-6 rounded-3xl min-h-screen">

        <!-- Banner Info Alert MOPHY Style -->
        <div class="rounded-2xl border-0 bg-amber-50/90 p-4 shadow-[0_8px_20px_rgba(0,0,0,0.03)] flex items-center gap-3">
            <div class="rounded-xl bg-amber-500 p-2.5 text-white text-base shrink-0">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <p class="text-xs text-amber-950 font-medium leading-relaxed">
                Este relatório compila os alunos matriculados com propinas e mensalidades pendentes para o mês de 
                <strong>{{ now()->format('F Y') }}</strong>.
            </p>
        </div>

        <!-- Table Card MOPHY Style -->
        <div class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl bg-amber-100/70 p-3 text-amber-600 text-lg">
                        <i class="fas fa-users-slash"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800 font-heading">Alunos Inadimplentes</h3>
                        <p class="text-xs text-slate-400">Total de {{ $defaulters->count() }} registos em atraso</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Aluno</th>
                            <th class="px-4 py-3 text-left">Turma</th>
                            <th class="px-4 py-3 text-left">Encarregado de Educação</th>
                            <th class="px-4 py-3 text-left">Contacto</th>
                            <th class="px-4 py-3 text-left">Valor Propinas</th>
                            <th class="px-4 py-3 text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($defaulters as $enrollment)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 font-bold text-slate-900">
                                    {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}
                                    <span class="block text-[11px] font-medium text-slate-400">{{ $enrollment->student->student_number }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-slate-600 font-medium">{{ $enrollment->class->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3.5 text-slate-700 font-semibold">{{ $enrollment->student->parent->first_name ?? 'N/A' }}</td>
                                <td class="px-4 py-3.5 text-slate-600 font-bold">{{ $enrollment->student->parent->phone ?? 'N/A' }}</td>
                                <td class="px-4 py-3.5 font-black text-rose-600">
                                    {{ number_format($enrollment->monthly_fee, 2, ',', '.') }} MT
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <a href="{{ route('students.show', $enrollment->student_id) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-100 text-slate-700 hover:bg-emerald-100 hover:text-emerald-800 transition">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                                    <i class="fas fa-circle-check text-emerald-500 text-3xl mb-2 block"></i>
                                    Parabéns! Não existem alunos inadimplentes neste período.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection