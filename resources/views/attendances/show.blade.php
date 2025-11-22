@extends('layouts.app')

@section('title', 'Detalhes da Presença')
@section('page-title', 'Detalhes da Presença')

@php
    $titleIcon = 'fas fa-eye';
@endphp

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Presenças</a></li>
    <li class="breadcrumb-item active">Detalhes</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-calendar-check"></i>
                    Registo de {{ $attendance->student->full_name }}
                </div>
                @can('mark_attendances')
                    <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endcan
            </div>
            <div class="school-card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ $attendance->student->photo_url }}" 
                             alt="{{ $attendance->student->full_name }}" 
                             class="img-fluid rounded-circle mb-3" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <h5 class="mb-0">{{ $attendance->student->first_name }}</h5>
                        <p class="text-muted">{{ $attendance->student->student_number }}</p>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;">Data da Presença</th>
                                    <td>{{ $attendance->attendance_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @php
                                            $statusColors = ['present' => 'success', 'absent' => 'danger', 'late' => 'warning', 'excused' => 'info'];
                                            $statusNames = ['present' => 'Presente', 'absent' => 'Ausente', 'late' => 'Atrasado', 'excused' => 'Justificado'];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$attendance->status] ?? 'secondary' }}">
                                            {{ $statusNames[$attendance->status] ?? 'N/D' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Turma</th>
                                    <td>
                                        <a href="{{ route('classes.show', $attendance->class) }}">{{ $attendance->class->name }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Hora de Chegada</th>
                                    <td>{{ $attendance->arrival_time ? $attendance->arrival_time->format('H:i:s') : 'Não registrada' }}</td>
                                </tr>
                                <tr>
                                    <th>Marcado Por</th>
                                    <td>{{ $attendance->markedBy->name ?? 'Sistema' }}</td>
                                </tr>
                                <tr>
                                    <th>Notas</th>
                                    <td>{{ $attendance->notes ?? 'Nenhuma nota.' }}</td>
                                </tr>
                                <tr>
                                    <th>Registado em</th>
                                    <td>{{ $attendance->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ url()->previous() }}" class="btn-school btn-secondary-school"><i class="fas fa-arrow-left"></i> Voltar</a>
            </div>
        </div>
    </div>
</div>
@endsection