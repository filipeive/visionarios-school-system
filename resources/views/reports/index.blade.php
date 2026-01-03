@extends('layouts.app')

@section('title', 'Painel de Relatórios')
@section('page-title', 'Relatórios')
@section('page-title-icon', 'fas fa-chart-pie')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Relatórios</li>
@endsection

@section('content')
    <div class="row">
        <!-- Estatísticas Rápidas -->
        <div class="col-md-3 mb-4">
            <div class="school-card text-center p-4">
                <div class="stat-icon mb-2 text-primary">
                    <i class="fas fa-user-graduate fa-2x"></i>
                </div>
                <div class="stat-value h4 fw-bold">{{ $stats['total_students'] }}</div>
                <div class="stat-label text-muted">Alunos Ativos</div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="school-card text-center p-4">
                <div class="stat-icon mb-2 text-success">
                    <i class="fas fa-chalkboard-teacher fa-2x"></i>
                </div>
                <div class="stat-value h4 fw-bold">{{ $stats['total_teachers'] }}</div>
                <div class="stat-label text-muted">Professores</div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="school-card text-center p-4">
                <div class="stat-icon mb-2 text-info">
                    <i class="fas fa-school fa-2x"></i>
                </div>
                <div class="stat-value h4 fw-bold">{{ $stats['total_classes'] }}</div>
                <div class="stat-label text-muted">Turmas Ativas</div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="school-card text-center p-4">
                <div class="stat-icon mb-2 text-warning">
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
                <div class="stat-value h4 fw-bold">{{ number_format($stats['monthly_revenue'], 2, ',', '.') }} MT</div>
                <div class="stat-label text-muted">Receita Mensal</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Seções de Relatórios -->
        <div class="col-md-6 mb-4">
            <div class="school-card h-100">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-academic-cap"></i> Relatórios Acadêmicos
                    </h3>
                </div>
                <div class="school-card-body">
                    <p class="text-muted">Acompanhe o desempenho dos alunos, frequência e dados das turmas.</p>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('reports.academic') }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-list-ul me-2 text-primary"></i> Visão Geral Acadêmica
                            </div>
                            <i class="fas fa-chevron-right small text-muted"></i>
                        </a>
                        <a href="{{ route('reports.academic.performance') }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-chart-line me-2 text-primary"></i> Desempenho de Alunos
                            </div>
                            <i class="fas fa-chevron-right small text-muted"></i>
                        </a>
                        <a href="{{ route('reports.academic.attendance') }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-calendar-check me-2 text-primary"></i> Relatório de Frequência
                            </div>
                            <i class="fas fa-chevron-right small text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="school-card h-100">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-wallet"></i> Relatórios Financeiros
                    </h3>
                </div>
                <div class="school-card-body">
                    <p class="text-muted">Monitore a saúde financeira, pagamentos e inadimplência.</p>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('reports.financial') }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-invoice-dollar me-2 text-success"></i> Visão Geral Financeira
                            </div>
                            <i class="fas fa-chevron-right small text-muted"></i>
                        </a>
                        <a href="{{ route('reports.financial.revenue') }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-hand-holding-usd me-2 text-success"></i> Relatório de Receitas
                            </div>
                            <i class="fas fa-chevron-right small text-muted"></i>
                        </a>
                        <a href="{{ route('reports.financial.defaulters') }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user-times me-2 text-success"></i> Relatório de Inadimplentes
                            </div>
                            <i class="fas fa-chevron-right small text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráficos Rápidos -->
        <div class="col-md-8 mb-4">
            <div class="school-card">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-chart-area"></i> Tendência de Receita (Últimos 6 meses)
                    </h3>
                </div>
                <div class="school-card-body">
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="school-card">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-chart-pie"></i> Alunos por Nível
                    </h3>
                </div>
                <div class="school-card-body">
                    <canvas id="studentsByGradeChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Gráfico de Receita
                fetch('{{ route('api.charts.revenue-monthly') }}')
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('revenueChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.map(item => item.month_name),
                                datasets: [{
                                    label: 'Receita (MT)',
                                    data: data.map(item => item.total),
                                    borderColor: '#28a745',
                                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    });

                // Gráfico de Alunos por Nível
                fetch('{{ route('api.charts.students-by-grade') }}')
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('studentsByGradeChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: data.map(item => item.label),
                                datasets: [{
                                    data: data.map(item => item.value),
                                    backgroundColor: [
                                        '#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#dc3545', '#fd7e14', '#ffc107', '#28a745'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    });
            });
        </script>
    @endpush
@endsection