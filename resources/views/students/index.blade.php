@extends('layouts.app')

@section('title', 'Gestão de Alunos')
@section('page-title', 'Gestão de Alunos')
@section('page-title-icon', 'fas fa-user-graduate')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Alunos</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Estatísticas Rápidas -->
        <div class="school-stats mb-4">
            <div class="stat-card students">
                <div class="stat-icon students">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalStudents }}</div>
                    <div class="stat-label">Total de Alunos</div>
                </div>
            </div>

            <div class="stat-card teachers">
                <div class="stat-icon teachers">
                    <i class="fas fa-male"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\Student::where('gender', 'male')->count() }}</div>
                    <div class="stat-label">Alunos</div>
                </div>
            </div>

            <div class="stat-card payments">
                <div class="stat-icon payments">
                    <i class="fas fa-female"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\Student::where('gender', 'female')->count() }}</div>
                    <div class="stat-label">Alunas</div>
                </div>
            </div>

            <div class="stat-card events">
                <div class="stat-icon events">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\Enrollment::where('status', 'active')->count() }}</div>
                    <div class="stat-label">Matrículas Ativas</div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form action="{{ route('students.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Pesquisar</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nome, número..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                            <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferidos</option>
                            <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Formados</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Turma</label>
                        <select name="class_id" class="form-select">
                            <option value="">Todas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Gênero</label>
                        <select name="gender" class="form-select">
                            <option value="">Todos</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Feminino</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-school">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            @if(request()->anyFilled(['search', 'status', 'class_id', 'gender']))
                            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Limpar
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Alunos -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-list"></i>
                    Lista de Alunos
                </h3>
                @can('create_students')
                <a href="{{ route('students.create') }}" class="btn btn-secondary-school">
                    <i class="fas fa-plus"></i> Novo Aluno
                </a>
                @endcan
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Número</th>
                            <th>Turma</th>
                            <th>Idade</th>
                            <th>Enc. Educação</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php
                                $currentEnrollment = $student->enrollments->where('status', 'active')->first();
                                $age = $student->birthdate ? $student->birthdate->age : 'N/A';
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($student->passport_photo)
                                            <img src="{{ Storage::url($student->passport_photo) }}" 
                                                 alt="{{ $student->first_name }}" 
                                                 class="rounded-circle me-3" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="user-avatar-sm me-3">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                            @if($student->has_special_needs)
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
                                    @if($currentEnrollment)
                                        <span class="badge bg-primary">{{ $currentEnrollment->class->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">Sem turma</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $age }} anos
                                    @if($student->birthdate)
                                        <br>
                                        <small class="text-muted">{{ $student->birthdate->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($student->parent)
                                        <div>
                                            {{ $student->parent->first_name }} {{ $student->parent->last_name }}
                                            @if($student->parent->phone)
                                                <br>
                                                <small class="text-muted">{{ $student->parent->phone }}</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $student->status === 'active' ? 'success' : ($student->status === 'inactive' ? 'danger' : 'warning') }}">
                                        @switch($student->status)
                                            @case('active') Ativo @break
                                            @case('inactive') Inativo @break
                                            @case('transferred') Transferido @break
                                            @case('graduated') Formado @break
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('students.show', $student) }}" 
                                           class="btn btn-sm btn-primary-school" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_students')
                                        <a href="{{ route('students.edit', $student) }}" 
                                           class="btn btn-sm btn-secondary-school" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete_students')
                                        <form action="{{ route('students.destroy', $student) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="Excluir" 
                                                    onclick="return confirm('Tem certeza que deseja excluir este aluno?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhum aluno encontrado.</p>
                                    @can('create_students')
                                    <a href="{{ route('students.create') }}" class="btn btn-primary-school">
                                        <i class="fas fa-plus"></i> Cadastrar Primeiro Aluno
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($students->hasPages())
                <div class="school-card-body border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Mostrando {{ $students->firstItem() }} a {{ $students->lastItem() }} de {{ $students->total() }} registros
                        </div>
                        {{ $students->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

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
</style>
@endsection