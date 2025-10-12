@extends('layouts.app')

@section('title', 'Dashboard Administrativo')
@section('page-title', 'Dashboard Administrativo')
@section('title-icon', 'fas fa-tachometer-alt')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <button class="btn btn-school btn-primary-school" onclick="refreshDashboard()">
            <i class="fas fa-sync-alt"></i> Atualizar
        </button>
        <button class="btn btn-school btn-primary-school dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Exportar Relatório</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Imprimir</a></li>
        </ul>
    </div>
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
            <div class="stat-label">Total de Alunos</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +{{ $stats['new_students_this_month'] }} este mês
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
            <div class="stat-value">{{ number_format($stats['monthly_revenue'], 0, ',', '.') }} MT</div>
            <div class="stat-label">Receita Mensal</div>
            <div class="stat-change {{ $stats['revenue_change'] >= 0 ? 'positive' : 'negative' }}">
                <i class="fas fa-{{ $stats['revenue_change'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($stats['revenue_change']) }}% vs mês anterior
            </div>
        </div>
    </div>

    <div class="stat-card events">
        <div class="stat-icon events">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['overdue_payments']) }}</div>
            <div class="stat-label">Pagamentos em Atraso</div>
            <div class="stat-change negative">
                <i class="fas fa-clock"></i>
                {{ number_format($stats['overdue_amount'], 0, ',', '.') }} MT
            </div>
        </div>
    </div>
</div>

