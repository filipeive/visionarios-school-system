@extends('layouts.app')

@section('title', 'Pauta Trimestral - ' . $class->name)

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
            <h1 class="h3 font-weight-bold text-dark mb-0">Pauta Trimestral: {{ $class->name }}</h1>
            <p class="text-muted small mb-0">Visão consolidada de avaliações contínuas (ACS), parciais (ACP) e média trimestral (MT).</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Filtro de Trimestre -->
            <form method="GET" action="{{ route('pautas.trimestral', $class->id) }}" class="d-flex align-items-center gap-2">
                <select name="term" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="1" {{ $term == 1 ? 'selected' : '' }}>1º Trimestre</option>
                    <option value="2" {{ $term == 2 ? 'selected' : '' }}>2º Trimestre</option>
                    <option value="3" {{ $term == 3 ? 'selected' : '' }}>3º Trimestre</option>
                </select>
            </form>

            <a href="{{ route('pautas.anual', $class->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-calendar-alt me-1"></i> Pauta Anual
            </a>

            <a href="{{ route('pautas.pdf', ['class' => $class->id, 'type' => 'trimestral', 'term' => $term]) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Exportar PDF
            </a>
        </div>
    </div>

    <!-- Tabela Matriz da Pauta -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark font-weight-bold">
                <i class="fas fa-table text-success me-2"></i> Pauta do {{ $term }}º Trimestre - Ano Lectivo {{ $year }}
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
                            <th rowspan="2" class="align-middle">Situação</th>
                        </tr>
                        <tr>
                            @foreach($subjects as $subject)
                                <th class="small fw-normal bg-secondary">ACS</th>
                                <th class="small fw-normal bg-secondary">ACP</th>
                                <th class="small fw-bold bg-dark text-warning">MT</th>
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
                                        $subData = $item['subjects'][$subject->id] ?? ['acs' => '-', 'acp' => '-', 'mt' => '-'];
                                        $mtVal = is_numeric($subData['mt']) ? (float)$subData['mt'] : null;
                                    @endphp
                                    <td class="text-muted">{{ $subData['acs'] }}</td>
                                    <td class="text-muted">{{ $subData['acp'] }}</td>
                                    <td class="fw-bold {{ $mtVal !== null ? ($mtVal >= 10 ? 'text-success bg-success-subtle' : 'text-danger bg-danger-subtle') : '' }}">
                                        {{ $subData['mt'] }}
                                    </td>
                                @endforeach
                                <td class="fw-bold bg-light text-primary">
                                    {{ $item['overall_average'] > 0 ? number_format($item['overall_average'], 1) : '-' }}
                                </td>
                                <td>
                                    @if($item['final_status'] === 'Aprovado')
                                        <span class="badge bg-success">Positivo</span>
                                    @elseif($item['final_status'] === 'Retido')
                                        <span class="badge bg-danger">Atenção</span>
                                    @else
                                        <span class="badge bg-secondary">Em Curso</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($subjects) * 3 + 3 }}" class="py-5 text-center text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Nenhuma nota registada para esta turma no {{ $term }}º Trimestre.</p>
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
