{{-- resources/views/payments/with-penalties.blade.php --}}

@extends('layouts.app')

@section('title', 'Pagamentos com Multa')
@section('page-title', 'Pagamentos com Multa Aplicada')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pagamentos</a></li>
    <li class="breadcrumb-item active">Com Multa</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-warning bg-opacity-10 border-warning">
                                <div class="card-body text-center">
                                    <h3 class="text-warning">{{ number_format($totalPenalties, 2, ',', '.') }} MT</h3>
                                    <p class="mb-0">Total em Multas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger bg-opacity-10 border-danger">
                                <div class="card-body text-center">
                                    <h3 class="text-danger">{{ $payments->total() }}</h3>
                                    <p class="mb-0">Pagamentos com Multa</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info bg-opacity-10 border-info">
                                <div class="card-body text-center">
                                    <h3 class="text-info">{{ now()->format('d/m/Y') }}</h3>
                                    <p class="mb-0">Data de Consulta</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="school-table-container">
        <div class="school-table-header">
            <h5 class="school-table-title">
                <i class="fas fa-exclamation-triangle"></i> Pagamentos com Multa Aplicada
            </h5>
            <span class="badge bg-light text-dark">{{ $payments->total() }} registros</span>
        </div>

        <div class="table-responsive">
            <table class="table table-school table-hover mb-0">
                <thead>
                    <tr>
                        <th>Referência</th>
                        <th>Aluno</th>
                        <th>Turma</th>
                        <th>Valor Original</th>
                        <th>Multa</th>
                        <th>Total</th>
                        <th>Dias em Atraso</th>
                        <th>Vencimento</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr class="{{ $payment->is_blocked ? 'table-danger' : '' }}">
                        <td>
                            <code class="bg-light px-2 py-1 rounded">{{ $payment->reference_number }}</code>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $payment->student->photo_url }}" class="rounded-circle me-2" width="30" height="30">
                                <div>
                                    <div class="fw-semibold">{{ $payment->student->full_name }}</div>
                                    <small class="text-muted">{{ $payment->student->student_number }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $payment->enrollment?->class?->name ?? 'N/A' }}</span>
                        </td>
                        <td class="text-end">
                            {{ number_format($payment->original_amount, 2, ',', '.') }} MT
                        </td>
                        <td>
                            <span class="badge bg-danger">
                                {{ $payment->penalty_percentage }}% 
                                ({{ number_format($payment->penalty_amount, 2, ',', '.') }} MT)
                            </span>
                        </td>
                        <td class="text-end fw-bold">
                            {{ number_format($payment->total_amount, 2, ',', '.') }} MT
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $payment->days_late > 30 ? 'danger' : 'warning' }}">
                                {{ $payment->days_late }} dias
                            </span>
                            @if($payment->is_blocked)
                                <br><small class="text-danger">BLOQUEADO</small>
                            @endif
                        </td>
                        <td>
                            <span class="text-danger fw-bold">
                                {{ $payment->due_date->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($payment->penalty_amount > 0)
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            data-bs-toggle="modal" data-bs-target="#removePenaltyModal{{ $payment->id }}">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                @endif
                            </div>

                            <!-- Modal Remover Multa -->
                            <div class="modal fade" id="removePenaltyModal{{ $payment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Remover Multa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('payments.remove-penalty', $payment) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p><strong>Referência:</strong> {{ $payment->reference_number }}</p>
                                                <p><strong>Aluno:</strong> {{ $payment->student->full_name }}</p>
                                                <p><strong>Multa Atual:</strong> {{ $payment->penalty_percentage }}% ({{ number_format($payment->penalty_amount, 2, ',', '.') }} MT)</p>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Motivo da Remoção *</label>
                                                    <textarea name="reason" class="form-control" rows="3" required placeholder="Explique o motivo da remoção da multa..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-warning">Remover Multa</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <p>Nenhum pagamento com multa encontrado</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
        <div class="card-footer bg-white">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection