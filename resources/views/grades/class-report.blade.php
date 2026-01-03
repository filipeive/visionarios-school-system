@extends('layouts.app')

@section('title', 'Relatório de Notas - ' . $class->name)
@section('page-title', 'Relatório de Notas')
@section('page-title-icon', 'fas fa-chart-line')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Turmas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('classes.show', $class->id) }}">{{ $class->name }}</a></li>
    <li class="breadcrumb-item active">Relatório de Notas</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card mb-4">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <h3 class="school-card-title">
                        <i class="fas fa-file-alt"></i>
                        Relatório de Aproveitamento: {{ $class->name }} ({{ $currentYear }})
                    </h3>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary-school btn-sm" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                    </div>
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-school">
                            <thead class="bg-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Aluno</th>
                                    @foreach($class->subjects as $subject)
                                        <th colspan="3" class="text-center">{{ $subject->name }}</th>
                                    @endforeach
                                    <th rowspan="2" class="align-middle text-center">Média Geral</th>
                                </tr>
                                <tr>
                                    @foreach($class->subjects as $subject)
                                        <th class="text-center small">T1</th>
                                        <th class="text-center small">T2</th>
                                        <th class="text-center small">T3</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($class->students as $student)
                                    <tr>
                                        <td>
                                            <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                            <br><small class="text-muted">{{ $student->student_number }}</small>
                                        </td>
                                        @php $totalGrades = 0;
                                        $gradeCount = 0; @endphp
                                        @foreach($class->subjects as $subject)
                                            @foreach($terms as $term)
                                                @php
                                                    $grade = $student->grades
                                                        ->where('subject_id', $subject->id)
                                                        ->where('term', $term)
                                                        ->first();
                                                    $value = $grade ? $grade->value : null;
                                                    if ($value !== null) {
                                                        $totalGrades += $value;
                                                        $gradeCount++;
                                                    }
                                                @endphp
                                                <td class="text-center {{ $value !== null && $value < 10 ? 'text-danger' : '' }}">
                                                    {{ $value !== null ? number_format($value, 1) : '-' }}
                                                </td>
                                            @endforeach
                                        @endforeach
                                        <td class="text-center fw-bold">
                                            @if($gradeCount > 0)
                                                @php $avg = $totalGrades / $gradeCount; @endphp
                                                <span class="{{ $avg < 10 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($avg, 1) }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 1 + ($class->subjects->count() * 3) + 1 }}" class="text-center py-4">
                                            Nenhum aluno matriculado nesta turma.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn-group,
            .breadcrumb,
            .main-header,
            .main-sidebar {
                display: none !important;
            }

            .content-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            .school-card {
                border: none !important;
                box-shadow: none !important;
            }

            .table-school {
                font-size: 10px;
            }
        }
    </style>
@endsection