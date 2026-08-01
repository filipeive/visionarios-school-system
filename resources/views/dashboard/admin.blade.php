@extends('layouts.app')

@section('title', 'Dashboard Executivo & Analytics')
@section('page-title', 'Dashboard Executivo & Analytics')
@section('title-icon', 'fas fa-chart-pie')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard ZamEdu</li>
@endsection

@section('page-actions')
    <div class="flex items-center gap-3">
        <button id="refresh-dashboard-btn" type="button"
            class="inline-flex items-center gap-2 rounded-full bg-emerald-700 px-5 py-2.5 text-xs font-bold text-white shadow-md transition hover:bg-emerald-800"
            onclick="location.reload()">
            <i class="fas fa-sync-alt"></i>
            Atualizar
        </button>
        <button type="button" onclick="window.print()"
            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
            <i class="fas fa-print"></i>
            Imprimir
        </button>
    </div>
@endsection

@section('content')
    <div class="admin-dashboard space-y-6 bg-[#F4F6FA] -m-4 p-6 rounded-3xl min-h-screen">

        <!-- Banner de Boas-Vindas Institucional MOPHY Style -->
        <div class="rounded-3xl bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 p-7 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 backdrop-blur-md px-3.5 py-1 text-xs font-bold text-amber-300 border border-white/20">
                        <i class="fas fa-star"></i> Painel
                    </span>
                    <h2 class="mt-3 text-3xl font-extrabold font-heading text-white tracking-tight">
                        {{ setting('school_name', 'ZamEdu') }} — Sistema de Gestão Escolar
                    </h2>
                    <p class="mt-1 text-sm text-emerald-100/90 max-w-xl">
                        Acompanhamento em tempo real da assiduidade, arrecadações, pautas académicas e indicadores de desempenho.
                    </p>
                </div>
                <div class="flex items-center gap-3 bg-white/15 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/20 text-xs font-semibold shadow-inner">
                    <div>
                        <span class="block text-emerald-200 uppercase text-[10px] tracking-wider">Ano Lectivo</span>
                        <span class="text-base font-extrabold text-white">{{ current_school_year() }}</span>
                    </div>
                    <div class="h-8 w-px bg-white/20"></div>
                    <div>
                        <span class="block text-emerald-200 uppercase text-[10px] tracking-wider">Data Atual</span>
                        <span class="text-base font-extrabold text-white">{{ date('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Smart Executive Insights (Alertas Inteligentes MOPHY Style) -->
        @if (!empty($smartInsights))
            <section class="grid gap-4 md:grid-cols-3">
                @foreach ($smartInsights as $insight)
                    <div class="rounded-2xl p-4 shadow-[0_8px_20px_rgba(0,0,0,0.03)] border-0 flex items-start gap-3.5 transition hover:translate-y-[-2px]
                        {{ $insight['type'] === 'success' ? 'bg-emerald-50/90 text-emerald-950' : '' }}
                        {{ $insight['type'] === 'info' ? 'bg-sky-50/90 text-sky-950' : '' }}
                        {{ $insight['type'] === 'warning' ? 'bg-amber-50/90 text-amber-950' : '' }}">
                        <div class="rounded-xl p-2.5 text-lg shrink-0
                            {{ $insight['type'] === 'success' ? 'bg-emerald-500 text-white shadow-sm' : '' }}
                            {{ $insight['type'] === 'info' ? 'bg-sky-500 text-white shadow-sm' : '' }}
                            {{ $insight['type'] === 'warning' ? 'bg-amber-500 text-white shadow-sm' : '' }}">
                            <i class="fas {{ $insight['icon'] }}"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold">{{ $insight['title'] }}</h4>
                            <p class="mt-0.5 text-xs opacity-90 leading-relaxed font-medium">{{ $insight['message'] }}</p>
                        </div>
                    </div>
                @endforeach
            </section>
        @endif

        <!-- MOPHY Style Stat Cards Grid (Compactos, Arredondados, Elegantes) -->
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            
            <!-- Card 1: Receita Mensal (Destaque Principal Gradient MOPHY) -->
            <article class="rounded-2xl bg-gradient-to-br from-emerald-800 to-teal-900 p-5 text-white shadow-lg relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-200">Receita Mensal</span>
                    <span class="rounded-full bg-white/20 p-2 text-white text-xs"><i class="fas fa-wallet"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black tracking-tight text-white">{{ number_format($stats['monthly_revenue'], 0, ',', '.') }} <span class="text-xs font-normal text-emerald-200">MT</span></p>
                    <div class="mt-2 inline-flex items-center gap-1 rounded-full bg-emerald-400/20 px-2.5 py-0.5 text-[11px] font-bold text-emerald-200 border border-emerald-400/30">
                        <i class="fas fa-{{ $stats['revenue_change'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ abs($stats['revenue_change']) }}% este mês
                    </div>
                </div>
            </article>

            <!-- Card 2: Alunos Ativos -->
            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Alunos Ativos</span>
                    <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600 text-sm"><i class="fas fa-user-graduate"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_students']) }}</p>
                    <p class="mt-1 text-xs text-slate-500 font-medium">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1"></span> {{ $stats['total_classes'] }} Turmas ativas
                    </p>
                </div>
            </article>

            <!-- Card 3: Assiduidade Donut SVG (MOPHY Ring Indicator) -->
            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Assiduidade</span>
                    <p class="mt-2 text-2xl font-black text-slate-800">{{ $stats['overall_attendance_rate'] }}%</p>
                    <p class="mt-1 text-[11px] text-teal-600 font-semibold">Presença Global</p>
                </div>
                <!-- Mini Donut Ring SVG -->
                <div class="relative w-14 h-14 flex items-center justify-center shrink-0">
                    <svg class="w-14 h-14 transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="text-teal-500" stroke-linecap="round" stroke-dasharray="{{ $stats['overall_attendance_rate'] }}, 100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <span class="absolute text-[10px] font-extrabold text-teal-700"><i class="fas fa-check"></i></span>
                </div>
            </article>

            <!-- Card 4: Média Académica -->
            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Média Geral</span>
                    <span class="rounded-xl bg-purple-50 p-2.5 text-purple-600 text-sm"><i class="fas fa-award"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-purple-700">{{ $stats['global_grade_avg'] }} <span class="text-xs text-slate-400 font-normal">/ 20</span></p>
                    <span class="mt-1 inline-flex items-center gap-1 rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-bold text-purple-700">
                        <i class="fas fa-star text-amber-500"></i> Bom Desempenho
                    </span>
                </div>
            </article>

            <!-- Card 5: Inadimplência -->
            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Em Atraso</span>
                    <span class="rounded-xl bg-rose-50 p-2.5 text-rose-600 text-sm"><i class="fas fa-exclamation-circle"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-rose-600">{{ number_format($stats['overdue_payments']) }}</p>
                    <p class="mt-1 text-xs text-rose-600 font-bold truncate">
                        {{ number_format($stats['overdue_amount'], 0, ',', '.') }} MT
                    </p>
                </div>
            </article>

            <!-- Card 6: Corpo Docente -->
            <article class="rounded-2xl bg-white p-5 shadow-[0_10px_25px_rgba(0,0,0,0.03)] border-0 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Professores</span>
                    <span class="rounded-xl bg-blue-50 p-2.5 text-blue-600 text-sm"><i class="fas fa-chalkboard-user"></i></span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_teachers']) }}</p>
                    <p class="mt-1 text-xs text-blue-600 font-semibold">
                        <i class="fas fa-graduation-cap"></i> Docentes Ativos
                    </p>
                </div>
            </article>

        </section>

        <!-- Charts Section (Receita 12 Meses & Distribuição de Alunos MOPHY Cards) -->
        <section class="grid gap-6 xl:grid-cols-3">
            
            <!-- Revenue Chart Card -->
            <article class="xl:col-span-2 min-w-0 rounded-3xl bg-white p-4 sm:p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0 overflow-hidden">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-800 font-heading">Evolução da Receita</h3>
                        <p class="text-xs text-slate-400">Histórico de propinas e mensalidades pagas nos últimos 12 meses</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 text-nowrap">12 Meses</span>
                </div>
                <div class="relative w-full min-w-0 h-[240px] sm:h-[300px]">
                    <canvas id="revenueChart"></canvas>
                </div>
            </article>

            <!-- Class Distribution Chart Card -->
            <article class="min-w-0 rounded-3xl bg-white p-4 sm:p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0 overflow-hidden">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-800 font-heading">Alunos por Turma</h3>
                        <p class="text-xs text-slate-400">Distribuição atual de matrículas</p>
                    </div>
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 text-nowrap">Turmas</span>
                </div>
                <div class="relative w-full min-w-0 h-[240px] sm:h-[300px]">
                    <canvas id="studentsChart"></canvas>
                </div>
            </article>

        </section>

        <!-- Rankings Section: Quadro de Honra & Melhores Turmas -->
        <section class="grid gap-6 lg:grid-cols-2">
            
            <!-- Quadro de Honra (Top Alunos) -->
            <article class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="rounded-2xl bg-amber-100/70 p-3 text-amber-600 text-lg">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-800 font-heading">Quadro de Honra — Melhores Alunos</h3>
                            <p class="text-xs text-slate-400">Estudantes com maior média académica global</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($topStudents as $index => $student)
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/70 hover:bg-slate-100/80 transition">
                            <div class="flex items-center gap-3.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full font-black text-xs shadow-sm
                                    {{ $index === 0 ? 'bg-amber-400 text-amber-950 ring-2 ring-amber-300' : '' }}
                                    {{ $index === 1 ? 'bg-slate-300 text-slate-800' : '' }}
                                    {{ $index === 2 ? 'bg-amber-600 text-white' : '' }}
                                    {{ $index > 2 ? 'bg-slate-200 text-slate-600' : '' }}">
                                    {{ $index + 1 }}º
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                    <p class="text-xs text-slate-400 font-medium">Nº {{ $student->student_number }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="inline-block rounded-full bg-emerald-100 px-3.5 py-1 text-xs font-black text-emerald-800">
                                    {{ number_format($student->average_grade, 1) }} / 20
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="p-5 text-center text-sm text-slate-400">Nenhum aluno classificado.</p>
                    @endforelse
                </div>
            </article>

            <!-- Top Turmas por Média -->
            <article class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="rounded-2xl bg-emerald-100/70 p-3 text-emerald-600 text-lg">
                            <i class="fas fa-ranking-star"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-800 font-heading">Ranking de Turmas</h3>
                            <p class="text-xs text-slate-400">Média geral por turma</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($topClasses as $index => $class)
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/70 hover:bg-slate-100/80 transition">
                            <div class="flex items-center gap-3.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-xs font-black text-slate-700">
                                    #{{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $class->name }}</p>
                                    <p class="text-xs text-slate-400 font-medium">{{ $class->current_students }} Alunos inscritos</p>
                                </div>
                            </div>
                            <div>
                                <span class="inline-block rounded-full bg-teal-100 px-3.5 py-1 text-xs font-black text-teal-800">
                                    {{ number_format($class->average_grade, 1) }} / 20
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="p-5 text-center text-sm text-slate-400">Nenhuma turma classificada.</p>
                    @endforelse
                </div>
            </article>

        </section>

        <!-- Ações Necessárias & Próximos Eventos -->
        <section class="grid gap-6 xl:grid-cols-2">
            
            <article class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                    <h3 class="text-base font-extrabold text-slate-800 font-heading">Próximos Eventos</h3>
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-emerald-700 px-3.5 py-1.5 text-xs font-bold text-white hover:bg-emerald-800 transition">
                        <i class="fas fa-calendar-alt"></i> Agenda
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($upcomingEvents as $event)
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50/70 p-3.5">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $event->title }}</p>
                                <p class="text-xs text-slate-400">
                                    <i class="fas fa-clock text-slate-400 me-1"></i> {{ $event->event_date->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                {{ ucfirst($event->type) }}
                            </span>
                        </div>
                    @empty
                        <p class="rounded-2xl bg-slate-50 p-6 text-center text-sm text-slate-400">Nenhum evento programado.</p>
                    @endforelse
                </div>
            </article>

            <article class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border-0">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                    <h3 class="text-base font-extrabold text-slate-800 font-heading">Ações Administrativas</h3>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">{{ $stats['pending_actions'] }} Pendentes</span>
                </div>
                <div class="space-y-3">
                    @if ($stats['overdue_payments'] > 0)
                        <div class="rounded-2xl bg-rose-50/80 p-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-rose-950">{{ $stats['overdue_payments'] }} Mensalidades em Atraso</p>
                                <p class="text-xs text-rose-700">Valor em mora: {{ number_format($stats['overdue_amount'], 2, ',', '.') }} MT</p>
                            </div>
                            <a href="{{ route('payments.index') }}?status=overdue"
                                class="rounded-full bg-rose-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-rose-700 transition">
                                Resolver
                            </a>
                        </div>
                    @endif

                    @if ($stats['pending_enrollments'] > 0)
                        <div class="rounded-2xl bg-amber-50/80 p-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-amber-950">{{ $stats['pending_enrollments'] }} Pré-Matrículas Pendentes</p>
                                <p class="text-xs text-amber-700">Aguardando aprovação da secretaria</p>
                            </div>
                            <a href="{{ route('enrollments.index') }}?status=pending"
                                class="rounded-full bg-amber-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition">
                                Aprovar
                            </a>
                        </div>
                    @endif

                    @if ($stats['pending_actions'] === 0)
                        <div class="rounded-2xl bg-emerald-50 p-6 text-center text-sm font-bold text-emerald-800">
                            <i class="fas fa-circle-check text-xl mb-1 block text-emerald-600"></i>
                            Todas as tarefas administrativas estão concluídas!
                        </div>
                    @endif
                </div>
            </article>

        </section>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Obter cores dinâmicas do ThemeEngine
            const computedStyle = getComputedStyle(document.documentElement);
            const primaryColor = computedStyle.getPropertyValue('--primary').trim() || '#4F46E5';
            const secondaryColor = computedStyle.getPropertyValue('--secondary').trim() || '#06B6D4';
            const accentColor = computedStyle.getPropertyValue('--accent').trim() || '#F59E0B';

            // Chart 1: Revenue 12 Months
            const revenueCtx = document.getElementById('revenueChart')?.getContext('2d');
            if (revenueCtx) {
                const revenueChartData = @json($monthlyRevenueChart ?? []);
                const labels = revenueChartData.map(item => item.month);
                const values = revenueChartData.map(item => item.revenue);

                // Criar gradiente dinâmico com a cor primária da escola
                const gradientFill = revenueCtx.createLinearGradient(0, 0, 0, 300);
                gradientFill.addColorStop(0, primaryColor + '33'); // 20% opacidade
                gradientFill.addColorStop(1, primaryColor + '00'); // 0% opacidade

                new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Receita (MT)',
                            data: values,
                            borderColor: primaryColor,
                            backgroundColor: gradientFill,
                            borderWidth: 3.5,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: accentColor,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(226, 232, 240, 0.5)' },
                                ticks: {
                                    font: { size: window.innerWidth < 640 ? 10 : 11 },
                                    callback: function(value) {
                                        if (window.innerWidth < 640 && value >= 1000) {
                                            return (value / 1000).toLocaleString('pt-MZ') + 'k';
                                        }
                                        return value.toLocaleString('pt-MZ') + ' MT';
                                    }
                                }
                            },
                            x: { 
                                grid: { display: false },
                                ticks: { font: { size: window.innerWidth < 640 ? 9 : 11 } }
                            }
                        }
                    }
                });
            }

            // Chart 2: Students Distribution
            const studentsCtx = document.getElementById('studentsChart')?.getContext('2d');
            if (studentsCtx) {
                const studentsDistributionData = @json($studentsDistribution ?? []);
                const labels = Array.isArray(studentsDistributionData)
                    ? studentsDistributionData.map(item => item.label)
                    : (studentsDistributionData.labels || []);
                const values = Array.isArray(studentsDistributionData)
                    ? studentsDistributionData.map(item => item.value)
                    : (studentsDistributionData.data || []);

                new Chart(studentsCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Nº de Alunos',
                            data: values,
                            backgroundColor: secondaryColor,
                            borderRadius: 8,
                            maxBarThickness: 32
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { color: 'rgba(226, 232, 240, 0.5)' },
                                ticks: { font: { size: window.innerWidth < 640 ? 10 : 11 } }
                            },
                            x: { 
                                grid: { display: false },
                                ticks: { font: { size: window.innerWidth < 640 ? 9 : 11 } }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
