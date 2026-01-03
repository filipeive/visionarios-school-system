@extends('layouts.app')

@section('title', $student->full_name)
@section('page-title', $student->full_name)

@section('content')
    <div class="row mb-4">
        <!-- Informações Básicas -->
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-body">
                    <div class="row align-items-center">
                        <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                            @if($student->photo_url)
                                <img src="{{ $student->photo_url }}" alt="{{ $student->full_name }}"
                                    class="rounded-circle border border-3 border-light shadow-sm" width="120" height="120"
                                    style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-secondary border border-3 border-light shadow-sm"
                                    style="width: 120px; height: 120px;">
                                    <i class="fas fa-user fa-4x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md">
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-3">
                                    <label class="text-muted small text-uppercase fw-bold">Nome Completo</label>
                                    <div class="fw-bold fs-5">{{ $student->full_name }}</div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <label class="text-muted small text-uppercase fw-bold">Data de Nascimento</label>
                                    <div class="fw-medium">{{ $student->birthdate?->format('d/m/Y') ?? 'N/A' }}
                                        ({{ $student->age }}
                                        anos)</div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <label class="text-muted small text-uppercase fw-bold">Turma Atual</label>
                                    <div class="fw-medium">{{ $student->currentEnrollment->class->name ?? 'N/A' }}</div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <label class="text-muted small text-uppercase fw-bold">Número de Estudante</label>
                                    <div class="fw-medium font-monospace">{{ $student->student_number }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Presenças Recentes -->
        <div class="col-md-6 mb-4">
            <div class="school-card h-100">
                <div class="school-card-header">
                    <i class="fas fa-calendar-check me-2"></i> Presenças Recentes
                </div>
                <div class="school-card-body">
                    <div class="list-group list-group-flush">
                        @forelse($student->attendances()->latest()->take(5)->get() as $attendance)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>{{ $attendance->attendance_date?->format('d/m/Y') ?? 'N/A' }}</span>
                                @if($attendance->status == 'present')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Presente</span>
                                @elseif($attendance->status == 'absent')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Ausente</span>
                                @else
                                    <span
                                        class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ ucfirst($attendance->status) }}</span>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted">
                                Nenhum registro de presença.
                            </div>
                        @endforelse
                    </div>
                    {{--
                    <div class="mt-3 text-end">
                        <a href="#" class="btn btn-sm btn-link text-decoration-none">Ver todas</a>
                    </div>
                    --}}
                </div>
            </div>
        </div>

        <!-- Últimas Notas -->
        <div class="col-md-6 mb-4">
            <div class="school-card h-100">
                <div class="school-card-header">
                    <i class="fas fa-graduation-cap me-2"></i> Últimas Notas
                </div>
                <div class="school-card-body">
                    <div class="list-group list-group-flush">
                        @forelse($student->grades()->latest()->take(5)->get() as $grade)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <div class="fw-bold">{{ $grade->subject->name ?? 'Disciplina' }}</div>
                                    <small class="text-muted">{{ ucfirst($grade->assessment_type) }} - Trimestre
                                        {{ $grade->term }}</small>
                                </div>
                                <span class="fw-bold fs-5 {{ $grade->grade < 10 ? 'text-danger' : 'text-success' }}">
                                    {{ $grade->grade }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted">
                                Nenhuma nota registrada.
                            </div>
                        @endforelse
                    </div>
                    {{--
                    <div class="mt-3 text-end">
                        <a href="#" class="btn btn-sm btn-link text-decoration-none">Ver todas</a>
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </div>
@endsection