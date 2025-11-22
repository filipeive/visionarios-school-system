@extends('layouts.app')

@section('title', 'Marcar Presenças')
@section('page-title', 'Marcar Presenças')

@php
    $titleIcon = 'fas fa-calendar-plus';
@endphp

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Presenças</a></li>
    <li class="breadcrumb-item active">Marcar</li>
@endsection

@section('content')
<form action="{{ route('attendances.store-mark') }}" method="POST" id="attendance-form">
    @csrf
    
    <div class="row">
        <!-- Seleção de Turma e Data -->
        <div class="col-lg-4">
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-cog"></i>
                    Configurações
                </div>
                <div class="school-card-body">
                    <div class="mb-3">
                        <label class="form-label">Turma <span class="text-danger">*</span></label>
                        <select name="class_id" id="class_id" class="form-select" required onchange="loadStudents()">
                            <option value="">Selecione uma turma...</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->current_students }} alunos)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data <span class="text-danger">*</span></label>
                        <input type="date" name="attendance_date" class="form-control" 
                               value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}">
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Selecione uma turma para carregar a lista de alunos</small>
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
                    <div id="students-list">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-users fs-1 mb-3 d-block"></i>
                            <p>Selecione uma turma para ver os alunos</p>
                        </div>
                    </div>

                    <div id="students-attendance" style="display: none;">
                        <!-- Será preenchido via JavaScript -->
                    </div>

                    <div class="mt-4 d-grid">
                        <button type="submit" class="btn-school btn-primary-school btn-lg">
                            <i class="fas fa-save"></i>
                            Salvar Presenças
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function loadStudents() {
    const classId = document.getElementById('class_id').value;
    
    if (!classId) {
        document.getElementById('students-list').style.display = 'block';
        document.getElementById('students-attendance').style.display = 'none';
        return;
    }

    // Fazer requisição AJAX para carregar alunos
    fetch(`/api/classes/${classId}/students`)
        .then(response => response.json())
        .then(students => {
            const container = document.getElementById('students-attendance');
            container.innerHTML = '';

            students.forEach(student => {
                const studentCard = `
                    <div class="card mb-2">
                        <div class="card-body d-flex align-items-center">
                            <img src="${student.photo_url}" class="rounded-circle me-3" 
                                 style="width: 40px; height: 40px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <strong>${student.full_name}</strong>
                                <br><small class="text-muted">${student.student_number}</small>
                            </div>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="attendance[${student.id}]" 
                                       id="present_${student.id}" value="present" onchange="updateCounts()">
                                <label class="btn btn-outline-success" for="present_${student.id}">
                                    <i class="fas fa-check"></i> Presente
                                </label>

                                <input type="radio" class="btn-check" name="attendance[${student.id}]" 
                                       id="absent_${student.id}" value="absent" onchange="updateCounts()">
                                <label class="btn btn-outline-danger" for="absent_${student.id}">
                                    <i class="fas fa-times"></i> Ausente
                                </label>

                                <input type="radio" class="btn-check" name="attendance[${student.id}]" 
                                       id="late_${student.id}" value="late" onchange="updateCounts()">
                                <label class="btn btn-outline-warning" for="late_${student.id}">
                                    <i class="fas fa-clock"></i> Atrasado
                                </label>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', studentCard);
            });

            document.getElementById('students-list').style.display = 'none';
            document.getElementById('students-attendance').style.display = 'block';
            updateCounts();
        })
        .catch(error => {
            console.error('Erro ao carregar alunos:', error);
            alert('Erro ao carregar alunos. Tente novamente.');
        });
}

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
    const total = document.querySelectorAll('input[type="radio"]:checked').length;

    document.getElementById('count-present').textContent = present;
    document.getElementById('count-absent').textContent = absent;
    document.getElementById('count-late').textContent = late;
    document.getElementById('count-total').textContent = total;
}
</script>
@endpush