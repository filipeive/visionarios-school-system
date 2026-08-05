@extends('layouts.app')

@section('title', 'Gestão de Frequências')
@section('page-title', 'Gestão de Frequências')
@section('page-title-icon', 'fas fa-calendar-check')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item active">Frequências</li>
@endsection

@php
    $titleIcon = 'fas fa-calendar-check';
@endphp

@section('content')
<!-- Estatísticas Globais -->
<div class="school-stats mb-4">
    {{--  <div class="stat-card students">
        <div class="stat-icon students">
            <i class="fas fa-chalkboard"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $globalStats['total_classes'] }}</div>
            <div class="stat-label">Turmas Ativas</div>
        </div>
    </div> --}}

    <div class="stat-card teachers">
        <div class="stat-icon teachers">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $globalStats['total_students'] }}</div>
            <div class="stat-label">Total Alunos</div>
        </div>
    </div>

    <div class="stat-card payments">
        <div class="stat-icon payments">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $globalStats['total_present'] }}</div>
            <div class="stat-label">Presentes Hoje</div>
        </div>
    </div>

    <div class="stat-card events">
        <div class="stat-icon events" style="background: linear-gradient(135deg, #F44336, #C62828);">
            <i class="fas fa-user-times"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $globalStats['total_absent'] }}</div>
            <div class="stat-label">Ausentes Hoje</div>
        </div>
    </div>

    <div class="stat-card teachers">
        <div class="stat-icon teachers">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $globalStats['total_late'] }}</div>
            <div class="stat-label">Atrasados Hoje</div>
        </div>
    </div>

    <div class="stat-card students">
        <div class="stat-icon students">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $globalStats['total_unmarked'] }}</div>
            <div class="stat-label">Por Marcar</div>
        </div>
    </div>
</div>

<!-- Filtro de Data e Ações -->
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-body">
                <form action="{{ route('attendances.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fas fa-calendar me-2"></i>Data
                        </label>
                        <input type="date" name="date" class="form-control" value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="{{ route('attendances.reports') }}" class="btn btn-warning">
                                <i class="fas fa-chart-bar"></i> Relatórios
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Turmas e suas Frequências -->
<div class="row g-3">
    @forelse($classes as $classData)
        @php
            $class = $classData['class'];
            $attendanceRate = $classData['attendance_rate'];
            $rateColor = $attendanceRate >= 90 ? 'success' : ($attendanceRate >= 75 ? 'warning' : 'danger');
        @endphp
        <div class="col-md-6 col-xl-4">
            <div class="school-card h-100 hover-shadow transition">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $class->name }}</h5>
                        <small class="text-muted">{{ $class->grade_level_name }} • {{ $class->shift_label }}</small>
                    </div>
                    <span class="badge bg-{{ $rateColor }}">
                        {{ $attendanceRate }}%
                    </span>
                </div>
                <div class="school-card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Presentes:</small>
                                <strong class="text-success">{{ $classData['present'] }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Ausentes:</small>
                                <strong class="text-danger">{{ $classData['absent'] }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Atrasados:</small>
                                <strong class="text-warning">{{ $classData['late'] }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Justificados:</small>
                                <strong class="text-info">{{ $classData['excused'] }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-{{ $rateColor }}" style="width: {{ $attendanceRate }}%"></div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('attendances.class-attendances', $class) }}?date={{ $selectedDate }}" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="fas fa-eye"></i> Ver Frequências
                        </a>
                        @can('mark_attendances')
                            <a href="{{ route('attendances.mark-by-class', $class) }}?date={{ $selectedDate }}" class="btn btn-success btn-sm flex-grow-1">
                                <i class="fas fa-edit"></i> Marcar
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nenhuma turma ativa encontrada para o ano letivo atual.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection
