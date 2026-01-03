@extends('layouts.app')

@section('title', 'Relatórios Financeiros')
@section('page-title', 'Visão Geral Financeira')
@section('page-title-icon', 'fas fa-money-check-alt')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item active">Financeiro</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="school-card">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-history"></i> Pagamentos Recentes
                    </h3>
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-school">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->student->first_name }} {{ $payment->student->last_name }}</td>
                                        <td>{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '-' }}</td>
                                        <td>{{ number_format($payment->amount, 2, ',', '.') }} MT</td>
                                        <td>
                                            <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="school-card">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-chart-line"></i> Receita Mensal
                    </h3>
                </div>
                <div class="school-card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($monthlyRevenue as $revenue)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ Carbon\Carbon::parse($revenue->month . '-01')->format('M Y') }}
                                <span class="fw-bold">{{ number_format($revenue->total, 2, ',', '.') }} MT</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="school-card mt-4">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-exclamation-triangle"></i> Inadimplência
                    </h3>
                </div>
                <div class="school-card-body text-center">
                    <p class="text-muted">Existem alunos com mensalidades pendentes para este mês.</p>
                    <a href="{{ route('reports.financial.defaulters') }}" class="btn btn-warning w-100">
                        Ver Inadimplentes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-tools"></i> Ferramentas de Exportação
                    </h3>
                </div>
                <div class="school-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('reports.export.payments') }}" class="btn btn-outline-success w-100 p-4 mb-3">
                                <i class="fas fa-file-excel fa-2x mb-2"></i><br>
                                Exportar Histórico de Pagamentos
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('reports.financial.revenue') }}"
                                class="btn btn-outline-primary w-100 p-4 mb-3">
                                <i class="fas fa-search-dollar fa-2x mb-2"></i><br>
                                Relatório Detalhado de Receitas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection