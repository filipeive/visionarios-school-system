@extends('layouts.app')

@section('title', 'Detalhes do Pagamento')
@section('page-title', 'Pagamento #' . $payment->reference_number)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pagamentos</a></li>
    <li class="breadcrumb-item active">{{ $payment->reference_number }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Coluna Principal --}}
        <div class="col-lg-8">
            {{-- Card de Status --}}
            <div class="school-card mb-4">
                <div class="school-card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1">
                                <code class="bg-light px-3 py-2 rounded fs-5">{{ $payment->reference_number }}</code>
                            </h4>
                            <p class="text-muted mb-0">Referência de Pagamento</p>
                        </div>
                        <div class="text-end">
                            @switch($payment->status)
                                @case('paid')
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="fas fa-check-circle"></i> PAGO
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                        <i class="fas fa-clock"></i> PENDENTE
                                    </span>
                                    @break
                                @case('overdue')
                                    <span class="badge bg-danger fs-6 px-3 py-2">
                                        <i class="fas fa-exclamation-circle"></i> EM ATRASO
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-secondary fs-6 px-3 py-2">
                                        <i class="fas fa-ban"></i> CANCELADO
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informações do Pagamento --}}
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-file-invoice-dollar"></i> Detalhes do Pagamento
                </div>
                <div class="school-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-2">Tipo de Pagamento</h6>
                            <p class="fs-5 mb-0">
                                @switch($payment->type)
                                    @case('matricula')
                                        <span class="badge bg-primary">Taxa de Matrícula</span>
                                        @break
                                    @case('mensalidade')
                                        <span class="badge bg-success">Mensalidade</span>
                                        @break
                                    @case('material')
                                        <span class="badge bg-info">Material Escolar</span>
                                        @break
                                    @case('uniforme')
                                        <span class="badge bg-secondary">Uniforme</span>
                                        @break
                                    @default
                                        <span class="badge bg-dark">Outro</span>
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-2">Período de Referência</h6>
                            <p class="fs-5 mb-0">
                                @if($payment->month)
                                    {{ $payment->month_name }} / {{ $payment->year }}
                                @else
                                    {{ $payment->year }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-2">Data de Vencimento</h6>
                            <p class="fs-5 mb-0 {{ $payment->due_date < now() && $payment->status != 'paid' ? 'text-danger' : '' }}">
                                <i class="fas fa-calendar"></i> {{ $payment->due_date->format('d/m/Y') }}
                                @if($payment->due_date < now() && $payment->status != 'paid')
                                    <small class="text-danger">(Vencido há {{ $payment->due_date->diffInDays(now()) }} dias)</small>
                                @endif
                            </p>
                        </div>
                        @if($payment->payment_date)
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-2">Data do Pagamento</h6>
                            <p class="fs-5 mb-0 text-success">
                                <i class="fas fa-check"></i> {{ $payment->payment_date->format('d/m/Y') }}
                            </p>
                        </div>
                        @endif
                    </div>

                    <hr>

                    {{-- Valores --}}
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <h6 class="text-muted">Valor Base</h6>
                            <p class="fs-4 fw-bold mb-0">{{ number_format($payment->amount, 2, ',', '.') }} MT</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <h6 class="text-muted">Desconto</h6>
                            <p class="fs-4 fw-bold mb-0 text-success">
                                - {{ number_format($payment->discount, 2, ',', '.') }} MT
                            </p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <h6 class="text-muted">Total a Pagar</h6>
                            <p class="fs-3 fw-bold mb-0 text-primary">
                                {{ number_format($payment->total_amount, 2, ',', '.') }} MT
                            </p>
                        </div>
                    </div>

                    @if($payment->payment_method)
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Método de Pagamento</h6>
                            <p class="mb-0">
                                @switch($payment->payment_method)
                                    @case('cash') <i class="fas fa-money-bill"></i> Dinheiro @break
                                    @case('mpesa') <i class="fas fa-mobile-alt"></i> M-Pesa @break
                                    @case('emola') <i class="fas fa-mobile-alt"></i> e-Mola @break
                                    @case('bank') <i class="fas fa-university"></i> Transferência Bancária @break
                                    @case('multicaixa') <i class="fas fa-credit-card"></i> Multicaixa @break
                                @endswitch
                            </p>
                        </div>
                        @if($payment->transaction_id)
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">ID da Transação</h6>
                            <p class="mb-0"><code>{{ $payment->transaction_id }}</code></p>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($payment->notes)
                    <hr>
                    <div>
                        <h6 class="text-muted mb-2">Observações</h6>
                        <p class="mb-0">{{ $payment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Informações do Aluno --}}
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-user-graduate"></i> Informações do Aluno
                </div>
                <div class="school-card-body">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $payment->student->photo_url }}" 
                             class="rounded-circle me-3" width="80" height="80"
                             alt="{{ $payment->student->full_name }}">
                        <div>
                            <h5 class="mb-1">{{ $payment->student->full_name }}</h5>
                            <p class="text-muted mb-0">
                                <code>{{ $payment->student->student_number }}</code>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Turma</h6>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $payment->enrollment?->class?->name ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Encarregado</h6>
                            <p class="mb-0">{{ $payment->student->parent?->first_name ?? 'N/A' }} {{ $payment->student->parent?->last_name ?? '' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Contacto</h6>
                            <p class="mb-0">{{ $payment->student->parent?->phone ?? $payment->student->emergency_phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Mensalidade Base</h6>
                            <p class="mb-0">{{ number_format($payment->enrollment?->monthly_fee ?? $payment->student->monthly_fee, 2, ',', '.') }} MT</p>
                        </div>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('students.show', $payment->student) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Ver Perfil Completo
                        </a>
                        <a href="{{ route('students.payments', $payment->student) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-history"></i> Histórico de Pagamentos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Coluna Lateral --}}
        <div class="col-lg-4">
            {{-- Ações --}}
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-cogs"></i> Ações
                </div>
                <div class="school-card-body">
                    <div class="d-grid gap-2">
                        @if($payment->status == 'pending' || $payment->status == 'overdue')
                            @can('process_payments')
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#processModal">
                                <i class="fas fa-check-circle"></i> Processar Pagamento
                            </button>
                            @endcan
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="fas fa-ban"></i> Cancelar Pagamento
                            </button>
                        @endif
                        <a href="{{ route('payments.download-reference', $payment) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-print"></i> Imprimir Referência
                        </a>
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar à Lista
                        </a>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-history"></i> Histórico
                </div>
                <div class="school-card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pagamento Criado</h6>
                                <small class="text-muted">{{ $payment->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @if($payment->status == 'paid')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pagamento Confirmado</h6>
                                <small class="text-muted">{{ $payment->payment_date?->format('d/m/Y') ?? $payment->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @elseif($payment->status == 'cancelled')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pagamento Cancelado</h6>
                                <small class="text-muted">{{ $payment->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Processamento --}}
<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('payments.process', $payment) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle"></i> Processar Pagamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Valor a Receber:</strong> {{ number_format($payment->total_amount, 2, ',', '.') }} MT
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Método de Pagamento *</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="cash">Dinheiro</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="emola">e-Mola</option>
                            <option value="bank">Transferência Bancária</option>
                            <option value="multicaixa">Multicaixa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID da Transação</label>
                        <input type="text" name="transaction_id" class="form-control" placeholder="Ex: MP123456789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Data do Pagamento *</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observações</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Confirmar Pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de Cancelamento --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('payments.cancel', $payment) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-ban"></i> Cancelar Pagamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Esta ação não pode ser desfeita. Tem certeza que deseja cancelar este pagamento?
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motivo do Cancelamento *</label>
                        <textarea name="reason" class="form-control" rows="3" required 
                                  placeholder="Informe o motivo do cancelamento..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban"></i> Confirmar Cancelamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-color);
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -25px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px var(--border-color);
}
.timeline-content h6 {
    font-size: 14px;
    margin-bottom: 2px;
}
</style>
@endsection