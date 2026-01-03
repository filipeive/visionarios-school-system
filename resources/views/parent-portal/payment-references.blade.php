@extends('layouts.app')

@section('title', 'Referências de Pagamento')
@section('page-title')
    Referências: {{ $student->first_name }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-receipt me-2"></i> Referências Pendentes
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-school">
                            <thead>
                                <tr>
                                    <th>Referência</th>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($references as $reference)
                                    <tr>
                                        <td class="font-monospace fw-bold">{{ $reference->reference_number }}</td>
                                        <td>
                                            {{ ucfirst($reference->type) }}
                                            @if($reference->month) - {{ $reference->month_name }}/{{ $reference->year }} @endif
                                        </td>
                                        <td class="fw-bold">
                                            {{ number_format($reference->amount, 2, ',', '.') }} MT
                                        </td>
                                        <td>
                                            {{ $reference->due_date->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            {!! $reference->status_badge !!}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-search fa-2x mb-2 d-block"></i>
                                            Nenhuma referência encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('parent.student-payments', $student) }}" class="btn btn-secondary-school">
                            <i class="fas fa-arrow-left me-2"></i> Voltar aos Pagamentos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection