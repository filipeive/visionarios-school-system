@extends('layouts.app')

@section('title', 'Relatório de Presenças')
@section('page-title', 'Relatório de Presenças')

@php
    $titleIcon = 'fas fa-chart-bar';
@endphp

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Presenças</a></li>
    <li class="breadcrumb-item active">Relatório</li>
@endsection

@section('content')
<!-- Filtros de Relatório -->
<div class="school-card mb-4">
    <div class="school-card-header">
        <i class="fas fa-filter"></i>
        Parâmetros do Relatório
    </div>
    <div class="school-card-body">
        <form method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Turma</label>
                    <select name="class_id" class="form-select">
                        <option value="">Todas as turmas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Período</label>
                    <select name="period" class="form-select">
                        <option value="today">Hoje</option>
                        <option value="week">Esta Semana</option>
                        <option value="month" selected>Este Mês</option>
                        <option value="custom">Personalizado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn-school btn-primary-school w-100">
                        <i class="fas fa-search"></i> Gerar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Gráfico de Presenças -->
<div class="row">
    <div class="col-lg-8">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-chart-line"></i>
                Frequência por Dia
            </div>
            <div class="school-card-body">
                <canvas id="attendanceChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-chart-pie"></i>
                Distribuição Geral
            </div>
            <div class="school-card-body">
                <canvas id="pieChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Gráfico de linha
const ctx = document.getElementById('attendanceChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex'],
        datasets: [{
            label: 'Presentes',
            data: [95, 92, 96, 90, 88],
            borderColor: '#4CAF50',
            backgroundColor: 'rgba(76, 175, 80, 0.1)',
            tension: 0.4
        }, {
            label: 'Ausentes',
            data: [5, 8, 4, 10, 12],
            borderColor: '#F44336',
            backgroundColor: 'rgba(244, 67, 54, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Gráfico de pizza
const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: ['Presentes', 'Ausentes', 'Atrasados', 'Justificados'],
        datasets: [{
            data: [461, 39, 25, 10],
            backgroundColor: ['#4CAF50', '#F44336', '#FF9800', '#2196F3']
        }]
    }
});
</script>
@endpush