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
        <!-- Filtros e Busca -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <form action="{{ route('teachers.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Pesquisar por nome, email ou BI..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary-school">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" onchange="window.location.href = this.value">
                            <option value="{{ route('teachers.index') }}">Todos os Status</option>
                            <option value="{{ route('teachers.index', ['status' => 'active']) }}" 
                                    {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="{{ route('teachers.index', ['status' => 'inactive']) }}"
                                    {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        @can('create_teachers')
                        <a href="{{ route('teachers.create') }}" class="btn btn-secondary-school w-100">
                            <i class="fas fa-plus"></i> Novo Professor
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="school-stats mb-4">
            <div class="stat-card students">
                <div class="stat-icon students">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\Teacher::count() }}</div>
                    <div class="stat-label">Total de Professores</div>
                </div>
            </div>

            <div class="stat-card teachers">
                <div class="stat-icon teachers">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\Teacher::active()->count() }}</div>
                    <div class="stat-label">Professores Ativos</div>
                </div>
            </div>

            <div class="stat-card payments">
                <div class="stat-icon payments">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    @php
                        $totalClasses = \App\Models\ClassRoom::whereNotNull('teacher_id')->count();
                    @endphp
                    <div class="stat-value">{{ $totalClasses }}</div>
                    <div class="stat-label">Turmas com Professor</div>
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
                                        <div class="user-avatar" style="width: 40px; height: 40px; font-size: 14px; margin-right: 12px; background: linear-gradient(135deg, var(--accent), #0097A7);">
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
                                                    title="Excluir" onclick="return confirm('Tem certeza?')">
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