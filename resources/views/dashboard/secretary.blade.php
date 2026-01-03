@extends('layouts.app')

@section('title', 'Dashboard da Secretaria')
@section('page-title', 'Dashboard da Secretaria')
@section('title-icon', 'fas fa-user-tie')

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
                    <i class="fas fa-plus"></i>
                    {{ $stats['new_enrollments_month'] }} este mês
                </div>
            </div>
        </div>

        <div class="stat-card teachers">
            <div class="stat-icon teachers" style="background: var(--gradient-accent);">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['pending_enrollments']) }}</div>
                <div class="stat-label">Matrículas Pendentes</div>
                <div class="stat-change warning">
                    <i class="fas fa-clock"></i> Aguardando revisão
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
                <div class="stat-change positive">
                    <i class="fas fa-calendar-day"></i> {{ $stats['todays_payments'] }} hoje
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

    <div class="row mt-4">
        <!-- Matrículas Pendentes -->
        <div class="col-lg-6">
            <div class="school-card">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-clipboard-list"></i>
                        Matrículas Pendentes
                    </div>
                    <a href="{{ route('enrollments.index') }}?status=pending"
                        class="btn btn-school btn-primary-school btn-sm">
                        Ver Todas
                    </a>
                </div>
                <div class="school-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Classe</th>
                                    <th>Data</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingEnrollments as $enrollment)
                                    <tr>
                                        <td>{{ $enrollment->student->full_name }}</td>
                                        <td>{{ $enrollment->class->name ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->created_at->format('d/m/Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('enrollments.show', $enrollment) }}"
                                                class="btn btn-sm btn-light rounded-circle">
                                                <i class="fas fa-eye text-primary"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Nenhuma matrícula pendente</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagamentos em Atraso -->
        <div class="col-lg-6">
            <div class="school-card">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-clock text-danger"></i>
                        Pagamentos em Atraso
                    </div>
                    <a href="{{ route('payments.index') }}?status=overdue" class="btn btn-school btn-outline-danger btn-sm">
                        Ver Todos
                    </a>
                </div>
                <div class="school-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Vencimento</th>
                                    <th>Valor</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overduePayments as $payment)
                                    <tr>
                                        <td>{{ $payment->student->full_name }}</td>
                                        <td>{{ $payment->due_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                        <td class="fw-bold text-danger">{{ number_format($payment->amount, 2, ',', '.') }} MT
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('payments.show', $payment) }}"
                                                class="btn btn-sm btn-light rounded-circle">
                                                <i class="fas fa-receipt text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Nenhum pagamento em atraso</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Últimos Pagamentos -->
        <div class="col-lg-8">
            <div class="school-card">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-history"></i>
                        Últimos Pagamentos Recebidos
                    </div>
                    <a href="{{ route('payments.index') }}" class="btn btn-school btn-outline-secondary btn-sm">
                        Ver Histórico
                    </a>
                </div>
                <div class="school-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Data</th>
                                    <th>Método</th>
                                    <th>Valor</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->student->full_name }}</td>
                                        <td>{{ $payment->payment_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td class="fw-bold text-success">{{ number_format($payment->amount, 2, ',', '.') }} MT
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('payments.show', $payment) }}"
                                                class="btn btn-sm btn-light rounded-circle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Nenhum pagamento registrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="col-lg-4">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-bolt"></i> Ações Rápidas
                </div>
                <div class="school-card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('students.create') }}" class="btn btn-outline-primary text-start p-3 rounded-3">
                            <i class="fas fa-user-plus me-2"></i> Novo Aluno
                        </a>
                        <a href="{{ route('payments.create') }}" class="btn btn-outline-success text-start p-3 rounded-3">
                            <i class="fas fa-money-bill-wave me-2"></i> Receber Pagamento
                        </a>
                        <a href="{{ route('enrollments.create') }}" class="btn btn-outline-info text-start p-3 rounded-3">
                            <i class="fas fa-id-card me-2"></i> Nova Matrícula
                        </a>
                        <a href="{{ route('communications.create') }}"
                            class="btn btn-outline-warning text-start p-3 rounded-3">
                            <i class="fas fa-bullhorn me-2"></i> Enviar Comunicado
                        </a>
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
            border-bottom: 4px solid var(--accent);
        }

        .stat-card.payments {
            border-bottom: 4px solid var(--secondary);
        }

        .stat-card.events {
            border-bottom: 4px solid var(--danger);
        }

        .stat-change.warning {
            background: rgba(249, 168, 37, 0.1);
            color: var(--accent);
        }
    </style>
@endpush