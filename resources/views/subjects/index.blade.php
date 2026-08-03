@extends('layouts.app')

@section('title', 'Gestão de Disciplinas')
@section('page-title', 'Disciplinas')
@section('page-title-icon', 'fas fa-book')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Disciplinas</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Estatísticas -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-book fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Total de Disciplinas</div>
                            <h4 class="mb-0 text-primary font-weight-bold">{{ $totalSubjects }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Disciplinas Ativas</div>
                            <h4 class="mb-0 text-success font-weight-bold">{{ $activeSubjects }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-chalkboard fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Turmas Atribuidas</div>
                            <h4 class="mb-0 text-info font-weight-bold">{{ \App\Models\ClassSubject::distinct('class_id')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-warning">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning-subtle text-warning rounded-circle p-3 me-3">
                            <i class="fas fa-medal fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Notas Registadas</div>
                            <h4 class="mb-0 text-dark font-weight-bold">{{ \App\Models\Grade::count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form action="{{ route('subjects.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Pesquisar</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nome, código..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nível</label>
                        <select name="grade_level" class="form-select">
                            <option value="">Todos os níveis</option>
                            @foreach($gradeLevels as $key => $level)
                                <option value="{{ $key }}" {{ request('grade_level') == $key ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="">Todos</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Ativas</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inativas</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-school">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Disciplinas -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-list"></i>
                    Lista de Disciplinas
                </h3>
                @can('create_subjects')
                <a href="{{ route('subjects.create') }}" class="btn btn-secondary-school">
                    <i class="fas fa-plus"></i> Nova Disciplina
                </a>
                @endcan
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th>Código</th>
                            <th>Nível</th>
                            <th>Horas/Semana</th>
                            <th>Turmas</th>
                            <th>Notas</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td>
                                    <strong>{{ $subject->name }}</strong>
                                    @if($subject->description)
                                        <br>
                                        <small class="text-muted">{{ Str::limit($subject->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $subject->code }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $gradeLevels[$subject->grade_level] ?? $subject->grade_level }}</span>
                                </td>
                                <td>
                                    {{ $subject->weekly_hours }}h
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $subject->classes_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $subject->grades_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $subject->is_active ? 'success' : 'secondary' }}">
                                        {{ $subject->is_active ? 'Ativa' : 'Inativa' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('subjects.show', $subject->id) }}" 
                                           class="btn btn-sm btn-primary-school" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_subjects')
                                        <a href="{{ route('subjects.edit', $subject->id) }}" 
                                           class="btn btn-sm btn-secondary-school" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete_subjects')
                                        <form action="{{ route('subjects.destroy', $subject->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="Excluir" data-confirm="Tem certeza que deseja excluir esta disciplina?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhuma disciplina encontrada.</p>
                                    @can('create_subjects')
                                    <a href="{{ route('subjects.create') }}" class="btn btn-primary-school">
                                        <i class="fas fa-plus"></i> Criar Primeira Disciplina
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($subjects->hasPages())
                <div class="school-card-body border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Mostrando {{ $subjects->firstItem() }} a {{ $subjects->lastItem() }} de {{ $subjects->total() }} registros
                        </div>
                        {{ $subjects->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection