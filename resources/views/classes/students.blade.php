@extends('layouts.app')

@section('title', 'Alunos - ' . $class->name)
@section('page-title', 'Gestão de Alunos da Turma')
@section('page-title-icon', 'fas fa-users')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Turmas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('classes.show', $class->id) }}">{{ $class->name }}</a></li>
    <li class="breadcrumb-item active">Alunos</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Informações da Turma -->
            <div class="school-card mb-4">
                <div class="school-card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{ $class->name }}</h4>
                            <p class="text-muted mb-0">
                                <strong>Nível:</strong> {{ $class->grade_level_name }} |
                                <strong>Ano Letivo:</strong> {{ $class->school_year }} |
                                <strong>Professor:</strong>
                                {{ $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Não atribuído' }}
                                |
                                <strong>Alunos:</strong> {{ $students->count() }}/{{ $class->max_students }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('classes.show', $class->id) }}" class="btn btn-secondary-school">
                                <i class="fas fa-arrow-left"></i> Voltar à Turma
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Adicionar Aluno -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-user-plus"></i>
                        Adicionar Aluno à Turma
                    </h3>
                </div>
                <div class="school-card-body">
                    <form action="{{ route('classes.add-student', $class->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Selecionar Aluno *</label>
                                    <select class="form-select @error('student_id') is-invalid @enderror" id="student_id"
                                        name="student_id" required>
                                        <option value="">Selecione um aluno...</option>
                                        @foreach ($availableStudents as $student)
                                            <option value="{{ $student->id }}"
                                                {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->first_name }} {{ $student->last_name }}
                                                ({{ $student->student_number }})
                                                @if ($student->parent)
                                                    - Enc: {{ $student->parent->first_name }}
                                                @endif
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
                                            name="monthly_fee"
                                            value="{{ old('monthly_fee', $class->students->avg('monthly_fee') ?? 0) }}"
                                            min="0" required>
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

                    @if ($availableStudents->isEmpty())
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Informação:</strong> Todos os alunos disponíveis já estão matriculados em turmas deste
                            ano letivo.
                            <a href="{{ route('students.create') }}" class="alert-link">Cadastrar novo aluno</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lista de Alunos Matriculados -->
            <div class="school-table-container">
                <div class="school-table-header">
                    <h3 class="school-table-title">
                        <i class="fas fa-list"></i>
                        Alunos Matriculados ({{ $students->count() }})
                    </h3>
                    <div class="text-muted small">
                        Capacidade: {{ $students->count() }}/{{ $class->max_students }}
                        ({{ round(($students->count() / $class->max_students) * 100, 1) }}%)
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-school">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Número</th>
                                <th>Gênero</th>
                                <th>Idade</th>
                                <th>Data de Matrícula</th>
                                <th>Mensalidade</th>
                                <th>Enc. Educação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                @php
                                    $enrollment = $student->enrollments
                                        ->where('class_id', $class->id)
                                        ->where('status', 'active')
                                        ->first();
                                    $age = $student->birthdate ? $student->birthdate->age : 'N/A';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($student->passport_photo)
                                                <img src="{{ Storage::url($student->passport_photo) }}"
                                                    alt="{{ $student->first_name }}" class="rounded-circle me-3"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="user-avatar-sm me-3">
                                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                                @if ($student->has_special_needs)
                                                    <span class="badge bg-warning ms-1" title="Necessidades Especiais">
                                                        <i class="fas fa-wheelchair"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $student->student_number }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $student->gender == 'male' ? 'primary' : 'pink' }}">
                                            {{ $student->gender == 'male' ? '♂ Masculino' : '♀ Feminino' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $age }} anos
                                        @if ($student->birthdate)
                                            <br>
                                            <small class="text-muted">{{ $student->birthdate->format('d/m/Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($enrollment)
                                            {{ $enrollment->enrollment_date->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            {{ number_format($student->monthly_fee, 2, ',', '.') }} MT
                                        </strong>
                                    </td>
                                    <td>
                                        @if ($student->parent)
                                            <div>
                                                {{ $student->parent->first_name }} {{ $student->parent->last_name }}
                                                @if ($student->parent->phone)
                                                    <br>
                                                    <small class="text-muted">{{ $student->parent->phone }}</small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('students.show', $student->id) }}"
                                                class="btn btn-sm btn-primary-school" title="Ver Perfil">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form
                                                action="{{ route('classes.remove-student', [$class->id, $student->id]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    title="Remover da Turma"
                                                    onclick="return confirm('Tem certeza que deseja remover {{ $student->first_name }} da turma {{ $class->name }}?')">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Nenhum aluno matriculado nesta turma.</p>
                                        <p class="text-muted small">Use o formulário acima para adicionar alunos à turma.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Estatísticas da Turma -->
            @if ($students->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="school-card">
                            <div class="school-card-header">
                                <h3 class="school-card-title">
                                    <i class="fas fa-chart-pie"></i>
                                    Distribuição por Gênero
                                </h3>
                            </div>
                            <div class="school-card-body">
                                @php
                                    $total = $students->count();
                                @endphp
                                <canvas id="genderChart" width="400" height="200"></canvas>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-primary me-2">
                                        ♂ {{ $maleCount }}
                                        ({{ $total > 0 ? round(($maleCount / $total) * 100, 1) : 0 }}%)
                                    </span>
                                    <span class="badge bg-pink">
                                        ♀ {{ $femaleCount }}
                                        ({{ $total > 0 ? round(($femaleCount / $total) * 100, 1) : 0 }}%)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="school-card">
                            <div class="school-card-header">
                                <h3 class="school-card-title">
                                    <i class="fas fa-info-circle"></i>
                                    Resumo da Turma
                                </h3>
                            </div>
                            <div class="school-card-body">
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        Total de Alunos
                                        <span class="badge bg-primary rounded-pill">{{ $total }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        Vagas Disponíveis
                                        <span
                                            class="badge bg-{{ $class->max_students - $total > 0 ? 'success' : 'danger' }} rounded-pill">
                                            {{ $class->max_students - $total }}
                                        </span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        Alunos com Necessidades Especiais
                                        <span
                                            class="badge bg-warning rounded-pill">{{ $students->where('has_special_needs', true)->count() }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        Média de Idade
                                        <span class="badge bg-info rounded-pill">
                                            @php
                                                $ages = $students
                                                    ->filter(fn($s) => $s->birthdate)
                                                    ->map(fn($s) => $s->birthdate->age);
                                                $averageAge = $ages->isNotEmpty() ? round($ages->avg(), 1) : 0;
                                            @endphp
                                            {{ $averageAge }} anos
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Lista de Aniversariantes do Mês -->
            @php
                $birthdaysThisMonth = $students
                    ->filter(function ($student) {
                        return $student->birthdate && $student->birthdate->format('m') == now()->format('m');
                    })
                    ->sortBy(function ($student) {
                        return $student->birthdate->format('d');
                    });
            @endphp

            @if ($birthdaysThisMonth->count() > 0)
                <div class="school-card mt-4">
                    <div class="school-card-header">
                        <h3 class="school-card-title">
                            <i class="fas fa-birthday-cake"></i>
                            Aniversariantes do Mês
                        </h3>
                    </div>
                    <div class="school-card-body">
                        <div class="row">
                            @foreach ($birthdaysThisMonth as $student)
                                <div class="col-md-3 mb-3">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <div class="user-avatar-lg mx-auto mb-2 bg-warning">
                                                <i class="fas fa-birthday-cake text-white"></i>
                                            </div>
                                            <h6 class="card-title mb-1">{{ $student->first_name }}
                                                {{ $student->last_name }}</h6>
                                            <p class="text-muted small mb-2">
                                                {{ $student->birthdate->format('d/m') }}
                                                ({{ $student->birthdate->age + 1 }} anos)
                                            </p>
                                            <span class="badge bg-warning">
                                                <i class="fas fa-gift"></i> Aniversariante
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('genderChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Masculino', 'Feminino'],
                        datasets: [{
                            data: [{{ $maleCount }}, {{ $femaleCount }}],
                            backgroundColor: ['#007bff', '#e83e8c'],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            });
        </script>
    @endpush

    <style>
        .user-avatar-sm {
            width: 40px;
            height: 40px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 600;
        }

        .user-avatar-lg {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .bg-pink {
            background-color: #e83e8c !important;
        }
    </style>
@endsection
