@extends('layouts.app')

@section('title', 'Relatório de Frequências - ' . $class->name)
@section('page-title', 'Relatório de Frequências')
@section('page-title-icon', 'fas fa-chart-bar')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Frequências</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendances.class-attendances', $class) }}">{{ $class->name }}</a></li>
    <li class="breadcrumb-item active">Relatório</li>
@endsection

@php
    $titleIcon = 'fas fa-chart-bar';
@endphp

@section('content')
<div class="row">
    <!-- Informações da Turma -->
    <div class="col-md-4 mb-4">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-info-circle"></i>
                Informações da Turma
            </div>
            <div class="school-card-body">
                <div class="mb-3">
                    <strong>Nome:</strong> {{ $class->name }}
                </div>
                <div class="mb-3">
                    <strong>Nível:</strong> 
                    <span class="badge bg-primary">{{ $class->grade_level_name }}</span>
                </div>
                <div class="mb-3">
                    <strong>Turno:</strong> 
                    @php
                        $shiftBadge = match($class->shift) {
                            'afternoon' => 'bg-warning text-dark',
                            'night' => 'bg-info',
                            default => 'bg-primary',
                        };
                    @endphp
                    <span class="badge {{ $shiftBadge }}">{{ $class->shift_label }}</span>
                </div>
                <div class="mb-3">
                    <strong>Total Alunos:</strong> {{ $students->count() }}
                </div>
                <div class="mb-3">
                    <strong>Professor:</strong>
                    @if($class->teacher)
                        {{ $class->teacher->first_name }} {{ $class->teacher->last_name }}
                    @else
                        <span class="text-muted">Não atribuído</span>
                    @endif
                </div>

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('attendances.class-attendances', $class) }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> Ver Frequências
                    </a>
                    @can('mark_attendances')
                        <a href="{{ route('attendances.mark-by-class', $class) }}" class="btn btn-success">
                            <i class="fas fa-edit"></i> Marcar Frequências
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Resumo Mensal -->
        <div class="school-card mt-3">
            <div class="school-card-header">
                <i class="fas fa-chart-pie"></i>
                Resumo Mensal
            </div>
            <div class="school-card-body">
                @php
                    $totalDays = $students->sum('total_days');
                    $totalPresent = $students->sum('present');
                    $totalAbsent = $students->sum('absent');
                    $totalLate = $students->sum('late');
                    $totalExcused = $students->sum('excused');
                    $avgRate = $students->count() > 0 ? round($students->avg('attendance_rate'), 1) : 0;
                @endphp
                
                <div class="text-center mb-3">
                    <h3 class="text-{{ $avgRate >= 90 ? 'success' : ($avgRate >= 75 ? 'warning' : 'danger') }}">
                        {{ $avgRate }}%
                    </h3>
                    <small class="text-muted">Média de Presença</small>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="d-flex justify-content-between">
                            <small>Total Dias:</small>
                            <strong>{{ $totalDays }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-between">
                            <small>Presentes:</small>
                            <strong class="text-success">{{ $totalPresent }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-between">
                            <small>Ausentes:</small>
                            <strong class="text-danger">{{ $totalAbsent }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-between">
                            <small>Atrasados:</small>
                            <strong class="text-warning">{{ $totalLate }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-between">
                            <small>Justificados:</small>
                            <strong class="text-info">{{ $totalExcused }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Relatório por Aluno -->
    <div class="col-md-8">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-users"></i>
                Relatório Individual
            </div>
            <div class="school-card-body">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-school table-hover">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Nº</th>
                                    <th>Dias</th>
                                    <th>Presentes</th>
                                    <th>Ausentes</th>
                                    <th>Atrasados</th>
                                    <th>Justificados</th>
                                    <th>Taxa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $item)
                                    @php
                                        $student = $item['student'];
                                        $statusColors = [
                                            'present' => 'success',
                                            'absent' => 'danger',
                                            'late' => 'warning',
                                            'excused' => 'info'
                                        ];
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $student->full_name }}</strong>
                                        </td>
                                        <td>{{ $student->student_number }}</td>
                                        <td>{{ $item['total_days'] }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $item['present'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">{{ $item['absent'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $item['late'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $item['excused'] }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $rate = $item['attendance_rate'];
                                                $rateColor = $rate >= 90 ? 'success' : ($rate >= 75 ? 'warning' : 'danger');
                                            @endphp
                                            <span class="badge bg-{{ $rateColor }}">{{ $rate }}%</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum aluno matriculado nesta turma.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
