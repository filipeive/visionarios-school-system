@extends('layouts.app')

@section('title', 'Gestão de Encarregados de Educação')
@section('page-title', 'Encarregados de Educação')
@section('page-title-icon', 'fas fa-users-cog')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item active">Encarregados</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Stat KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-users-cog fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Total Encarregados</div>
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

        <!-- Filtros Harmonizados -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form action="{{ route('parents.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-10">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Pesquisar Encarregados</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control rounded-xl border-slate-200"
                            placeholder="Pesquisar por nome, e-mail, telefone ou N.º de BI...">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-school flex-grow rounded-xl">
                            <i class="fas fa-search me-1"></i> Filtrar
                        </button>
                        @if(request()->filled('search'))
                            <a href="{{ route('parents.index') }}" class="btn btn-outline-secondary rounded-xl" title="Limpar">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Encarregados -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-users text-primary me-2"></i>
                    Lista de Encarregados de Educação
                    <span class="badge bg-primary ms-2" style="font-size: 0.55em;">{{ $parents->total() }}</span>
                </h3>
                <div class="d-flex gap-2">
                    @can('create_students')
                        <a href="{{ route('parents.create') }}" class="btn btn-secondary-school">
                            <i class="fas fa-plus me-1"></i> Registar Encarregado
                        </a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Encarregado(a)</th>
                            <th>Contacto / E-mail</th>
                            <th>Profissão / Trabalho</th>
                            <th>Educando(s)</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parents as $parent)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 13px;">
                                            {{ strtoupper(substr($parent->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-slate-800">{{ $parent->full_name }}</div>
                                            <small class="text-muted">BI: <code>{{ $parent->bi_number ?? 'N/A' }}</code></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-slate-800"><i class="fas fa-phone me-1 text-muted"></i> {{ $parent->phone }}</div>
                                    <small class="text-muted"><i class="fas fa-envelope me-1"></i> {{ $parent->email }}</small>
                                </td>
                                <td>
                                    <div class="text-slate-800 fw-semibold">{{ $parent->profession ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $parent->workplace ?? '' }}</small>
                                </td>
                                <td>
                                    @forelse($parent->students as $st)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-1 mb-1">
                                            <i class="fas fa-user-graduate me-1"></i> {{ $st->first_name }} {{ $st->last_name }}
                                        </span>
                                    @empty
                                        <span class="text-muted small">Sem educandos associados</span>
                                    @endforelse
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('parents.show', $parent->user_id) }}" class="btn btn-outline-primary" title="Ver Perfil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_students')
                                            <a href="{{ route('parents.edit', $parent->user_id) }}" class="btn btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-users-slash fa-3x mb-3 text-slate-300"></i>
                                    <h5>Nenhum encarregado encontrado</h5>
                                    <p class="text-xs">Não foram encontrados registos com os termos pesquisados.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($parents->hasPages())
                <div class="p-3 border-top">
                    {{ $parents->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
