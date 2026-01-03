@extends('layouts.app')

@section('title', 'Alunos da Turma')
@section('page-title', 'Alunos — ' . ($class->name ?? 'Turma'))
@section('page-title-icon', 'fas fa-users')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Portal</a></li>
    <li class="breadcrumb-item"><a href="{{ route('teacher.classes.index') }}">Minhas Turmas</a></li>
    <li class="breadcrumb-item active">Alunos</li>
@endsection

@section('content')
    <!-- Adicionar Aluno -->
    <div class="school-card mb-4">
        <div class="school-card-header">
            <i class="fas fa-user-plus"></i>
            Adicionar Aluno à Turma
        </div>
        <div class="school-card-body">
            @if($availableStudents->count() > 0)
                <form action="{{ route('teacher.classes.add-student', $class->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Selecionar Aluno *</label>
                                <select class="form-select @error('student_id') is-invalid @enderror" id="student_id"
                                    name="student_id" required>
                                    <option value="">Selecione um aluno...</option>
                                    @foreach ($availableStudents as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->first_name }} {{ $student->last_name }}
                                            ({{ $student->student_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="monthly_fee" class="form-label">Mensalidade *</label>
                                <div class="input-group">
                                    <span class="input-group-text">MT</span>
                                    <input type="number" step="0.01"
                                        class="form-control @error('monthly_fee') is-invalid @enderror" id="monthly_fee"
                                        name="monthly_fee" value="{{ old('monthly_fee', 1500) }}" min="0" required>
                                </div>
                                @error('monthly_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary-school w-100">
                                    <i class="fas fa-plus"></i> Adicionar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Informação:</strong> Todos os alunos disponíveis já estão matriculados em turmas deste ano letivo.
                </div>
            @endif
        </div>
    </div>

    <!-- Lista de Alunos -->
    <div class="school-card">
        <div class="school-card-header">
            <i class="fas fa-users"></i>
            Alunos — {{ $class->name }}
            <span class="badge bg-secondary ms-2">{{ $students->count() }} alunos</span>
        </div>
        <div class="school-card-body">
            @if($students->isEmpty())
                <p class="text-muted text-center py-4">
                    <i class="fas fa-user-graduate fa-2x mb-2 d-block"></i>
                    Nenhum aluno nesta turma.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Nº</th>
                                <th>Classe</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">
                                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                <small class="text-muted">{{ $student->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->student_number ?? '—' }}</td>
                                    <td>{{ $class->name }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('teacher.attendance.class', $class->id) }}"
                                            class="btn btn-sm btn-outline-success" title="Presenças">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                        <a href="{{ route('teacher.gradebook', $class->id) }}?student_id={{ $student->id }}"
                                            class="btn btn-sm btn-outline-warning" title="Notas">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <style>
        .user-avatar-sm {
            width: 35px;
            height: 35px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
@endsection