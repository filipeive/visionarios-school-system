@extends('layouts.app')

@section('title', 'Dashboard da Secretaria')
@section('page-title', 'Dashboard da Secretaria')
@section('title-icon', 'fas fa-user-tie')

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
    <div class="space-y-6 bg-[#F4F6FA] -m-4 p-6 rounded-3xl min-h-screen" x-data="{ openQuickActions: true }">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total de Alunos</span>
                    <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600 text-sm"><i class="fas fa-user-graduate"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_students']) }}</p>
                    <p class="mt-1 text-xs text-emerald-700 font-semibold">
                        <i class="fas fa-arrow-trend-up"></i> {{ $stats['new_enrollments_month'] }} este mês
                    </p>
                </div>
            </article>

            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Matrículas Pendentes</span>
                    <span class="rounded-xl bg-amber-50 p-2.5 text-amber-600 text-sm"><i class="fas fa-clock"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-amber-700">{{ number_format($stats['pending_enrollments']) }}</p>
                    <p class="mt-1 text-xs text-amber-700 font-semibold">Aguardando revisão</p>
                </div>
            </article>

            <article class="rounded-2xl bg-gradient-to-br from-emerald-800 to-teal-900 p-5 text-white shadow-lg flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-200">Receita Mensal</span>
                    <span class="rounded-full bg-white/20 p-2.5 text-white text-sm"><i class="fas fa-wallet"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-white">{{ number_format($stats['monthly_revenue'], 0, ',', '.') }} MT</p>
                    <p class="mt-1 text-xs text-emerald-200 font-semibold">
                        {{ $stats['todays_payments'] }} pagamentos hoje
                    </p>
                </div>
            </article>

            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Propinas em Atraso</span>
                    <span class="rounded-xl bg-rose-50 p-2.5 text-rose-600 text-sm"><i class="fas fa-exclamation-triangle"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-rose-600">{{ number_format($stats['overdue_payments']) }}</p>
                    <p class="mt-1 text-xs text-rose-600 font-bold truncate">
                        {{ number_format($stats['overdue_amount'], 0, ',', '.') }} MT
                    </p>
                </div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Matriculas Pendentes</h3>
                    <a href="{{ route('enrollments.index') }}?status=pending"
                        class="text-sm font-medium text-sky-700 hover:text-sky-800">Ver todas</a>
                </header>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Aluno</th>
                                <th class="px-4 py-3 text-left font-semibold">Classe</th>
                                <th class="px-4 py-3 text-left font-semibold">Data</th>
                                <th class="px-4 py-3 text-right font-semibold">Acao</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pendingEnrollments as $enrollment)
                                <tr>
                                    <td class="px-4 py-3 text-slate-800">{{ $enrollment->student->full_name }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $enrollment->class->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $enrollment->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('enrollments.show', $enrollment) }}"
                                            class="inline-flex rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-500">Nenhuma matricula pendente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Pagamentos em Atraso</h3>
                    <a href="{{ route('payments.index') }}?status=overdue"
                        class="text-sm font-medium text-rose-700 hover:text-rose-800">Ver todos</a>
                </header>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Aluno</th>
                                <th class="px-4 py-3 text-left font-semibold">Vencimento</th>
                                <th class="px-4 py-3 text-left font-semibold">Valor</th>
                                <th class="px-4 py-3 text-right font-semibold">Acao</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($overduePayments as $payment)
                                <tr>
                                    <td class="px-4 py-3 text-slate-800">{{ $payment->student->full_name }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $payment->due_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 font-semibold text-rose-700">{{ number_format($payment->amount, 2, ',', '.') }} MT</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('payments.show', $payment) }}"
                                            class="inline-flex rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                                            Abrir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-500">Nenhum pagamento em atraso.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Ultimos Pagamentos Recebidos</h3>
                    <a href="{{ route('payments.index') }}"
                        class="text-sm font-medium text-slate-700 hover:text-slate-900">Ver historico</a>
                </header>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Aluno</th>
                                <th class="px-4 py-3 text-left font-semibold">Data</th>
                                <th class="px-4 py-3 text-left font-semibold">Metodo</th>
                                <th class="px-4 py-3 text-left font-semibold">Valor</th>
                                <th class="px-4 py-3 text-right font-semibold">Acao</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentPayments as $payment)
                                <tr>
                                    <td class="px-4 py-3 text-slate-800">{{ $payment->student->full_name }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $payment->payment_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ ucfirst($payment->payment_method) }}</td>
                                    <td class="px-4 py-3 font-semibold text-emerald-700">{{ number_format($payment->amount, 2, ',', '.') }} MT</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('payments.show', $payment) }}"
                                            class="inline-flex rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">Nenhum pagamento registrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-200 px-5 py-4">
                    <button type="button" @click="openQuickActions = !openQuickActions"
                        class="flex w-full items-center justify-between text-left text-sm font-semibold text-slate-900">
                        <span>Acoes Rapidas</span>
                        <i class="fas fa-chevron-down text-xs transition" :class="{ 'rotate-180': openQuickActions }"></i>
                    </button>
                </header>
                <div class="space-y-2 p-4" x-show="openQuickActions" x-transition>
                    <a href="{{ route('students.create') }}"
                        class="block rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-800">
                        <i class="fas fa-user-plus mr-2"></i>Novo Aluno
                    </a>
                    <a href="{{ route('payments.create') }}"
                        class="block rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-800">
                        <i class="fas fa-money-bill-wave mr-2"></i>Receber Pagamento
                    </a>
                    <a href="{{ route('enrollments.create') }}"
                        class="block rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-800">
                        <i class="fas fa-id-card mr-2"></i>Nova Matricula
                    </a>
                    <a href="{{ route('communications.create') }}"
                        class="block rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800">
                        <i class="fas fa-bullhorn mr-2"></i>Enviar Comunicado
                    </a>
                </div>
            </article>
        </section>
    </div>
@endsection