<!-- Gráficos e Métricas -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-chart-line"></i>
                Receitas dos Últimos 6 Meses
            </div>
            <div class="school-card-body">
                <div style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-chart-pie"></i>
                Distribuição de Alunos por Classe
            </div>
            <div class="school-card-body">
                <div style="height: 300px;">
                    <canvas id="studentsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informações Rápidas e Ações -->
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-calendar-alt"></i>
                    Próximos Eventos
                </div>
                <a href="{{ route('events.index') }}" class="btn btn-school btn-primary-school btn-sm">
                    <i class="fas fa-plus"></i> Novo
                </a>
            </div>
            <div class="school-card-body">
                @forelse($upcomingEvents as $event)
                    <div class="event-item d-flex align-items-center mb-3 p-3 border-start border-4 
                              {{ $event->type === 'exam' ? 'border-danger' : 
                                 ($event->type === 'holiday' ? 'border-warning' : 'border-info') }} 
                              bg-light rounded">
                        <div class="event-date me-3 text-center">
                            <div class="fw-bold text-primary">{{ $event->event_date->format('d') }}</div>
                            <div class="text-muted small text-uppercase">{{ $event->event_date->format('M') }}</div>
                        </div>
                        <div class="event-details flex-grow-1">
                            <h6 class="mb-1 fw-semibold">{{ $event->title }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $event->event_date->format('H:i') }} • 
                                {{ $event->location ?? 'Escola' }}
                            </small>
                        </div>
                        <div class="event-badge">
                            <span class="badge bg-{{ $event->type === 'exam' ? 'danger' : 
                                                   ($event->type === 'holiday' ? 'warning' : 'info') }}">
                                {{ ucfirst($event->type) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fs-3 mb-2 d-block"></i>
                        Nenhum evento programado
                    </div>
                @endforelse
                
                @if(count($upcomingEvents) > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('events.index') }}" class="btn btn-school btn-outline-primary btn-sm">
                            Ver Todos os Eventos
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-exclamation-circle"></i>
                    Ações Necessárias
                    @if($stats['pending_actions'] > 0)
                        <span class="nav-badge badge-danger ms-2">{{ $stats['pending_actions'] }}</span>
                    @endif
                </div>
            </div>
            <div class="school-card-body">
                <!-- Alertas de Pagamentos -->
                @if($stats['overdue_payments'] > 0)
                    <div class="alert-school alert-danger-school mb-3 d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-3"></i>
                        <div class="flex-grow-1">
                            <strong>{{ $stats['overdue_payments'] }} pagamentos em atraso</strong>
                            <div class="small">Valor total: {{ number_format($stats['overdue_amount'], 0, ',', '.') }} MT</div>
                        </div>
                        <a href="{{ route('payments.index') }}?status=overdue" class="btn btn-school btn-outline-danger btn-sm">
                            Resolver
                        </a>
                    </div>
                @endif

                <!-- Alertas de Matrículas Pendentes -->
                @if($stats['pending_enrollments'] > 0)
                    <div class="alert-school alert-warning-school mb-3 d-flex align-items-center">
                        <i class="fas fa-clipboard-list me-3"></i>
                        <div class="flex-grow-1">
                            <strong>{{ $stats['pending_enrollments'] }} matrículas pendentes</strong>
                            <div class="small">Aguardando aprovação</div>
                        </div>
                        <a href="{{ route('enrollments.index') }}?status=pending" class="btn btn-school btn-outline-warning btn-sm">
                            Revisar
                        </a>
                    </div>
                @endif

                <!-- Alertas de Licenças -->
                @if($stats['pending_leave_requests'] > 0)
                    <div class="alert-school alert-info-school mb-3 d-flex align-items-center">
                        <i class="fas fa-calendar-times me-3"></i>
                        <div class="flex-grow-1">
                            <strong>{{ $stats['pending_leave_requests'] }} pedidos de licença</strong>
                            <div class="small">Necessitam de atenção</div>
                        </div>
                        <a href="{{ route('teacher.leave-requests') }}" class="btn btn-school btn-outline-info btn-sm">
                            Analisar
                        </a>
                    </div>
                @endif

                <!-- Ações Rápidas -->
                <div class="quick-actions mt-4">
                    <h6 class="fw-semibold mb-3">Ações Rápidas</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('students.create') }}" class="btn btn-school btn-outline-primary w-100 d-flex align-items-center justify-content-center py-2">
                                <i class="fas fa-user-plus me-2"></i>
                                Novo Aluno
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('payments.create') }}" class="btn btn-school btn-outline-success w-100 d-flex align-items-center justify-content-center py-2">
                                <i class="fas fa-money-bill me-2"></i>
                                Receber Pagamento
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('reports.index') }}" class="btn btn-school btn-outline-warning w-100 d-flex align-items-center justify-content-center py-2">
                                <i class="fas fa-chart-bar me-2"></i>
                                Relatório Financeiro
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('communications.create') }}" class="btn btn-school btn-outline-info w-100 d-flex align-items-center justify-content-center py-2">
                                <i class="fas fa-bullhorn me-2"></i>
                                Enviar Comunicado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Últimas Atividades -->
