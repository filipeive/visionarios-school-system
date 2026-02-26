@extends('layouts.app')

@section('title', 'Dashboard Administrativo')
@section('page-title', 'Dashboard Administrativo')
@section('title-icon', 'fas fa-tachometer-alt')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('page-actions')
    <div class="relative" x-data="{ open: false }">
        <div class="flex items-center gap-2">
            <button id="refresh-dashboard-btn" type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-800"
                onclick="refreshDashboard()">
                <i class="fas fa-sync-alt"></i>
                Atualizar
            </button>
            <button type="button" @click="open = !open" @click.outside="open = false"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">
                <i class="fas fa-ellipsis-vertical"></i>
            </button>
        </div>
        <div x-show="open" x-transition
            class="absolute right-0 z-20 mt-2 w-52 rounded-lg border border-slate-200 bg-white p-1 shadow-lg">
            <button type="button" onclick="window.print()"
                class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">
                <i class="fas fa-print text-slate-500"></i>
                Imprimir
            </button>
            <a href="{{ route('reports.index') }}"
                class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm text-slate-700 hover:bg-slate-100">
                <i class="fas fa-file-export text-slate-500"></i>
                Ir para Relatorios
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="admin-dashboard space-y-6">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total de Alunos</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($stats['total_students']) }}</p>
                <p class="mt-2 text-sm text-emerald-700">
                    <i class="fas fa-arrow-up"></i> +{{ $stats['new_students_this_month'] }} este mes
                </p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Professores Ativos</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($stats['total_teachers']) }}</p>
                <p class="mt-2 text-sm text-slate-600">
                    <i class="fas fa-users"></i> {{ $stats['total_classes'] }} turmas
                </p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Receita Mensal</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format($stats['monthly_revenue'], 0, ',', '.') }} MT</p>
                <p class="mt-2 text-sm {{ $stats['revenue_change'] >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                    <i class="fas fa-{{ $stats['revenue_change'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs($stats['revenue_change']) }}% vs mes anterior
                </p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pagamentos em Atraso</p>
                <p class="mt-2 text-3xl font-bold text-rose-700">{{ number_format($stats['overdue_payments']) }}</p>
                <p class="mt-2 text-sm text-rose-700">
                    <i class="fas fa-clock"></i> {{ number_format($stats['overdue_amount'], 0, ',', '.') }} MT
                </p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Receitas dos Ultimos 6 Meses</h3>
                </header>
                <div class="p-5">
                    <div class="h-[320px]">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Distribuicao de Alunos por Classe</h3>
                </header>
                <div class="p-5">
                    <div class="h-[320px]">
                        <canvas id="studentsChart"></canvas>
                    </div>
                </div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Proximos Eventos</h3>
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-3 py-2 text-xs font-semibold text-white hover:bg-sky-800">
                        <i class="fas fa-calendar"></i> Ver Agenda
                    </a>
                </header>
                <div class="space-y-3 p-5">
                    @forelse($upcomingEvents as $event)
                        <div class="flex items-center justify-between rounded-lg border border-slate-200 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $event->title }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $event->event_date->format('d/m/Y H:i') }} • {{ $event->location ?? 'Escola' }}
                                </p>
                            </div>
                            <span
                                class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $event->type === 'exam'
                                    ? 'bg-rose-100 text-rose-700'
                                    : ($event->type === 'holiday'
                                        ? 'bg-amber-100 text-amber-700'
                                        : 'bg-sky-100 text-sky-700') }}">
                                {{ ucfirst($event->type) }}
                            </span>
                        </div>
                    @empty
                        <p class="rounded-lg bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">Nenhum evento programado.</p>
                    @endforelse
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white shadow-sm" x-data="{ showActions: true }">
                <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <button type="button" @click="showActions = !showActions"
                        class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                        Acoes Necessarias
                        @if ($stats['pending_actions'] > 0)
                            <span class="rounded-full bg-rose-100 px-2 py-0.5 text-xs text-rose-700">{{ $stats['pending_actions'] }}</span>
                        @endif
                        <i class="fas fa-chevron-down text-xs transition" :class="{ 'rotate-180': showActions }"></i>
                    </button>
                </header>
                <div class="space-y-3 p-5" x-show="showActions" x-transition>
                    @if ($stats['overdue_payments'] > 0)
                        <div class="rounded-lg border border-rose-200 bg-rose-50 p-4">
                            <p class="text-sm font-semibold text-rose-800">{{ $stats['overdue_payments'] }} pagamentos em atraso</p>
                            <p class="mt-1 text-xs text-rose-700">Valor: {{ number_format($stats['overdue_amount'], 0, ',', '.') }} MT</p>
                            <a href="{{ route('payments.index') }}?status=overdue"
                                class="mt-3 inline-flex rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-rose-700 ring-1 ring-rose-300 hover:bg-rose-100">
                                Resolver
                            </a>
                        </div>
                    @endif
                    @if ($stats['pending_enrollments'] > 0)
                        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                            <p class="text-sm font-semibold text-amber-800">{{ $stats['pending_enrollments'] }} matriculas pendentes</p>
                            <p class="mt-1 text-xs text-amber-700">Aguardando aprovacao da secretaria.</p>
                            <a href="{{ route('enrollments.index') }}?status=pending"
                                class="mt-3 inline-flex rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-amber-700 ring-1 ring-amber-300 hover:bg-amber-100">
                                Revisar
                            </a>
                        </div>
                    @endif
                    @if ($stats['pending_leave_requests'] > 0)
                        <div class="rounded-lg border border-sky-200 bg-sky-50 p-4">
                            <p class="text-sm font-semibold text-sky-800">{{ $stats['pending_leave_requests'] }} pedidos de licenca</p>
                            <p class="mt-1 text-xs text-sky-700">Necessitam de analise administrativa.</p>
                            <a href="{{ route('staff-leave-requests.index') }}"
                                class="mt-3 inline-flex rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-sky-700 ring-1 ring-sky-300 hover:bg-sky-100">
                                Analisar
                            </a>
                        </div>
                    @endif
                    @if ($stats['pending_actions'] === 0)
                        <p class="rounded-lg bg-emerald-50 px-4 py-6 text-center text-sm text-emerald-700">Nenhuma acao critica no momento.</p>
                    @endif

                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <a href="{{ route('students.create') }}"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-center text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Novo Aluno
                        </a>
                        <a href="{{ route('payments.create') }}"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-center text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Receber Pagamento
                        </a>
                        <a href="{{ route('reports.index') }}"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-center text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Relatorios
                        </a>
                        <a href="{{ route('communications.create') }}"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-center text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Comunicado
                        </a>
                    </div>
                </div>
            </article>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">Ultimas Atividades</h3>
                <a href="{{ route('admin.audit.index') }}"
                    class="text-xs font-semibold text-sky-700 hover:text-sky-800">Ver log completo</a>
            </header>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Tipo</th>
                            <th class="px-4 py-3 text-left font-semibold">Descricao</th>
                            <th class="px-4 py-3 text-left font-semibold">Usuario</th>
                            <th class="px-4 py-3 text-left font-semibold">Data/Hora</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentActivities as $activity)
                            <tr>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold {{ $activity->type === 'payment'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : ($activity->type === 'enrollment'
                                                ? 'bg-sky-100 text-sky-700'
                                                : ($activity->type === 'user'
                                                    ? 'bg-indigo-100 text-indigo-700'
                                                    : 'bg-slate-100 text-slate-700')) }}">
                                        <i class="fas fa-{{ $activity->icon }}"></i>
                                        {{ ucfirst($activity->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $activity->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $activity->description }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-700 text-[10px] font-bold text-white">
                                            {{ substr($activity->user_name, 0, 1) }}
                                        </div>
                                        <span class="text-slate-700">{{ $activity->user_name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-500">Nenhuma atividade recente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        [data-bs-theme="dark"] .admin-dashboard .bg-white {
            background-color: var(--card-bg) !important;
        }

        [data-bs-theme="dark"] .admin-dashboard .bg-slate-50 {
            background-color: rgba(148, 163, 184, 0.08) !important;
        }

        [data-bs-theme="dark"] .admin-dashboard .border-slate-100,
        [data-bs-theme="dark"] .admin-dashboard .border-slate-200,
        [data-bs-theme="dark"] .admin-dashboard .border-slate-300 {
            border-color: var(--border-color) !important;
        }

        [data-bs-theme="dark"] .admin-dashboard .text-slate-900 {
            color: var(--text-primary) !important;
        }

        [data-bs-theme="dark"] .admin-dashboard .text-slate-800,
        [data-bs-theme="dark"] .admin-dashboard .text-slate-700 {
            color: var(--text-secondary) !important;
        }

        [data-bs-theme="dark"] .admin-dashboard .text-slate-600,
        [data-bs-theme="dark"] .admin-dashboard .text-slate-500 {
            color: var(--text-muted) !important;
        }

        [data-bs-theme="dark"] .admin-dashboard .hover\:bg-slate-50:hover,
        [data-bs-theme="dark"] .admin-dashboard .hover\:bg-slate-100:hover {
            background-color: rgba(148, 163, 184, 0.12) !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let revenueChartInstance = null;
        let studentsChartInstance = null;

        const chartData = {
            revenue: @json($revenueData),
            students: @json($studentsDistribution)
        };

        function initializeCharts() {
            if (revenueChartInstance) revenueChartInstance.destroy();
            if (studentsChartInstance) studentsChartInstance.destroy();

            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx && chartData.revenue) {
                revenueChartInstance = new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: chartData.revenue.months,
                        datasets: [{
                            label: 'Receitas (MT)',
                            data: chartData.revenue.amounts,
                            borderColor: '#0f4c81',
                            backgroundColor: 'rgba(15, 76, 129, 0.08)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Receita: ${context.parsed.y.toLocaleString('pt-MZ')} MT`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            const studentsCtx = document.getElementById('studentsChart');
            if (studentsCtx && chartData.students) {
                studentsChartInstance = new Chart(studentsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: chartData.students.labels,
                        datasets: [{
                            data: chartData.students.data,
                            backgroundColor: ['#0f4c81', '#0ea5e9', '#14b8a6', '#f59e0b', '#ef4444', '#8b5cf6'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        cutout: '60%'
                    }
                });
            }
        }

        function refreshDashboard() {
            const btn = document.getElementById('refresh-dashboard-btn');
            if (!btn) return;

            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Atualizando...';
            btn.disabled = true;

            fetch('{{ route('api.dashboard.counters') }}')
                .then(response => response.json())
                .then(() => window.location.reload())
                .catch(() => {
                    btn.innerHTML = original;
                    btn.disabled = false;
                    window.VisionariosSchool?.showToast?.('Erro ao atualizar dashboard', 'error');
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            setInterval(() => {
                fetch('{{ route('api.dashboard.counters') }}').catch(() => {});
            }, 120000);
        });

        window.addEventListener('resize', initializeCharts);
    </script>
@endpush
