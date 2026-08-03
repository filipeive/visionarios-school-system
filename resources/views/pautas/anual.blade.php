@extends('layouts.app')

@section('title', 'Pauta Anual Consolidada - ' . $class->name)

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
            <h1 class="h3 font-weight-bold text-dark mb-0">Pauta Anual Consolidada: {{ $class->name }}</h1>
            <p class="text-muted small mb-0">Consolidação dos três trimestres (MT1, MT2, MT3) e Média Frequência (MF).</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('pautas.trimestral', $class->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-list-alt me-1"></i> Trimestral
            </a>
            <a href="{{ route('pautas.final', $class->id) }}" class="btn btn-outline-warning btn-sm text-dark">
                <i class="fas fa-graduation-cap me-1"></i> Pauta Final & Exames
            </a>
            <a href="{{ route('pautas.pdf', ['class' => $class->id, 'type' => 'anual']) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Exportar PDF
            </a>
        </div>
    </div>

    <!-- Tabela Matriz da Pauta Anual -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark font-weight-bold">
                <i class="fas fa-calendar-check text-primary me-2"></i> Pauta Anual Consolidada - Ano Lectivo {{ $year }}
            </h5>
            <span class="badge bg-light text-dark border">Total: {{ count($matrix) }} Alunos</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.88rem;">
                    <thead class="table-primary text-dark">
                        <tr>
                            <th rowspan="2" class="align-middle text-start" style="min-width: 220px;">Nome do Aluno</th>
                            @foreach($subjects as $subject)
                                <th colspan="4" class="text-center border-bottom-0">{{ $subject->code ?? $subject->name }}</th>
                            @endforeach
                            <th rowspan="2" class="align-middle bg-dark text-white">Média Geral</th>
                        </tr>
                        <tr>
                            @foreach($subjects as $subject)
                                <th class="small fw-normal">MT1</th>
                                <th class="small fw-normal">MT2</th>
                                <th class="small fw-normal">MT3</th>
                                <th class="small fw-bold bg-warning text-dark">MF</th>
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
                                        $subData = $item['subjects'][$subject->id] ?? ['mt1' => '-', 'mt2' => '-', 'mt3' => '-', 'mf' => '-'];
                                        $mfVal = is_numeric($subData['mf']) ? (float)$subData['mf'] : null;
                                    @endphp
                                    <td class="text-muted">{{ $subData['mt1'] }}</td>
                                    <td class="text-muted">{{ $subData['mt2'] }}</td>
                                    <td class="text-muted">{{ $subData['mt3'] }}</td>
                                    <td class="fw-bold {{ $mfVal !== null ? ($mfVal >= 10 ? 'text-success bg-success-subtle' : 'text-danger bg-danger-subtle') : '' }}">
                                        {{ $subData['mf'] }}
                                    </td>
                                @endforeach
                                <td class="fw-bold bg-dark text-white">
                                    {{ $item['overall_average'] > 0 ? number_format($item['overall_average'], 1) : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($subjects) * 4 + 2 }}" class="py-5 text-center text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Nenhum registo de notas encontrado para o ano lectivo {{ $year }}.</p>
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
