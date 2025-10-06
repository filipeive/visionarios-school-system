
@extends('layouts.school')

@section('title', 'Gestão de Alunos')
@section('page-title', 'Gestão de Alunos')

@php
    $titleIcon = 'fas fa-user-graduate';
@endphp

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Alunos</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">Lista de Alunos</h4>
                <p class="text-muted mb-0">Total: {{ $students->total() }} alunos</p>
            </div>
            @can('create_students')
                <a href="{{ route('students.create') }}" class="btn-school btn-primary-school">
                    <i class="fas fa-plus"></i>
                    Novo Aluno
                </a>
            @endcan
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-body">
                <form method="GET" action="{{ route('students.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Pesquisar</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nome ou número do aluno..."
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Turma</label>
                        <select name="class_id" class="form-select">
                            <option value="">Todas as turmas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" 
                                    {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Todos os status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                            <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferido</option>
                            <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduado</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Alunos -->
<div class="row">
    <div class="col-12">
        <div class="school-table-container">
            <table class="table table-school table-hover">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Número</th>
                        <th>Nome Completo</th>
                        <th>Idade</th>
                        <th>Turma Atual</th>
                        <th>Encarregado</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>
                                <img src="{{ $student->photo_url }}" 
                                     alt="{{ $student->full_name }}"
                                     class="rounded-circle"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            </td>
                            <td><strong>{{ $student->student_number }}</strong></td>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->age }} anos</td>
                            <td>
                                @if($student->currentEnrollment)
                                    <span class="badge bg-primary">
                                        {{ $student->currentEnrollment->class->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Sem turma</span>
                                @endif
                            </td>
                            <td>
                                @if($student->parent)
                                    {{ $student->parent->full_name }}
                                @else
                                    <span class="text-muted">Não definido</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('students.show', $student) }}" 
                                       class="btn btn-info" title="Ver Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('edit_students')
                                        <a href="{{ route('students.edit', $student) }}" 
                                           class="btn btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('delete_students')
                                        <form action="{{ route('students.destroy', $student) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este aluno?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-user-graduate fs-3 mb-2 d-block"></i>
                                Nenhum aluno encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
