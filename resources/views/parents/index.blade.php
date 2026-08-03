@extends('layouts.app')

@section('title', 'Gestão de Encarregados de Educação')
@section('page-title', 'Encarregados de Educação')
@section('title-icon', 'fas fa-users-cog')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Encarregados</li>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Stat Cards Uniformizados -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                        <i class="fas fa-users-cog fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Total de Encarregados</div>
                        <h4 class="mb-0 text-primary font-weight-bold">{{ $totalParents }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Educandos Associados</div>
                        <h4 class="mb-0 text-success font-weight-bold">{{ \App\Models\Student::whereNotNull('parent_id')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                        <i class="fas fa-shield-alt fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Contas do Portal dos Pais</div>
                        <h4 class="mb-0 text-info font-weight-bold">{{ \App\Models\User::role('parent')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela & Filtros -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-users text-primary me-2"></i> Lista de Encarregados de Educação
            </h5>
            @can('create_students')
                <a href="{{ route('parents.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i> Registar Encarregado
                </a>
            @endcan
        </div>
        <div class="card-body">
            <!-- Form de Pesquisa -->
            <form action="{{ route('parents.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-10">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Pesquisar por nome, e-mail, telefone ou N.º de BI...">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Pesquisar</button>
                    @if(request()->filled('search'))
                        <a href="{{ route('parents.index') }}" class="btn btn-light btn-sm border">Limpar</a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Encarregado(a)</th>
                            <th>Contacto / E-mail</th>
                            <th>Profissão / Local de Trabalho</th>
                            <th>Educando(s)</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parents as $parent)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $parent->full_name }}</div>
                                    <div class="text-muted small">BI: {{ $parent->bi_number ?? 'Não informado' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><i class="fas fa-phone me-1 text-muted"></i> {{ $parent->phone }}</div>
                                    <div class="text-muted small"><i class="fas fa-envelope me-1"></i> {{ $parent->email }}</div>
                                </td>
                                <td>
                                    <div class="text-dark">{{ $parent->profession ?? 'N/A' }}</div>
                                    <div class="text-muted small">{{ $parent->workplace ?? '' }}</div>
                                </td>
                                <td>
                                    @forelse($parent->students as $st)
                                        <span class="badge bg-light text-dark border me-1">
                                            <i class="fas fa-user-graduate me-1 text-primary"></i> {{ $st->first_name }} {{ $st->last_name }}
                                        </span>
                                    @empty
                                        <span class="text-muted small">Sem educandos associados</span>
                                    @endforelse
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('parents.show', $parent->user_id) }}" class="btn btn-outline-secondary" title="Ver Perfil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_students')
                                            <a href="{{ route('parents.edit', $parent->user_id) }}" class="btn btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-users-slash fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Nenhum encarregado de educação encontrado.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($parents->hasPages())
                <div class="mt-4">{{ $parents->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
