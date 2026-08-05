@extends('layouts.app')

@section('title', 'Marcar Frequências - ' . $class->name)
@section('page-title', 'Marcar Frequências')
@section('page-title-icon', 'fas fa-calendar-plus')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Frequências</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendances.class-attendances', $class) }}?date={{ $date }}">{{ $class->name }}</a></li>
    <li class="breadcrumb-item active">Marcar</li>
@endsection

@php
    $titleIcon = 'fas fa-calendar-plus';
@endphp

@section('content')
<form action="{{ route('attendances.store-mark-by-class', $class) }}" method="POST" id="attendance-form">
    @csrf
    
    <div class="row">
        <!-- Configurações -->
        <div class="col-lg-4">
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-cog"></i>
                    Configurações
                </div>
                <div class="school-card-body">
                    <div class="mb-3">
                        <label class="form-label">Turma</label>
                        <input type="text" class="form-control" value="{{ $class->name }} ({{ $class->grade_level_name }})" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data <span class="text-danger">*</span></label>
                        <input type="date" name="attendance_date" class="form-control" 
                               value="{{ $date }}" required max="{{ date('Y-m-d') }}">
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Selecione o status de frequência para cada aluno</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="markAllPresent()">
                            <i class="fas fa-check-double"></i> Todos Presentes
                        </button>
                        <button type="button" class="btn btn-danger" onclick="markAllAbsent()">
                            <i class="fas fa-times-circle"></i> Todos Ausentes
                        </button>
                    </div>
                </div>
            </div>

            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-chart-pie"></i>
                    Resumo
                </div>
                <div class="school-card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Presentes:</span>
                        <strong class="text-success" id="count-present">0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ausentes:</span>
                        <strong class="text-danger" id="count-absent">0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Atrasados:</span>
                        <strong class="text-warning" id="count-late">0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Justificados:</span>
                        <strong class="text-info" id="count-excused">0</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total:</span>
                        <strong id="count-total">0</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Alunos -->
        <div class="col-lg-8">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-users"></i>
                    Lista de Alunos
                </div>
                <div class="school-card-body">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Aluno</th>
                                        <th>Nº</th>
                                        <th>Status Anterior</th>
                                        <th>Presente</th>
                                        <th>Ausente</th>
                                        <th>Atrasado</th>
                                        <th>Justificado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        @php
                                            $existingStatus = $existingAttendances[$student->id] ?? null;
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
                                            <td>
                                                @if($existingStatus)
                                                    <span class="badge bg-{{ $statusColors[$existingStatus] ?? 'secondary' }}">
                                                        {{ ucfirst($existingStatus) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" 
                                                       id="present_{{ $student->id }}" value="present" 
                                                       {{ $existingStatus == 'present' ? 'checked' : '' }} onchange="updateCounts()">
                                                <label class="btn btn-outline-success btn-sm" for="present_{{ $student->id }}">
                                                    <i class="fas fa-check"></i>
                                                </label>
                                            </td>
                                            <td>
                                                <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" 
                                                       id="absent_{{ $student->id }}" value="absent" 
                                                       {{ $existingStatus == 'absent' ? 'checked' : '' }} onchange="updateCounts()">
                                                <label class="btn btn-outline-danger btn-sm" for="absent_{{ $student->id }}">
                                                    <i class="fas fa-times"></i>
                                                </label>
                                            </td>
                                            <td>
                                                <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" 
                                                       id="late_{{ $student->id }}" value="late" 
                                                       {{ $existingStatus == 'late' ? 'checked' : '' }} onchange="updateCounts()">
                                                <label class="btn btn-outline-warning btn-sm" for="late_{{ $student->id }}">
                                                    <i class="fas fa-clock"></i>
                                                </label>
                                            </td>
                                            <td>
                                                <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" 
                                                       id="excused_{{ $student->id }}" value="excused" 
                                                       {{ $existingStatus == 'excused' ? 'checked' : '' }} onchange="updateCounts()">
                                                <label class="btn btn-outline-info btn-sm" for="excused_{{ $student->id }}">
                                                    <i class="fas fa-comment"></i>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-grid">
                            <button type="submit" class="btn-school btn-primary-school btn-lg">
                                <i class="fas fa-save"></i>
                                Salvar Frequências
                            </button>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>Nenhum aluno matriculado nesta turma.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function markAllPresent() {
    document.querySelectorAll('input[type="radio"][value="present"]').forEach(radio => {
        radio.checked = true;
    });
    updateCounts();
}

function markAllAbsent() {
    document.querySelectorAll('input[type="radio"][value="absent"]').forEach(radio => {
        radio.checked = true;
    });
    updateCounts();
}

function updateCounts() {
    const present = document.querySelectorAll('input[type="radio"][value="present"]:checked').length;
    const absent = document.querySelectorAll('input[type="radio"][value="absent"]:checked').length;
    const late = document.querySelectorAll('input[type="radio"][value="late"]:checked').length;
    const excused = document.querySelectorAll('input[type="radio"][value="excused"]:checked').length;
    const total = present + absent + late + excused;

    document.getElementById('count-present').textContent = present;
    document.getElementById('count-absent').textContent = absent;
    document.getElementById('count-late').textContent = late;
    document.getElementById('count-excused').textContent = excused;
    document.getElementById('count-total').textContent = total;
}

document.addEventListener('DOMContentLoaded', function() {
    updateCounts();
});
</script>
@endpush
