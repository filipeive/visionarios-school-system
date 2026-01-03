@extends('layouts.app')

@section('title', 'Relatório de Receitas')
@section('page-title', 'Relatório de Receitas')
@section('page-title-icon', 'fas fa-hand-holding-usd')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.financial') }}">Financeiro</a></li>
    <li class="breadcrumb-item active">Receitas</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card mb-4">
                <div class="school-card-body">
                    <form action="{{ route('reports.financial.revenue') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">De</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Até</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary-school w-100">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="school-card mb-4">
                <div class="school-card-body p-4 text-center">
                    <h4 class="text-muted mb-2">Total de Receita no Período</h4>
                    <h2 class="fw-bold text-success">{{ number_format($totalRevenue, 2, ',', '.') }} MT</h2>
                </div>
            </div>

            <div class="school-card">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-list"></i> Detalhamento de Pagamentos
                    </h3>
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-school">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Data</th>
                                    <th>Método</th>
                                    <th>Referência</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->student->first_name }} {{ $payment->student->last_name }}</td>
                                        <td>{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '-' }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td><code>{{ $payment->reference }}</code></td>
                                        <td class="fw-bold">{{ number_format($payment->amount, 2, ',', '.') }} MT</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection