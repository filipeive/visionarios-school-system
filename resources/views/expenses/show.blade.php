@extends('layouts.app')

@section('title', 'Despesa #' . $expense->id)
@section('page-title', 'Detalhe da Despesa')
@section('page-title-icon', 'fas fa-receipt')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Despesas</a></li>
    <li class="breadcrumb-item active">#{{ $expense->id }}</li>
@endsection

@php
    $titleIcon = 'fas fa-receipt';
@endphp

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="dash-card-flat">
            <div class="dash-section">
                <div>
                    <p class="dash-section-title">Despesa #{{ $expense->id }}</p>
                    <p class="dash-section-subtitle">Detalhes da saída financeira</p>
                </div>
                <div>
                    @if(!$expense->approved_at)
                        @can('approve', $expense)
                            <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Aprovar esta despesa?')">
                                @csrf
                                <button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Aprovar</button>
                            </form>
                        @endcan
                    @else
                        <span class="dash-badge" style="background:#dcfce7; color:#166534;">Aprovada por {{ $expense->approver?->name }}</span>
                    @endif
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <p class="dash-kpi-label">Categoria</p>
                    <p class="fw-semibold">{{ $expense->category->name }}</p>
                </div>
                <div class="col-md-6">
                    <p class="dash-kpi-label">Data</p>
                    <p class="fw-semibold">{{ $expense->expense_date->format('d/m/Y') }}</p>
                </div>
                <div class="col-12">
                    <p class="dash-kpi-label">Descrição</p>
                    <p class="fw-semibold">{{ $expense->description }}</p>
                </div>
                <div class="col-md-6">
                    <p class="dash-kpi-label">Valor</p>
                    <p class="fw-bold text-danger" style="font-size:1.25rem;">{{ number_format($expense->amount, 2, ',', '.') }} MT</p>
                </div>
                <div class="col-md-6">
                    <p class="dash-kpi-label">Método de Pagamento</p>
                    <p class="fw-semibold">{{ $expense->payment_method ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="dash-kpi-label">Número do Recibo</p>
                    <p class="fw-semibold">{{ $expense->receipt_number ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="dash-kpi-label">Fornecedor</p>
                    <p class="fw-semibold">{{ $expense->supplier ?? '-' }}</p>
                </div>
                <div class="col-12">
                    <p class="dash-kpi-label">Observações</p>
                    <p class="fw-semibold">{{ $expense->notes ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="dash-kpi-label">Registado por</p>
                    <p class="fw-semibold">{{ $expense->creator?->name ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="dash-kpi-label">Criado em</p>
                    <p class="fw-semibold">{{ $expense->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-4 d-grid gap-2 d-md-flex">
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                @can('update', $expense)
                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
