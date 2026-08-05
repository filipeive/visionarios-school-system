@extends('layouts.app')

@section('title', 'Gestão de Despesas')
@section('page-title', 'Gestão de Despesas')
@section('page-title-icon', 'fas fa-receipt')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item active">Despesas</li>
@endsection

@php
    $titleIcon = 'fas fa-receipt';
@endphp

@section('content')
<div class="space-y-5">

    <!-- Header -->
    <div class="dash-card-flat">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <p class="dash-kpi-label" style="margin-bottom:0.25rem;">Controle financeiro</p>
                <h1 class="dash-kpi-value" style="font-size:1.4rem;">Despesas</h1>
            </div>
            @can('create', \App\Models\Expense::class)
                <a href="{{ route('expenses.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nova Despesa
                </a>
            @endcan
        </div>
    </div>

    <!-- KPIs -->
    <section class="row g-3">
        <div class="col-md-4">
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="dash-kpi-label">Total Despesas</p>
                        <p class="dash-kpi-value">{{ number_format($stats['total'], 2, ',', '.') }} <span style="font-size:0.75rem; font-weight:600; color:#64748b;">MT</span></p>
                    </div>
                    <div class="rounded-lg p-2" style="background:#fee2e2; color:#dc2626;"><i class="fas fa-arrow-trend-down"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="dash-kpi-label">Despesas do Mês</p>
                        <p class="dash-kpi-value">{{ number_format($stats['this_month'], 2, ',', '.') }} <span style="font-size:0.75rem; font-weight:600; color:#64748b;">MT</span></p>
                    </div>
                    <div class="rounded-lg p-2" style="background:#fef3c7; color:#d97706;"><i class="fas fa-calendar"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="dash-kpi-label">Pendentes de Aprovação</p>
                        <p class="dash-kpi-value">{{ number_format($stats['pending_approval']) }}</p>
                    </div>
                    <div class="rounded-lg p-2" style="background:#e0e7ff; color:#4f46e5;"><i class="fas fa-clock"></i></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filtros e Listagem -->
    <section>
        <div class="dash-card-flat">
            <div class="dash-section">
                <div>
                    <p class="dash-section-title">Filtros</p>
                    <p class="dash-section-subtitle">Refine a pesquisa de despesas</p>
                </div>
            </div>
            <form action="{{ route('expenses.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Categoria</label>
                    <select name="category_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">De</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Até</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section>
        <div class="dash-card-flat">
            <div class="dash-section">
                <div>
                    <p class="dash-section-title">Lista de Despesas</p>
                    <p class="dash-section-subtitle">Histórico de saídas financeiras</p>
                </div>
            </div>
            <div class="dash-collapse-content">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
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
                                    <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="dash-badge" style="background:{{ $expense->category->color ?? '#f1f5f9' }}20; color:{{ $expense->category->color ?? '#334155' }};">
                                            {{ $expense->category->name }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">{{ $expense->description }}</td>
                                    <td class="fw-bold text-danger">{{ number_format($expense->amount, 2, ',', '.') }} MT</td>
                                    <td class="text-muted">{{ $expense->payment_method ?? '-' }}</td>
                                    <td>
                                        @if($expense->approved_at)
                                            <span class="dash-badge" style="background:#dcfce7; color:#166534;">Aprovada</span>
                                        @else
                                            <span class="dash-badge" style="background:#fef9c3; color:#854d0e;">Pendente</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('expenses.show', $expense) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('update', $expense)
                                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $expense)
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Remover esta despesa?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endcan
                                        @can('approve', $expense)
                                            @if(!$expense->approved_at)
                                                <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Aprovar esta despesa?')">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i></button>
                                                </form>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Nenhuma despesa registada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
