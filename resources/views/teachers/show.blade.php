@extends('layouts.app')

@section('title', 'Detalhes do Professor')
@section('page-title', 'Detalhes do Professor')
@section('page-title-icon', 'fas fa-eye')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Professores</a></li>
    <li class="breadcrumb-item active">Detalhes</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- Informações Pessoais -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-info-circle"></i>
                    Informações Pessoais
                </div>
                <div class="school-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Dados Pessoais</h6>
                            <p><strong>Nome Completo:</strong> {{ $teacher->first_name }} {{ $teacher->last_name }}</p>
                            <p><strong>BI:</strong> {{ $teacher->bi_number }}</p>
                            <p><strong>Gênero:</strong> {{ $teacher->gender == 'male' ? 'Masculino' : 'Feminino' }}</p>
                            <p><strong>Data Nascimento:</strong>
                                {{ $teacher->birth_date ? $teacher->birth_date->format('d/m/Y') : 'Não informada' }}</p>
                            <p><strong>Idade:</strong>
                                {{ $teacher->birth_date ? $teacher->birth_date->diffInYears(now()) . ' anos' : 'Não informada' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Contactos</h6>
                            <p><strong>Email:</strong> {{ $teacher->email }}</p>
                            <p><strong>Telefone:</strong> {{ $teacher->phone }}</p>
                            <p><strong>Endereço:</strong> {{ $teacher->address }}</p>

                            <h6 class="mt-3">Informações Profissionais</h6>
                            <p><strong>Data Contratação:</strong>
                                {{ $teacher->hire_date ? $teacher->hire_date->format('d/m/Y') : 'Não informada' }}</p>
                            <p><strong>Experiência:</strong> {{ $teacher->years_experience }} anos</p>
                            <p><strong>Salário:</strong> {{ number_format($teacher->salary, 2, ',', '.') }} MZN</p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Qualificação</h6>
                            <p><strong>Formação:</strong> {{ $teacher->qualification }}</p>
                            <p><strong>Especialização:</strong> {{ $teacher->specialization }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Status</h6>
                            <span class="badge bg-{{ $teacher->status == 'active' ? 'success' : 'secondary' }} fs-6">
                                {{ $teacher->status == 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                            @if($teacher->user)
                                <span class="badge bg-success ms-2">Conta de Usuário Ativa</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Turmas do Professor -->
            @if($teacher->classes->count() > 0)
                <div class="school-card mb-4">
                    <div class="school-card-header">
                        <i class="fas fa-chalkboard"></i>
                        Turmas Atribuídas
                    </div>
                    <div class="school-card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Turma</th>
                                        <th>Ano Letivo</th>
                                        <th>Nível</th>
                                        <th>Capacidade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->classes as $class)
                                        <tr>
                                            <td>
                                                <strong>{{ $class->name }}</strong>
                                            </td>
                                            <td>{{ $class->school_year }}</td>
                                            <td>{{ $class->grade_level_name }}</td>
                                            <td>
                                                {{ $class->current_students }}/{{ $class->max_students }}
                                                <div class="progress mt-1" style="height: 5px;">
                                                    <div class="progress-bar bg-{{ $class->capacity_percentage > 80 ? 'danger' : ($class->capacity_percentage > 60 ? 'warning' : 'success') }}"
                                                        style="width: {{ $class->capacity_percentage }}%"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $class->is_active ? 'success' : 'secondary' }}">
                                                    {{ $class->is_active ? 'Ativa' : 'Inativa' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Estatísticas -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-chart-bar"></i>
                    Estatísticas
                </div>
                <div class="school-card-body">
                    <div class="text-center">
                        <div class="user-avatar mx-auto mb-3"
                            style="width: 80px; height: 80px; font-size: 24px; background: linear-gradient(135deg, var(--accent), #0097A7);">
                            {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                        </div>
                        <h5>{{ $teacher->first_name }} {{ $teacher->last_name }}</h5>
                        <p class="text-muted">{{ $teacher->specialization }}</p>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Turmas Totais:</span>
                            <strong>{{ $stats['total_classes'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Turmas Atuais:</span>
                            <strong>{{ $stats['current_classes'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Disciplinas:</span>
                            <strong>{{ $stats['total_subjects'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Licenças Pendentes:</span>
                            <strong class="text-{{ $stats['pending_leave_requests'] > 0 ? 'warning' : 'success' }}">
                                {{ $stats['pending_leave_requests'] }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-cog"></i>
                    Ações
                </div>
                <div class="school-card-body">
                    <div class="d-grid gap-2">
                        @can('edit_teachers')
                            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-secondary-school">
                                <i class="fas fa-edit"></i> Editar Professor
                            </a>
                        @endcan

                        @can('delete_teachers')
                            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="d-grid">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Tem certeza que deseja excluir este professor?')">
                                    <i class="fas fa-trash"></i> Excluir Professor
                                </button>
                            </form>
                        @endcan

                        @can('edit_teachers')
                            <form action="{{ route('teachers.toggle-status', $teacher->id) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit"
                                    class="btn btn-{{ $teacher->status == 'active' ? 'warning' : 'success' }}">
                                    <i class="fas fa-{{ $teacher->status == 'active' ? 'pause' : 'play' }}"></i>
                                    {{ $teacher->status == 'active' ? 'Desativar' : 'Ativar' }} Professor
                                </button>
                            </form>
                        @endcan

                        @if($teacher->user)
                            <a href="{{ route('admin.users.edit', $teacher->user->id) }}" class="btn btn-info text-white">
                                <i class="fas fa-user-cog"></i> Gerenciar Conta
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Atribuir Turma -->
            @can('edit_teachers')
                <div class="school-card">
                    <div class="school-card-header">
                        <i class="fas fa-link"></i>
                        Atribuir Turma
                    </div>
                    <div class="school-card-body">
                        <form action="{{ route('teachers.assign-class', $teacher->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Turma</label>
                                <select name="class_id" class="form-select" required>
                                    <option value="">Selecione uma turma</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->name }} - {{ $class->grade_level_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Função</label>
                                <select name="role" class="form-select" required>
                                    <option value="main">Professor Principal</option>
                                    <option value="assistant">Professor Auxiliar</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary-school w-100">
                                <i class="fas fa-link"></i> Atribuir Turma
                            </button>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection