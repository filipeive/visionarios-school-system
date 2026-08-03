@extends('layouts.app')

@section('title', 'Pauta Final & Exames - ' . $class->name)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-success-subtle text-success border border-success fw-bold">
                    {{ $class->education_level_name }}
                </span>
                <span class="badge bg-secondary-subtle text-secondary fw-bold">
                    {{ $class->grade_level_name }}
                </span>
            </div>
            <h1 class="h3 font-weight-bold text-dark mb-0">Pauta Final & Exames: {{ $class->name }}</h1>
            <p class="text-muted small mb-0">Resultados Finais, Admissão a Exame, Exame Normal/Recorrência e Média Final de Disciplina (MFD).</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('pautas.trimestral', $class->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-list-alt me-1"></i> Trimestral
            </a>
            <a href="{{ route('pautas.anual', $class->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-calendar-alt me-1"></i> Anual
            </a>
            <a href="{{ route('pautas.pdf', ['class' => $class->id, 'type' => 'final']) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Exportar PDF Oficial
            </a>
        </div>
    </div>

    <!-- Estatísticas de Aprovação da Turma -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                        <i class="fas fa-user-check fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Alunos Aprovados</div>
                        <h4 class="mb-0 text-success font-weight-bold">{{ $stats['approved'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-danger">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-danger-subtle text-danger rounded-circle p-3 me-3">
                        <i class="fas fa-user-times fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Alunos Retidos</div>
                        <h4 class="mb-0 text-danger font-weight-bold">{{ $stats['failed'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                        <i class="fas fa-chart-pie fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Taxa de Sucesso</div>
                        <h4 class="mb-0 text-primary font-weight-bold">
                            {{ $stats['total_students'] > 0 ? round(($stats['approved'] / $stats['total_students']) * 100, 1) : 0 }}%
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela Matriz da Pauta Final -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark font-weight-bold">
                <i class="fas fa-graduation-cap text-success me-2"></i> Pauta Final de Frequência & Exames - {{ $year }}
            </h5>
            <span class="badge bg-light text-dark border">Total: {{ count($matrix) }} Alunos</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.88rem;">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2" class="align-middle text-start" style="min-width: 220px;">Nome do Aluno</th>
                            @foreach($subjects as $subject)
                                <th colspan="3" class="text-center border-bottom-0">{{ $subject->code ?? $subject->name }}</th>
                            @endforeach
                            <th rowspan="2" class="align-middle bg-primary text-white">Média Geral</th>
                            <th rowspan="2" class="align-middle">Resultado Final</th>
                        </tr>
                        <tr>
                            @foreach($subjects as $subject)
                                <th class="small fw-normal bg-secondary">MF</th>
                                <th class="small fw-normal bg-secondary">EX</th>
                                <th class="small fw-bold bg-dark text-warning">MFD</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matrix as $item)
                            <tr>
                                <td class="text-start font-weight-bold text-dark">
                                    {{ $item['student']->first_name }} {{ $item['student']->last_name }}
                                    <div class="text-muted small" style="font-size: 0.75rem;">N.º {{ $item['student']->student_number }}</div>
                                </td>
                                @foreach($subjects as $subject)
                                    @php
                                        $subData = $item['subjects'][$subject->id] ?? ['mf' => '-', 'exam' => '-', 'mfd' => '-'];
                                        $mfdVal = is_numeric($subData['mfd']) ? (float)$subData['mfd'] : null;
                                    @endphp
                                    <td class="text-muted">{{ $subData['mf'] }}</td>
                                    <td class="text-muted">{{ $subData['exam'] }}</td>
                                    <td class="fw-bold {{ $mfdVal !== null ? ($mfdVal >= 10 ? 'text-success bg-success-subtle' : 'text-danger bg-danger-subtle') : '' }}">
                                        {{ $subData['mfd'] }}
                                    </td>
                                @endforeach
                                <td class="fw-bold bg-light text-primary">
                                    {{ $item['overall_average'] > 0 ? number_format($item['overall_average'], 1) : '-' }}
                                </td>
                                <td>
                                    @if($item['final_status'] === 'Aprovado')
                                        <span class="badge bg-success px-3 py-2">APROVADO</span>
                                    @elseif($item['final_status'] === 'Retido')
                                        <span class="badge bg-danger px-3 py-2">RETIDO</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">PENDENTE</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($subjects) * 3 + 3 }}" class="py-5 text-center text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Nenhum registo de pauta final disponível para esta turma.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
