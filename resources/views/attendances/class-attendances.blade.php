@extends('layouts.app')

@section('title', 'Frequências - ' . $class->name)
@section('page-title', 'Frequências da Turma')
@section('page-title-icon', 'fas fa-calendar-check')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Frequências</a></li>
    <li class="breadcrumb-item active">{{ $class->name }}</li>
@endsection

@php
    $titleIcon = 'fas fa-calendar-check';
@endphp

@section('content')
<div class="row">
    <!-- Informações da Turma -->
    <div class="col-md-4 mb-4">
        <div class="school-card">
            {{-- Header com Botao de Voltar --}}
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-info-circle"></i>
                    Informações da Turma
                </div>
                <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-warning d-flex align-items-center">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
            <div class="school-card-body">
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
                    @can('mark_attendances')
                        <a href="{{ route('attendances.mark-by-class', $class) }}?date={{ $selectedDate }}" class="btn btn-success">
                            <i class="fas fa-edit"></i> Marcar Frequências
                        </a>
                    @endcan
                    <a href="{{ route('attendances.class-report', $class) }}" class="btn btn-warning">
                        <i class="fas fa-chart-bar"></i> Ver Relatório
                    </a>
                </div>
            </div>
        </div>

        <!-- Estatísticas do Dia -->
        <div class="school-card mt-3">
            <div class="school-card-header">
                <i class="fas fa-chart-pie"></i>
                Frequência do Dia
            </div>
            <div class="school-card-body">
                @php
                    $total = $students->count();
                    $present = $students->sum(fn($s) => ($s['attendance_status'] ?? null) === 'present' ? 1 : 0);
                    $absent = $students->sum(fn($s) => ($s['attendance_status'] ?? null) === 'absent' ? 1 : 0);
                    $late = $students->sum(fn($s) => ($s['attendance_status'] ?? null) === 'late' ? 1 : 0);
                    $excused = $students->sum(fn($s) => ($s['attendance_status'] ?? null) === 'excused' ? 1 : 0);
                    $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                @endphp
                
                <div class="text-center mb-3">
                    <h3 class="text-{{ $rate >= 90 ? 'success' : ($rate >= 75 ? 'warning' : 'danger') }}">
                        {{ $rate }}%
                    </h3>
                    <small class="text-muted">Taxa de Presença</small>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="d-flex justify-content-between">
                            <small>Presentes:</small>
                            <strong class="text-success">{{ $present }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-between">
                            <small>Ausentes:</small>
                            <strong class="text-danger">{{ $absent }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-between">
                            <small>Atrasados:</small>
                            <strong class="text-warning">{{ $late }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-between">
                            <small>Justificados:</small>
                            <strong class="text-info">{{ $excused }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Alunos e Frequências -->
    <div class="col-md-8">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users"></i>
                    Lista de Alunos
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <label class="form-label mb-0 me-2">Data:</label>
                    <input type="date" class="form-control form-control-sm" value="{{ $selectedDate }}" 
                           onchange="window.location.href='{{ route('attendances.class-attendances', $class) }}?date=' + this.value" 
                           style="width: auto;">
                </div>
            </div>
            <div class="school-card-body">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-school table-hover">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Nº</th>
                                    <th>Status</th>
                                    <th>Hora Chegada</th>
                                    <th>Marcado Por</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $item)
                                    @php
                                        $student = $item['student'];
                                        $attendance = $item['attendance'];
                                        $status = $item['attendance_status'];
                                        $arrivalTime = $item['arrival_time'];
                                        $markedBy = $item['marked_by'];
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
                                    <tr>
                                        <td>
                                            <strong>{{ $student->full_name }}</strong>
                                        </td>
                                        <td>{{ $student->student_number }}</td>
                                        <td>
                                            @if($status)
                                                <span class="badge bg-{{ $statusColors[$status] }}">
                                                    <i class="fas fa-{{ $statusIcons[$status] }} me-1"></i>
                                                    {{ $statusNames[$status] }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Não marcado</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($arrivalTime)
                                                {{ $arrivalTime->format('H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($markedBy)
                                                <small class="text-muted">{{ $markedBy->name }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
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
