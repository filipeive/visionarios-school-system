@extends('layouts.app')

@section('title', 'Relatórios Financeiros')
@section('page-title', 'Relatórios Financeiros')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pagamentos</a></li>
    <li class="breadcrumb-item active">Relatórios</li>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Filtro de Ano --}}
    <div class="school-card mb-4">
        <div class="school-card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Ano de Referência</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        @for($y = date('Y') - 2; $y <= date('Y'); $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-9 text-end">
                    <button type="button" class="btn btn-outline-primary" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Cards de Resumo --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card payments">
                <div class="stat-icon payments">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($stats['total_year'], 2, ',', '.') }}</div>
                    <div class="stat-label">MT Recebidos em {{ $year }}</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> Total do ano
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card events">
                <div class="stat-icon events">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($stats['total_pending'], 2, ',', '.') }}</div>
                    <div class="stat-label">MT em Dívida</div>
                    <span class="stat-change negative">
                        <i class="fas fa-clock"></i> Pendente
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card teachers">
                <div class="stat-icon teachers">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['total_students_debt'] }}</div>
                    <div class="stat-label">Alunos com Dívida</div>
                    <span class="stat-change">
                        <i class="fas fa-user-clock"></i> Inadimplentes
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="row mb-4">
        {{-- Receita Mensal --}}
        <div class="col-lg-8 mb-4">
            <div class="school-card h-100">
                <div class="school-card-header">
                    <i class="fas fa-chart-line"></i> Receita Mensal - {{ $year }}
                </div>
                <div class="school-card-body">
                    <canvas id="monthlyRevenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Receita por Tipo --}}
        <div class="col-lg-4 mb-4">
            <div class="school-card h-100">
                <div class="school-card-header">
                    <i class="fas fa-chart-pie"></i> Receita por Tipo
                </div>
                <div class="school-card-body">
                    <canvas id="revenueByTypeChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabela de Receita Mensal --}}
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-table"></i> Resumo Mensal
                </div>
                <div class="school-card-body p-0">
                    <table class="table table-school mb-0">
                        <thead>
                            <tr>
                                <th>Mês</th>
                                <th class="text-end">Valor Recebido</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
                                          'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                            @endphp
                            @foreach($meses as $i => $mes)
                            <tr>
                                <td>{{ $mes }}</td>
                                <td class="text-end">
                                    <strong>{{ number_format($monthlyRevenue[$i + 1] ?? 0, 2, ',', '.') }} MT</strong>
                                </td>
                                <td class="text-center">
                                    @if(($monthlyRevenue[$i + 1] ?? 0) > 0)
                                        <span class="badge bg-success">Recebido</span>
                                    @elseif($i + 1 <= date('n') && $year == date('Y'))
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>TOTAL</th>
                                <th class="text-end">{{ number_format(array_sum($monthlyRevenue), 2, ',', '.') }} MT</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Inadimplência por Turma --}}
        <div class="col-lg-6">
            <div class="school-card">
                <div class="school-card-header bg-danger text-white">
                    <i class="fas fa-exclamation-circle"></i> Inadimplência por Turma
                </div>
                <div class="school-card-body p-0">
                    <table class="table table-school mb-0">
                        <thead>
                            <tr>
                                <th>Turma</th>
                                <th class="text-center">Alunos</th>
                                <th class="text-end">Valor em Dívida</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($defaultersByClass as $item)
                            <tr>
                                <td>
                                    <span class="badge bg-info">{{ $item->name }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $item->count }}</span>
                                </td>
                                <td class="text-end">
                                    <strong class="text-danger">{{ number_format($item->total, 2, ',', '.') }} MT</strong>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-success">
                                    <i class="fas fa-check-circle"></i> Nenhuma inadimplência registrada
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($defaultersByClass->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <th>TOTAL</th>
                                <th class="text-center">{{ $defaultersByClass->sum('count') }}</th>
                                <th class="text-end text-danger">{{ number_format($defaultersByClass->sum('total'), 2, ',', '.') }} MT</th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Ações Rápidas --}}
    <div class="school-card">
        <div class="school-card-header">
            <i class="fas fa-bolt"></i> Ações Rápidas
        </div>
        <div class="school-card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="{{ route('payments.overdue') }}" class="btn btn-outline-danger w-100 py-3">
                        <i class="fas fa-exclamation-triangle fa-2x d-block mb-2"></i>
                        Ver Pagamentos em Atraso
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('payments.references') }}" class="btn btn-outline-primary w-100 py-3">
                        <i class="fas fa-receipt fa-2x d-block mb-2"></i>
                        Gerar Referências
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('payments.index', ['status' => 'paid']) }}" class="btn btn-outline-success w-100 py-3">
                        <i class="fas fa-check-circle fa-2x d-block mb-2"></i>
                        Pagamentos Confirmados
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('reports.export.payments') }}" class="btn btn-outline-secondary w-100 py-3">
                        <i class="fas fa-download fa-2x d-block mb-2"></i>
                        Exportar Todos os Dados
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
    // Dados do backend
    const monthlyData = @json($monthlyRevenue);
    const typeData = @json($revenueByType);
    const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

    // Gráfico de Receita Mensal
    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Receita (MT)',
                data: months.map((_, i) => monthlyData[i + 1] || 0),
                backgroundColor: 'rgba(25, 67, 124, 0.8)',
                borderColor: '#19437C',
                borderWidth: 1,
                borderRadius: 5
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

    // Gráfico de Receita por Tipo
    const typeLabels = {
        'matricula': 'Matrícula',
        'mensalidade': 'Mensalidade',
        'material': 'Material',
        'uniforme': 'Uniforme',
        'outro': 'Outro'
    };
    
    new Chart(document.getElementById('revenueByTypeChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(typeData).map(k => typeLabels[k] || k),
            datasets: [{
                data: Object.values(typeData),
                backgroundColor: ['#19437C', '#4BA83C', '#F9A825', '#17a2b8', '#6c757d'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15 }
                }
            }
        }
    });
});

function exportReport(format) {
    const year = document.querySelector('select[name="year"]').value;
    window.location.href = `/reports/export/financial?year=${year}&format=${format}`;
}
</script>
@endpush