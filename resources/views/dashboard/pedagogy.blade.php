@extends('layouts.app')

@section('title', 'Dashboard Pedagógico')
@section('page-title', 'Dashboard Pedagógico')
@section('title-icon', 'fas fa-graduation-cap')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <button class="btn btn-school btn-primary-school" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i> Atualizar
        </button>
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
                    <i class="fas fa-users"></i> {{ $stats['total_classes'] }} turmas
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
                <div class="stat-change info">
                    <i class="fas fa-book"></i> {{ $stats['total_subjects'] }} disciplinas
                </div>
            </div>
        </div>

        <div class="stat-card attendance">
            <div class="stat-icon attendance" style="background: var(--gradient-success);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['average_attendance'] }}%</div>
                <div class="stat-label">Presença Média</div>
                <div class="stat-change positive">
                    <i class="fas fa-check"></i> Este mês
                </div>
            </div>
        </div>

        <div class="stat-card performance">
            <div class="stat-icon performance" style="background: var(--gradient-accent);">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['class_performance_avg'] }}</div>
                <div class="stat-label">Média Global</div>
                <div class="stat-change warning">
                    <i class="fas fa-star"></i> Desempenho
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Próximos Exames/Avaliações -->
        <div class="col-lg-6">
            <div class="school-card">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-file-alt text-danger"></i>
                        Próximas Avaliações
                    </div>
                    <span class="badge bg-danger rounded-pill">{{ $stats['upcoming_exams'] }}</span>
                </div>
                <div class="school-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Turma</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingExams as $exam)
                                    <tr>
                                        <td>{{ $exam->title }}</td>
                                        <td>{{ $exam->class->name ?? 'N/A' }}</td>
                                        <td>{{ $exam->event_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Nenhuma avaliação agendada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notas Pendentes -->
        <div class="col-lg-6">
            <div class="school-card">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-edit text-warning"></i>
                        Lançamentos Pendentes
                    </div>
                    <span class="badge bg-warning text-dark rounded-pill">{{ $stats['pending_grades'] }}</span>
                </div>
                <div class="school-card-body p-5 text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-circle text-warning fa-3x"></i>
                    </div>
                    <h5>{{ $stats['pending_grades'] }} notas aguardando lançamento</h5>
                    <p class="text-muted small">Notifique os professores responsáveis para regularizar a situação.</p>
                    <a href="{{ route('grades.index') }}" class="btn btn-sm btn-outline-warning rounded-pill">
                        Ver Detalhes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Desempenho por Turma -->
        <div class="col-lg-12">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-chart-bar"></i> Desempenho por Turma
                </div>
                <div class="school-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Turma</th>
                                    <th>Professor</th>
                                    <th>Alunos</th>
                                    <th>Média</th>
                                    <th width="200">Progresso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classPerformance as $perf)
                                    <tr>
                                        <td>{{ $perf['class_name'] }}</td>
                                        <td>{{ $perf['teacher_name'] }}</td>
                                        <td>{{ $perf['total_students'] }}</td>
                                        <td class="fw-bold">{{ $perf['average_grade'] }}</td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: {{ ($perf['average_grade'] / 20) * 100 }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
        .stat-card.students {
            border-bottom: 4px solid var(--primary);
        }

        .stat-card.teachers {
            border-bottom: 4px solid var(--info);
        }

        .stat-card.attendance {
            border-bottom: 4px solid var(--secondary);
        }

        .stat-card.performance {
            border-bottom: 4px solid var(--accent);
        }
    </style>
@endpush