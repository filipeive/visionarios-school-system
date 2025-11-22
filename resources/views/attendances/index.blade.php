@extends('layouts.app')

@section('title', 'Gestão de Presenças')
@section('page-title', 'Gestão de Presenças')

@php
    $titleIcon = 'fas fa-calendar-check';
@endphp

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item active">Presenças</li>
@endsection

@section('content')
<!-- Cards de Estatísticas -->
<div class="school-stats">
    <div class="stat-card students">
        <div class="stat-icon students">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
            <div class="stat-label">Total de Registos</div>
            <div class="stat-change positive">
                <i class="fas fa-check"></i>
                Todos os períodos
            </div>
        </div>
    </div>

    <div class="stat-card payments">
        <div class="stat-icon payments">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['today_present']) }}</div>
            <div class="stat-label">Presentes Hoje</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $stats['today_present'] > 0 ? round(($stats['today_present'] / ($stats['today_present'] + $stats['today_absent'])) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    <div class="stat-card events">
        <div class="stat-icon events" style="background: linear-gradient(135deg, #F44336, #C62828);">
            <i class="fas fa-user-times"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['today_absent']) }}</div>
            <div class="stat-label">Ausentes Hoje</div>
            <div class="stat-change {{ $stats['today_absent'] > 0 ? 'negative' : 'positive' }}">
                <i class="fas fa-{{ $stats['today_absent'] > 0 ? 'exclamation-triangle' : 'check' }}"></i>
                Requer atenção
            </div>
        </div>
    </div>

    <div class="stat-card teachers">
        <div class="stat-icon teachers">
            <i class="fas fa-calendar-plus"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ now()->format('d/m') }}</div>
            <div class="stat-label">Data de Hoje</div>
            <div class="stat-change positive">
                <i class="fas fa-clock"></i>
                {{ now()->format('H:i') }}
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-filter"></i>
                Filtrar Presenças
            </div>
            <div class="school-card-body">
                <form action="{{ route('attendances.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-search me-2"></i>Pesquisar Aluno
                            </label>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ request('search') }}" 
                                   placeholder="Nome ou número do aluno...">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-chalkboard me-2"></i>Turma
                            </label>
                            <select name="class_id" class="form-select">
                                <option value="">Todas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" 
                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-check-circle me-2"></i>Status
                            </label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Presente</option>
                                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Ausente</option>
                                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Atrasado</option>
                                <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Justificado</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-calendar me-2"></i>De
                            </label>
                            <input type="date" name="date_from" class="form-control" 
                                   value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-calendar me-2"></i>Até
                            </label>
                            <input type="date" name="date_to" class="form-control" 
                                   value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn-school btn-primary-school w-100 d-flex justify-content-center">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mt-3">
                        @if(request()->hasAny(['search', 'class_id', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i> Limpar Filtros
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
@can('mark_attendances')
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Ações Rápidas</h5>
                        <p class="text-muted mb-0">Marcar presenças e gerar relatórios</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('attendances.mark') }}" class="btn-school btn-success-school">
                            <i class="fas fa-calendar-plus"></i>
                            Marcar Presenças
                        </a>
                        <a href="{{ route('attendances.reports') }}" class="btn-school btn-warning-school">
                            <i class="fas fa-chart-bar"></i>
                            Relatórios
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- Tabela de Presenças -->
<div class="row">
    <div class="col-12">
        <div class="school-table-container">
            <div class="school-table-header">
                <h5 class="school-table-title">
                    <i class="fas fa-list"></i>
                    Lista de Presenças
                </h5> 
                <span class="badge bg-light text-dark">{{ $attendances->total() }} registos</span>
            </div>

            <table class="table table-school table-hover">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Nº Aluno</th>
                        <th>Turma</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Status</th>
                        <th>Marcado Por</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>
                                <a href="{{ route('students.show', $attendance->student) }}" 
                                   class="text-decoration-none fw-semibold">
                                    {{ $attendance->student->full_name }}
                                </a>
                            </td>
                            <td>{{ $attendance->student->student_number }}</td>
                            <td>
                                <a href="{{ route('classes.show', $attendance->class) }}" 
                                   class="text-decoration-none">
                                    <span class="badge bg-primary">{{ $attendance->class->name }}</span>
                                </a>
                            </td>
                            <td>{{ $attendance->attendance_date->format('d/m/Y') }}</td>
                            <td>
                                @if($attendance->arrival_time)
                                    {{ $attendance->arrival_time->format('H:i') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'present' => 'success',
                                        'absent' => 'danger',
                                        'late' => 'warning',
                                        'excused' => 'info'
                                    ];
                                    $statusIcons = [
                                        'present' => 'check',
                                        'absent' => 'times',
                                        'late' => 'clock',
                                        'excused' => 'comment'
                                    ];
                                    $statusNames = [
                                        'present' => 'Presente',
                                        'absent' => 'Ausente',
                                        'late' => 'Atrasado',
                                        'excused' => 'Justificado'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$attendance->status] }}">
                                    <i class="fas fa-{{ $statusIcons[$attendance->status] }} me-1"></i>
                                    {{ $statusNames[$attendance->status] }}
                                </span>
                            </td>
                            <td>
                                @if($attendance->markedBy)
                                    <small class="text-muted">{{ $attendance->markedBy->name }}</small>
                                @else
                                    <span class="text-muted">Sistema</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-info"
                                       title="Ver Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('mark_attendances')
                                        <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-warning"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fs-3 mb-2 d-block"></i>
                                Nenhum registo de presença encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="p-3">
                {{ $attendances->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection