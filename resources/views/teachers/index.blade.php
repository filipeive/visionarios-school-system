@extends('layouts.app')

@section('title', 'Gestão de Professores')
@section('page-title', 'Professores')
@section('page-title-icon', 'fas fa-chalkboard-teacher')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Professores</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filtros e Busca Harmonizados -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form action="{{ route('teachers.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Pesquisar Professor</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control rounded-xl border-slate-200" 
                                   placeholder="Pesquisar por nome, email ou BI..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary-school">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Status</label>
                        <select name="status" class="form-select rounded-xl border-slate-200" onchange="this.form.submit()">
                            <option value="">Todos os Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        @if(request()->anyFilled(['search', 'status']))
                            <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary rounded-xl" title="Limpar Filtros">
                                <i class="fas fa-times me-1"></i> Limpar
                            </a>
                        @endif

                        @can('create_teachers')
                        <a href="{{ route('teachers.create') }}" class="btn btn-secondary-school rounded-xl text-nowrap w-100">
                            <i class="fas fa-plus me-1"></i> Novo Professor
                        </a>
                        @endcan
                    </div>
                </form>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-chalkboard-teacher fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Total de Professores</div>
                            <h4 class="mb-0 text-primary font-weight-bold">{{ \App\Models\Teacher::count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                            <i class="fas fa-user-check fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Professores Ativos</div>
                            <h4 class="mb-0 text-success font-weight-bold">{{ \App\Models\Teacher::active()->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-graduation-cap fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Turmas com Director/Prof.</div>
                            <h4 class="mb-0 text-info font-weight-bold">{{ \App\Models\ClassRoom::whereNotNull('teacher_id')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Professores -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-list"></i>
                    Lista de Professores
                </h3>
                <div class="text-muted">
                    {{ $teachers->total() }} professor(es) encontrado(s)
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Professor</th>
                            <th>Contacto</th>
                            <th>Qualificação</th>
                            <th>Turmas</th>
                            <th>Salário</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-sm me-3" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                            {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $teacher->first_name }} {{ $teacher->last_name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                BI: {{ $teacher->bi_number }}
                                                @if($teacher->user)
                                                    <span class="badge bg-success ms-1">Conta Ativa</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <i class="fas fa-envelope text-muted me-1"></i>
                                        {{ $teacher->email }}
                                        <br>
                                        <i class="fas fa-phone text-muted me-1"></i>
                                        {{ $teacher->phone }}
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $teacher->qualification }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $teacher->specialization }}</small>
                                </td>
                                <td>
                                    @if($teacher->classes->count() > 0)
                                        <span class="badge bg-primary">{{ $teacher->classes->count() }} turma(s)</span>
                                        <br>
                                        <small class="text-muted">
                                            @foreach($teacher->classes->take(2) as $class)
                                                {{ $class->name }}@if(!$loop->last), @endif
                                            @endforeach
                                            @if($teacher->classes->count() > 2)
                                                ...
                                            @endif
                                        </small>
                                    @else
                                        <span class="badge bg-secondary">Sem turmas</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ number_format($teacher->salary, 2, ',', '.') }} MZN</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $teacher->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ $teacher->status == 'active' ? 'Ativo' : 'Inativo' }}
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        {{ $teacher->years_experience }} anos de experiência
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('teachers.show', $teacher->id) }}" 
                                           class="btn btn-sm btn-primary-school" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_teachers')
                                        <a href="{{ route('teachers.edit', $teacher->id) }}" 
                                           class="btn btn-sm btn-secondary-school" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete_teachers')
                                        <form action="{{ route('teachers.destroy', $teacher->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="Excluir" data-confirm="Tem certeza que deseja excluir este professor?">
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
                                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhum professor encontrado.</p>
                                    @can('create_teachers')
                                    <a href="{{ route('teachers.create') }}" class="btn btn-primary-school">
                                        <i class="fas fa-plus"></i> Cadastrar Primeiro Professor
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($teachers->hasPages())
                <div class="school-card-body border-top">
                    {{ $teachers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection