@extends('layouts.app')

@section('title', 'Gestão de Despesas')
@section('page-title', 'Gestão de Despesas')
@section('page-title-icon', 'fas fa-receipt')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item active">Despesas</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Estatísticas Rápidas (Stat KPI Cards) -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-danger">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger-subtle text-danger rounded-circle p-3 me-3">
                            <i class="fas fa-arrow-trend-down fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Total Despesas</div>
                            <h4 class="mb-0 text-danger font-weight-bold">{{ number_format($stats['total'], 2, ',', '.') }} <small class="text-xs text-muted">MT</small></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-warning">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning-subtle text-warning rounded-circle p-3 me-3">
                            <i class="fas fa-calendar-alt fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Despesas do Mês</div>
                            <h4 class="mb-0 text-warning font-weight-bold">{{ number_format($stats['this_month'], 2, ',', '.') }} <small class="text-xs text-muted">MT</small></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Pendentes Aprovação</div>
                            <h4 class="mb-0 text-info font-weight-bold">{{ number_format($stats['pending_approval']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                            <i class="fas fa-tags fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Categorias</div>
                            <h4 class="mb-0 text-success font-weight-bold">{{ $categories->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Harmonizados -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form action="{{ route('expenses.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Categoria</label>
                        <select name="category_id" class="form-select rounded-xl border-slate-200">
                            <option value="">Todas as Categorias</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Data Inicial (De)</label>
                        <input type="date" name="date_from" class="form-control rounded-xl border-slate-200" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Data Final (Até)</label>
                        <input type="date" name="date_to" class="form-control rounded-xl border-slate-200" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-school flex-grow rounded-xl">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                        @if(request()->anyFilled(['category_id', 'date_from', 'date_to']))
                            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary rounded-xl" title="Limpar Filtros">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Despesas -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-receipt me-2"></i>
                    Lista de Despesas
                    <span class="badge bg-primary ms-2" style="font-size: 0.55em;">{{ $expenses->total() }}</span>
                </h3>
                <div class="d-flex gap-2">
                    @can('create', \App\Models\Expense::class)
                        <a href="{{ route('expenses.create') }}" class="btn btn-secondary-school">
                            <i class="fas fa-plus me-1"></i> Nova Despesa
                        </a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Categoria</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Método</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>
                                    <strong>{{ $expense->expense_date->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $expense->expense_date->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="badge" style="background: {{ $expense->category->color ?? '#64748b' }}20; color: {{ $expense->category->color ?? '#1e293b' }}; border: 1px solid {{ $expense->category->color ?? '#cbd5e1' }}; font-weight: 600;">
                                        <i class="fas fa-tag me-1"></i> {{ $expense->category->name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-slate-800">{{ $expense->description }}</div>
                                    @if($expense->creator)
                                        <small class="text-muted"><i class="far fa-user me-1"></i>{{ $expense->creator->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-danger fs-6">{{ number_format($expense->amount, 2, ',', '.') }} MT</strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $expense->payment_method ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($expense->approved_at)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                            <i class="fas fa-check-circle me-1"></i> Aprovada
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">
                                            <i class="fas fa-clock me-1"></i> Pendente
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('expenses.show', $expense) }}" class="btn btn-outline-primary" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('update', $expense)
                                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('approve', $expense)
                                            @if(!$expense->approved_at)
                                                <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Aprovar esta despesa?')">
                                                    @csrf
                                                    <button class="btn btn-outline-success" title="Aprovar"><i class="fas fa-check"></i></button>
                                                </form>
                                            @endif
                                        @endcan
                                        @can('delete', $expense)
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Remover esta despesa?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger" title="Excluir"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fa-3x mb-3 text-slate-300"></i>
                                    <h5>Nenhuma despesa registrada</h5>
                                    <p class="text-xs">Não foram encontradas despesas com os filtros selecionados.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
                <div class="p-3 border-top">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