<div class="row mt-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-history"></i>
                    Últimas Atividades
                </div>
                <a href="#" class="btn btn-school btn-outline-secondary btn-sm">
                    Ver Log Completo
                </a>
            </div>
            <div class="school-card-body p-0">
                <div class="school-table-container">
                    <table class="table-school table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="60">Tipo</th>
                                <th>Descrição</th>
                                <th width="120">Usuário</th>
                                <th width="150">Data/Hora</th>
                                <th width="80">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $activity->type === 'payment' ? 'success' : 
                                                              ($activity->type === 'enrollment' ? 'primary' : 
                                                              ($activity->type === 'user' ? 'info' : 'secondary')) }}">
                                            <i class="fas fa-{{ $activity->icon }}"></i>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $activity->title }}</div>
                                        <small class="text-muted">{{ $activity->description }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">
                                                {{ substr($activity->user_name, 0, 1) }}
                                            </div>
                                            <span>{{ $activity->user_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $activity->created_at->format('d/m/Y') }}<br>
                                            {{ $activity->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <button class="btn btn-school btn-outline-primary btn-sm" title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fs-3 mb-2 d-block"></i>
                                        Nenhuma atividade recente
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .event-item {
        transition: all 0.2s;
        border-left-color: var(--primary) !important;
    }
    
    .event-item:hover {
        transform: translateX(4px);
        background: var(--content-bg) !important;
    }
    
    .user-avatar-sm {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 10px;
        flex-shrink: 0;
    }
    
    .quick-actions .btn-school {
        border-radius: var(--border-radius);
        font-size: 12px;
        transition: all 0.2s;
        padding: 8px 12px;
    }
    
    .quick-actions .btn-school:hover {
        transform: translateY(-1px);
    }

    .alert-school.d-flex {
        padding: 15px 20px;
        border-radius: var(--border-radius);
        margin-bottom: 15px;
    }

    .alert-school.d-flex i {
        font-size: 18px;
        margin-right: 12px;
    }

    .school-card-body > div[style*="height"] {
        position: relative;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Variáveis globais para os gráficos
let revenueChartInstance = null;
let studentsChartInstance = null;

// Dados reais do controller
const chartData = {
    revenue: @json($revenueData),
    students: @json($studentsDistribution)
};

function initializeCharts() {
    // Destruir gráficos existentes
    if (revenueChartInstance) {
        revenueChartInstance.destroy();
    }
    if (studentsChartInstance) {
        studentsChartInstance.destroy();
    }

    // Gráfico de Receitas
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx && chartData.revenue) {
        revenueChartInstance = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: chartData.revenue.months,
                datasets: [{
                    label: 'Receitas (MT)',
                    data: chartData.revenue.amounts,
                    borderColor: '#1a365d',
                    backgroundColor: 'rgba(26, 54, 93, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#1a365d',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
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
                        backgroundColor: 'rgba(26, 54, 93, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#d4af37',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return `Receita: ${context.parsed.y.toLocaleString('pt-MZ')} MT`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('pt-MZ') + ' MT';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    } else {
        console.warn('Dados de receita não disponíveis para o gráfico');
    }

    // Gráfico de Distribuição de Alunos
    const studentsCtx = document.getElementById('studentsChart');
    if (studentsCtx && chartData.students) {
        studentsChartInstance = new Chart(studentsCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.students.labels,
                datasets: [{
                    data: chartData.students.data,
                    backgroundColor: [
                        '#1a365d', '#d4af37', '#e53e3e', '#38a169',
                        '#dd6b20', '#3182ce', '#805ad5', '#d53f8c'
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: {
                                size: 11
                            },
                            color: 'var(--text-primary)'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 54, 93, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#d4af37',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return `${context.label}: ${context.parsed} alunos (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    } else {
        console.warn('Dados de distribuição de alunos não disponíveis para o gráfico');
    }
}

function refreshDashboard() {
    const btn = document.querySelector('.btn-school.btn-primary-school');
    const originalHtml = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Atualizando...';
    btn.disabled = true;

    // Fazer requisição para atualizar os dados
    fetch('/api/dashboard/counters')
        .then(response => response.json())
        .then(data => {
            // Atualizar badges e contadores
            updateLiveCounters(data);
            
            // Recarregar a página para obter dados atualizados
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            console.error('Erro ao atualizar dashboard:', error);
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            window.VisionariosSchool.showToast('Erro ao atualizar dashboard', 'error');
        });
}

function updateLiveCounters(data) {
    // Atualizar badge de notificações no header
    const notificationBadge = document.querySelector('.notification-badge');
    if (notificationBadge && data.notifications !== undefined) {
        if (data.notifications > 0) {
            notificationBadge.textContent = data.notifications;
        } else {
            notificationBadge.remove();
        }
    }
}

function initializeAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.5s ease ${index * 0.1}s`;
        observer.observe(card);
    });
}

// Inicialização quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    initializeAnimations();

    // Configurar atualização automática a cada 2 minutos
    setInterval(() => {
        fetch('/api/dashboard/counters')
            .then(response => response.json())
            .then(data => updateLiveCounters(data))
            .catch(error => console.error('Erro ao atualizar contadores:', error));
    }, 120000);
});

// Re-inicializar gráficos quando a janela for redimensionada
window.addEventListener('resize', function() {
    initializeCharts();
});
</script>
@endpush