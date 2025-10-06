@extends('layouts.app')

@section('title', 'Dashboard Administrativo')
@section('page-title', 'Dashboard Administrativo')

@php
    $titleIcon = 'fas fa-tachometer-alt';
@endphp

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Cards de Estatísticas -->
<div class="school-stats">
    <div class="stat-card students">
        <div class="stat-icon students">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['total_students']) }}</div>
            <div class="stat-label">Alunos Matriculados</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +{{ $stats['total_enrollments'] }} este ano
            </div>
        </div>
    </div>

    <div class="stat-card teachers">
        <div class="stat-icon teachers">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['total_teachers']) }}</div>
            <div class="stat-label">Professores Ativos</div>
            <div class="stat-change positive">
                <i class="fas fa-users"></i>
                {{ $stats['total_classes'] }} turmas
            </div>
        </div>
    </div>

    <div class="stat-card payments">
        <div class="stat-icon payments">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['monthly_revenue'], 2) }} MT</div>
            <div class="stat-label">Receita Mensal</div>
            <div class="stat-change {{ $stats['overdue_payments'] > 0 ? 'negative' : 'positive' }}">
                <i class="fas fa-{{ $stats['overdue_payments'] > 0 ? 'exclamation-triangle' : 'check' }}"></i>
                {{ $stats['overdue_payments'] }} em atraso
            </div>
        </div>
    </div>

    <div class="stat-card events">
        <div class="stat-icon events">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['todays_events']) }}</div>
            <div class="stat-label">Eventos Hoje</div>
            <div class="stat-change positive">
                <i class="fas fa-calendar-check"></i>
                Ver agenda
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-chart-line"></i>
                Receitas dos Últimos 12 Meses
            </div>
            <div class="school-card-body">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-chart-pie"></i>
                Alunos por Classe
            </div>
            <div class="school-card-body">
                <canvas id="classChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Eventos e Ações -->
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-calendar-alt"></i>
                Próximos Eventos
            </div>
            <div class="school-card-body">
                @forelse($upcomingEvents as $event)
                    <div class="d-flex align-items-center mb-3 p-3 border-start border-5 border-primary bg-light rounded">
                        <div class="me-3">
                            <div class="badge bg-primary rounded-pill" style="min-width: 50px;">
                                {{ $event->event_date->format('d/m') }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $event->title }}</h6>
                            <small class="text-muted">{{ Str::limit($event->description, 80) }}</small>
                        </div>
                        <div class="badge bg-{{ $event->type === 'exam' ? 'danger' : 'info' }}">
                            {{ ucfirst($event->type) }}
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fs-3 mb-2 d-block"></i>
                        Nenhum evento programado
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-exclamation-triangle"></i>
                Ações Necessárias
                @if($stats['pending_payments'] > 0 || $stats['overdue_payments'] > 0)
                    <span class="badge bg-danger ms-2">{{ $stats['pending_payments'] + $stats['overdue_payments'] }}</span>
                @endif
            </div>
            <div class="school-card-body">
                @if($stats['overdue_payments'] > 0)
                    <div class="alert-school alert-danger-school mb-3">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>{{ $stats['overdue_payments'] }} pagamentos em atraso</strong>
                            <br><small>Requer atenção imediata da secretaria</small>
                        </div>
                    </div>
                @endif

                @if($stats['pending_payments'] > 0)
                    <div class="alert-school alert-warning-school mb-3">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>{{ $stats['pending_payments'] }} pagamentos pendentes</strong>
                            <br><small>Aguardando processamento</small>
                        </div>
                    </div>
                @endif

                <div class="d-grid gap-2">
                    <a href="{{ route('payments.index') }}" class="btn-school btn-primary-school">
                        <i class="fas fa-money-bill-wave"></i>
                        Gerenciar Pagamentos
                    </a>
                    <a href="{{ route('students.index') }}" class="btn-school btn-success-school">
                        <i class="fas fa-user-graduate"></i>
                        Ver Alunos
                    </a>
                    <a href="{{ route('reports.financial') }}" class="btn-school btn-warning-school">
                        <i class="fas fa-chart-bar"></i>
                        Relatório Financeiro
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Receitas
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($monthlyStats['months']),
                datasets: [{
                    label: 'Receitas (MT)',
                    data: @json($monthlyStats['revenues']),
                    borderColor: '#2E7D32',
                    backgroundColor: 'rgba(46, 125, 50, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('pt-MZ') + ' MT';
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Classes
    const classCtx = document.getElementById('classChart');
    if (classCtx) {
        new Chart(classCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($classStats->pluck('grade_level_name')),
                datasets: [{
                    data: @json($classStats->pluck('total_students')),
                    backgroundColor: [
                        '#2E7D32', '#FFA000', '#00ACC1', '#FF5722',
                        '#9C27B0', '#3F51B5', '#4CAF50', '#FF9800'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 10 }
                    }
                }
            }
        });
    }
});
</script>
@endpush