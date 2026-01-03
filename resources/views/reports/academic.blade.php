@extends('layouts.app')

@section('title', 'Relatórios Acadêmicos')
@section('page-title', 'Visão Geral Acadêmica')
@section('page-title-icon', 'fas fa-graduation-cap')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item active">Acadêmico</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card mb-4">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <h3 class="school-card-title">
                        <i class="fas fa-chalkboard"></i> Resumo por Turma
                    </h3>
                    <a href="{{ route('reports.export.students') }}" class="btn btn-sm btn-secondary-school">
                        <i class="fas fa-file-export"></i> Exportar Alunos
                    </a>
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-school">
                            <thead>
                                <tr>
                                    <th>Turma</th>
                                    <th>Nível</th>
                                    <th>Professor</th>
                                    <th>Alunos Ativos</th>
                                    <th>Capacidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classes as $class)
                                    <tr>
                                        <td><strong>{{ $class->name }}</strong></td>
                                        <td>{{ $class->grade_level_name }}</td>
                                        <td>{{ $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'N/A' }}
                                        </td>
                                        <td>{{ $class->active_students_count }}</td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                @php $percent = $class->max_students > 0 ? ($class->active_students_count / $class->max_students) * 100 : 0; @endphp
                                                <div class="progress-bar bg-{{ $percent > 90 ? 'danger' : ($percent > 70 ? 'warning' : 'success') }}"
                                                    role="progressbar" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ round($percent) }}%
                                                ({{ $class->active_students_count }}/{{ $class->max_students }})</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('classes.show', $class->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
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

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="school-card">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-chart-bar"></i> Frequência Semanal
                    </h3>
                </div>
                <div class="school-card-body">
                    <canvas id="attendanceChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="school-card">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-info-circle"></i> Ações Rápidas
                    </h3>
                </div>
                <div class="school-card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('reports.academic.performance') }}"
                            class="btn btn-outline-primary text-start p-3">
                            <i class="fas fa-chart-line me-2"></i> Ver Desempenho por Disciplina
                        </a>
                        <a href="{{ route('reports.academic.attendance') }}" class="btn btn-outline-info text-start p-3">
                            <i class="fas fa-calendar-check me-2"></i> Ver Relatório Detalhado de Frequência
                        </a>
                        <a href="{{ route('reports.export.grades') }}" class="btn btn-outline-secondary text-start p-3">
                            <i class="fas fa-file-excel me-2"></i> Exportar Notas (Excel)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                fetch('{{ route('api.charts.attendance-weekly') }}')
                    .then(response => response.json())
                    .then(data => {
                        const days = [...new Set(data.map(item => item.day_name))];
                        const presentData = days.map(day => {
                            const item = data.find(i => i.day_name === day && i.status === 'present');
                            return item ? item.total : 0;
                        });
                        const absentData = days.map(day => {
                            const item = data.find(i => i.day_name === day && i.status === 'absent');
                            return item ? item.total : 0;
                        });

                        const ctx = document.getElementById('attendanceChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: days,
                                datasets: [
                                    {
                                        label: 'Presentes',
                                        data: presentData,
                                        backgroundColor: '#28a745'
                                    },
                                    {
                                        label: 'Ausentes',
                                        data: absentData,
                                        backgroundColor: '#dc3545'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: { beginAtZero: true }
                                }
                            }
                        });
                    });
            });
        </script>
    @endpush
@endsection