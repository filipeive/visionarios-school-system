@extends('layouts.app')

@section('title', 'Notas - ' . $subject->name)
@section('page-title', 'Notas da Disciplina')
@section('page-title-icon', 'fas fa-medal')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Disciplinas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('subjects.show', $subject->id) }}">{{ $subject->name }}</a></li>
    <li class="breadcrumb-item active">Notas</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Informações da Disciplina -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>{{ $subject->name }} ({{ $subject->code }})</h4>
                        <p class="text-muted mb-0">
                            <strong>Nível:</strong> {{ $gradeLevels[$subject->grade_level] ?? $subject->grade_level }} |
                            <strong>Total de Notas:</strong> {{ $gradeStats['total_grades'] }} |
                            <strong>Taxa de Aprovação:</strong> {{ $gradeStats['approval_rate'] }}%
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('subjects.show', $subject->id) }}" class="btn btn-secondary-school">
                            <i class="fas fa-arrow-left"></i> Voltar à Disciplina
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas de Notas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon students">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ number_format($gradeStats['average_grade'], 1) }}</div>
                        <div class="stat-label">Média Geral</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon teachers">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $gradeStats['approval_rate'] }}%</div>
                        <div class="stat-label">Taxa Aprovação</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon payments">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ number_format($gradeStats['max_grade'], 1) }}</div>
                        <div class="stat-label">Nota Máxima</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon events">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ number_format($gradeStats['min_grade'], 1) }}</div>
                        <div class="stat-label">Nota Mínima</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribuição de Notas -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="school-card">
                    <div class="school-card-header">
                        <h3 class="school-card-title">
                            <i class="fas fa-chart-pie"></i>
                            Distribuição de Notas
                        </h3>
                    </div>
                    <div class="school-card-body">
                        <canvas id="gradeDistributionChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="school-card">
                    <div class="school-card-header">
                        <h3 class="school-card-title">
                            <i class="fas fa-list"></i>
                            Resumo por Categoria
                        </h3>
                    </div>
                    <div class="school-card-body">
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Excelente (14-20)
                                <span class="badge bg-success rounded-pill">{{ $gradeDistribution['excellent'] }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Bom (12-13.9)
                                <span class="badge bg-primary rounded-pill">{{ $gradeDistribution['good'] }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Suficiente (10-11.9)
                                <span class="badge bg-warning rounded-pill">{{ $gradeDistribution['sufficient'] }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Insuficiente (0-9.9)
                                <span class="badge bg-danger rounded-pill">{{ $gradeDistribution['insufficient'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Período</label>
                        <select name="term" class="form-select" onchange="this.form.submit()">
                            <option value="">Todos os períodos</option>
                            <option value="1" {{ request('term') == '1' ? 'selected' : '' }}>1º Período</option>
                            <option value="2" {{ request('term') == '2' ? 'selected' : '' }}>2º Período</option>
                            <option value="3" {{ request('term') == '3' ? 'selected' : '' }}>3º Período</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ano</label>
                        <select name="year" class="form-select" onchange="this.form.submit()">
                            @for($i = date('Y') - 2; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo Avaliação</label>
                        <select name="assessment_type" class="form-select" onchange="this.form.submit()">
                            <option value="">Todos</option>
                            <option value="continuous" {{ request('assessment_type') == 'continuous' ? 'selected' : '' }}>Contínua</option>
                            <option value="test" {{ request('assessment_type') == 'test' ? 'selected' : '' }}>Teste</option>
                            <option value="exam" {{ request('assessment_type') == 'exam' ? 'selected' : '' }}>Exame</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <a href="{{ route('subjects.grades', $subject->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Notas -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-list"></i>
                    Histórico de Notas ({{ $subject->grades->count() }})
                </h3>
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Turma</th>
                            <th>Nota</th>
                            <th>Tipo</th>
                            <th>Período</th>
                            <th>Ano</th>
                            <th>Data</th>
                            <th>Professor</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subject->grades as $grade)
                            <tr>
                                <td>
                                    <strong>{{ $grade->student->first_name }} {{ $grade->student->last_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $grade->student->student_number }}</small>
                                </td>
                                <td>
                                    @php
                                        $enrollment = $grade->student->enrollments->where('status', 'active')->first();
                                    @endphp
                                    @if($enrollment)
                                        <span class="badge bg-primary">{{ $enrollment->class->name }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $grade->grade >= 10 ? 'success' : 'danger' }} fs-6">
                                        {{ number_format($grade->grade, 1) }}
                                    </span>
                                </td>
                                <td>
                                    @switch($grade->assessment_type)
                                        @case('continuous') Contínua @break
                                        @case('test') Teste @break
                                        @case('exam') Exame @break
                                        @case('final') Final @break
                                        @default {{ $grade->assessment_type }}
                                    @endswitch
                                </td>
                                <td>{{ $grade->term }}º Período</td>
                                <td>{{ $grade->year }}</td>
                                <td>{{ $grade->date_recorded->format('d/m/Y') }}</td>
                                <td>
                                    @if($grade->teacher)
                                        {{ $grade->teacher->first_name }} {{ $grade->teacher->last_name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $grade->comments ? Str::limit($grade->comments, 30) : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-medal fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhuma nota registrada para esta disciplina.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($subject->grades->count() > 20)
                <div class="school-card-body border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Mostrando {{ min($subject->grades->count(), 20) }} de {{ $subject->grades->count() }} registros
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-primary" onclick="loadMoreGrades()">
                                <i class="fas fa-plus"></i> Carregar Mais
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Top 10 Melhores Notas -->
        @if($subject->grades->count() > 0)
        <div class="school-card mt-4">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-trophy"></i>
                    Top 10 Melhores Notas
                </h3>
            </div>
            <div class="school-card-body">
                <div class="row">
                    @foreach($subject->grades->sortByDesc('grade')->take(10) as $index => $grade)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }} me-2">
                                                    {{ $index + 1 }}º
                                                </span>
                                                {{ $grade->student->first_name }} {{ $grade->student->last_name }}
                                            </h6>
                                            <small class="text-muted">
                                                {{ $grade->student->student_number }} | 
                                                @php
                                                    $enrollment = $grade->student->enrollments->where('status', 'active')->first();
                                                @endphp
                                                @if($enrollment)
                                                    {{ $enrollment->class->name }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success fs-5">
                                                {{ number_format($grade->grade, 1) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                {{ $grade->date_recorded->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de distribuição de notas
    const ctx = document.getElementById('gradeDistributionChart').getContext('2d');
    const gradeDistributionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Excelente (14-20)', 'Bom (12-13.9)', 'Suficiente (10-11.9)', 'Insuficiente (0-9.9)'],
            datasets: [{
                data: [
                    {{ $gradeDistribution['excellent'] }},
                    {{ $gradeDistribution['good'] }},
                    {{ $gradeDistribution['sufficient'] }},
                    {{ $gradeDistribution['insufficient'] }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#007bff',
                    '#ffc107',
                    '#dc3545'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Distribuição de Notas'
                }
            }
        }
    });

    // Função para carregar mais notas
    window.loadMoreGrades = function() {
        // Implementar carregamento AJAX para mais registros
        alert('Funcionalidade de carregar mais registros será implementada em breve.');
    };
});
</script>
@endpush