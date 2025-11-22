@extends('layouts.app')

@section('title', 'Gestão de Pagamentos')
@section('page-title', 'Pagamentos')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Pagamentos</li>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Cards de Estatísticas --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card payments">
                <div class="stat-icon payments">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($stats['paid'], 2, ',', '.') }} MT</div>
                    <div class="stat-label">Total Recebido</div>
                    <span class="stat-change positive">
                        <i class="fas fa-check"></i> Pagos
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card students">
                <div class="stat-icon students">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($stats['pending'], 2, ',', '.') }} MT</div>
                    <div class="stat-label">Pendente</div>
                    <span class="stat-change">
                        <i class="fas fa-hourglass-half"></i> {{ $stats['count_pending'] }} pagamentos
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card events">
                <div class="stat-icon events">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($stats['overdue'], 2, ',', '.') }} MT</div>
                    <div class="stat-label">Em Atraso</div>
                    <span class="stat-change negative">
                        <i class="fas fa-arrow-up"></i> {{ $stats['count_overdue'] }} em atraso
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card teachers">
                <div class="stat-icon teachers">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    @php
                        $totalPenalties = \App\Models\Payment::where('penalty', '>', 0)->sum('penalty');
                    @endphp
                    <div class="stat-value">{{ number_format($totalPenalties, 2, ',', '.') }} MT</div>
                    <div class="stat-label">Multas Aplicadas</div>
                    <span class="stat-change negative">
                        <i class="fas fa-balance-scale"></i> Total em multas
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros e Ações --}}
    <div class="school-card mb-4">
        <div class="school-card-body">
            <form method="GET" action="{{ route('payments.index') }}" id="filter-form">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Pesquisar</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nome, matrícula ou referência..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pago</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Em Atraso</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tipo</label>
                        <select name="type" class="form-select">
                            <option value="">Todos</option>
                            <option value="matricula" {{ request('type') == 'matricula' ? 'selected' : '' }}>Matrícula</option>
                            <option value="mensalidade" {{ request('type') == 'mensalidade' ? 'selected' : '' }}>Mensalidade</option>
                            <option value="material" {{ request('type') == 'material' ? 'selected' : '' }}>Material</option>
                            <option value="uniforme" {{ request('type') == 'uniforme' ? 'selected' : '' }}>Uniforme</option>
                            <option value="outro" {{ request('type') == 'outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Mês</label>
                        <select name="month" class="form-select">
                            <option value="">Todos</option>
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-school flex-grow-1">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Ações Rápidas --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex gap-2">
            @can('create_payments')
            <a href="{{ route('payments.create') }}" class="btn btn-primary-school">
                <i class="fas fa-plus"></i> Novo Pagamento
            </a>
            @endcan
            <a href="{{ route('payments.overdue') }}" class="btn btn-warning-school">
                <i class="fas fa-exclamation-triangle"></i> Em Atraso ({{ $stats['count_overdue'] }})
            </a>
            <a href="{{ route('payments.with-penalties') }}" class="btn btn-danger-school">
                <i class="fas fa-balance-scale"></i> Com Multas
                @php
                    $countWithPenalties = \App\Models\Payment::where('penalty', '>', 0)->count();
                @endphp
                @if($countWithPenalties > 0)
                <span class="badge bg-white text-danger ms-1">{{ $countWithPenalties }}</span>
                @endif
            </a>
            <a href="{{ route('payments.reports') }}" class="btn btn-success-school">
                <i class="fas fa-chart-bar"></i> Relatórios
            </a>
        </div>
        <div>
            <a href="{{ route('payments.references') }}" class="btn btn-secondary-school">
                <i class="fas fa-receipt"></i> Gerar Referências
            </a>
        </div>
    </div>

    {{-- Tabela de Pagamentos --}}
    <div class="school-table-container">
        <div class="school-table-header">
            <h5 class="school-table-title">
                <i class="fas fa-list"></i> Lista de Pagamentos
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
                        <th>Tipo</th>
                        <th>Mês/Ano</th>
                        <th class="text-end">Valor</th>
                        <th>Vencimento</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr class="{{ $payment->penalty > 0 ? 'table-warning' : '' }} {{ $payment->is_blocked ? 'table-danger' : '' }}">
                        <td>
                            <code class="bg-light px-2 py-1 rounded">{{ $payment->reference_number }}</code>
                            @if($payment->penalty > 0)
                                <br><small class="text-danger">Multa: {{ $payment->penalty_percentage }}%</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $payment->student->photo_url }}" 
                                     class="rounded-circle me-2" 
                                     width="35" height="35"
                                     alt="{{ $payment->student->full_name }}">
                                <div>
                                    <div class="fw-semibold">{{ $payment->student->full_name }}</div>
                                    <small class="text-muted">{{ $payment->student->student_number }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $payment->enrollment?->class?->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @switch($payment->type)
                                @case('matricula')
                                    <span class="badge bg-primary">Matrícula</span>
                                    @break
                                @case('mensalidade')
                                    <span class="badge bg-success">Mensalidade</span>
                                    @break
                                @case('material')
                                    <span class="badge bg-secondary">Material</span>
                                    @break
                                @case('uniforme')
                                    <span class="badge bg-info">Uniforme</span>
                                    @break
                                @default
                                    <span class="badge bg-dark">Outro</span>
                            @endswitch
                        </td>
                        <td>
                            @if($payment->month)
                                {{ $payment->month_name }}/{{ $payment->year }}
                            @else
                                {{ $payment->year }}
                            @endif
                            @if($payment->days_late > 0)
                                <br><small class="text-danger">{{ $payment->days_late }} dias atrasado</small>
                            @endif
                        </td>
                        <td class="text-end">
                            <div>
                                <strong>{{ number_format($payment->total_amount, 2, ',', '.') }} MT</strong>
                                @if($payment->discount > 0)
                                    <br><small class="text-success">-{{ number_format($payment->discount, 2, ',', '.') }} MT</small>
                                @endif
                                @if($payment->penalty > 0)
                                    <br><small class="text-danger">+{{ number_format($payment->penalty, 2, ',', '.') }} MT</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="{{ $payment->due_date < now() && $payment->status != 'paid' ? 'text-danger fw-bold' : '' }}">
                                {{ $payment->due_date->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>
                            @switch($payment->status)
                                @case('paid')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Pago
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock"></i> Pendente
                                    </span>
                                    @break
                                @case('overdue')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-circle"></i> Em Atraso
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-ban"></i> Cancelado
                                    </span>
                                    @break
                            @endswitch
                            @if($payment->is_blocked)
                                <br><span class="badge bg-dark mt-1">BLOQUEADO</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('payments.show', $payment) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($payment->status == 'pending' || $payment->status == 'overdue')
                                    @can('process_payments')
                                    <button type="button" class="btn btn-sm btn-success" 
                                            onclick="openProcessModal({{ $payment->id }})" title="Processar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    
                                    {{-- Botão para aplicar multa --}}
                                    @if($payment->penalty == 0 && $payment->days_late >= 15)
                                        <button type="button" class="btn btn-sm btn-warning" 
                                                onclick="openPenaltyModal({{ $payment->id }})" title="Aplicar Multa">
                                            <i class="fas fa-balance-scale"></i>
                                        </button>
                                    @endif
                                    @endcan
                                @endif
                                
                                {{-- Botão para remover multa --}}
                                @if($payment->penalty > 0)
                                    @can('process_payments')
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="openRemovePenaltyModal({{ $payment->id }})" title="Remover Multa">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endcan
                                @endif
                                
                                <a href="{{ route('payments.download-reference', $payment) }}" 
                                   class="btn btn-sm btn-outline-secondary" title="Imprimir" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>Nenhum pagamento encontrado</p>
                                <a href="{{ route('payments.create') }}" class="btn btn-primary-school btn-sm">
                                    <i class="fas fa-plus"></i> Criar Primeiro Pagamento
                                </a>
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

{{-- Modal de Processamento --}}
<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="processForm" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle"></i> Processar Pagamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                        <input type="text" name="transaction_id" class="form-control" 
                               placeholder="Ex: MP123456789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Data do Pagamento *</label>
                        <input type="date" name="payment_date" class="form-control" 
                               value="{{ date('Y-m-d') }}" required>
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

{{-- Modal de Aplicar Multa --}}
<div class="modal fade" id="penaltyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="penaltyForm" method="POST">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-balance-scale"></i> Aplicar Multa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="penaltyInfo" class="mb-3 p-3 bg-light rounded">
                        {{-- Informações serão preenchidas via JavaScript --}}
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Porcentagem da Multa *</label>
                        <select name="penalty_percentage" class="form-select" required id="penaltyPercentage">
                            <option value="">Selecione...</option>
                            <option value="10">10% (15-29 dias de atraso)</option>
                            <option value="25">25% (30-59 dias de atraso)</option>
                            <option value="50">50% (60-89 dias de atraso)</option>
                            <option value="100">100% (90+ dias de atraso)</option>
                            <option value="custom">Personalizado...</option>
                        </select>
                    </div>
                    <div class="mb-3" id="customPenaltyContainer" style="display: none;">
                        <label class="form-label fw-semibold">Porcentagem Personalizada</label>
                        <input type="number" name="custom_penalty_percentage" class="form-control" 
                               min="0" max="100" step="0.01" placeholder="Digite a porcentagem...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motivo da Multa *</label>
                        <textarea name="reason" class="form-control" rows="3" required 
                                  placeholder="Explique o motivo da aplicação da multa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-balance-scale"></i> Aplicar Multa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de Remover Multa --}}
<div class="modal fade" id="removePenaltyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="removePenaltyForm" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-times-circle"></i> Remover Multa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="removePenaltyInfo" class="mb-3 p-3 bg-light rounded">
                        {{-- Informações serão preenchidas via JavaScript --}}
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motivo da Remoção *</label>
                        <textarea name="reason" class="form-control" rows="3" required 
                                  placeholder="Explique o motivo da remoção da multa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Remover Multa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openProcessModal(paymentId) {
    const form = document.getElementById('processForm');
    form.action = `/payments/${paymentId}/process`;
    new bootstrap.Modal(document.getElementById('processModal')).show();
}

function openPenaltyModal(paymentId) {
    // Buscar dados do pagamento via fetch simples
    fetch(`/payments/${paymentId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }
            return response.text(); // Primeiro tenta como texto
        })
        .then(text => {
            try {
                // Tenta parsear como JSON
                const data = JSON.parse(text);
                updatePenaltyModal(data, paymentId);
            } catch (e) {
                // Se não for JSON, redireciona para a página normal
                window.location.href = `/payments/${paymentId}`;
                return;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            // Redireciona para a página normal em caso de erro
            window.location.href = `/payments/${paymentId}`;
        });
}

function updatePenaltyModal(data, paymentId) {
    const infoDiv = document.getElementById('penaltyInfo');
    const form = document.getElementById('penaltyForm');
    
    // Formatar valores monetários
    const amount = parseFloat(data.amount || 0).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    const penalty = parseFloat(data.penalty || 0).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    const totalAmount = parseFloat(data.total_amount || 0).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    infoDiv.innerHTML = `
        <strong>Referência:</strong> ${data.reference_number || 'N/A'}<br>
        <strong>Aluno:</strong> ${data.student?.full_name || 'N/A'}<br>
        <strong>Valor Original:</strong> ${amount} MT<br>
        <strong>Dias em Atraso:</strong> <span class="text-danger">${data.days_late || 0} dias</span><br>
        <strong>Multa Sugerida:</strong> <span class="text-warning">${data.suggested_penalty_percentage || 0}%</span>
    `;
    
    form.action = `/payments/${paymentId}/apply-penalty`;
    
    // Selecionar a porcentagem sugerida
    const percentageSelect = document.getElementById('penaltyPercentage');
    if (percentageSelect && data.suggested_penalty_percentage) {
        percentageSelect.value = data.suggested_penalty_percentage;
    }
    
    new bootstrap.Modal(document.getElementById('penaltyModal')).show();
}

function openRemovePenaltyModal(paymentId) {
    // Buscar dados do pagamento via fetch simples
    fetch(`/payments/${paymentId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }
            return response.text(); // Primeiro tenta como texto
        })
        .then(text => {
            try {
                // Tenta parsear como JSON
                const data = JSON.parse(text);
                updateRemovePenaltyModal(data, paymentId);
            } catch (e) {
                // Se não for JSON, redireciona para a página normal
                window.location.href = `/payments/${paymentId}`;
                return;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            // Redireciona para a página normal em caso de erro
            window.location.href = `/payments/${paymentId}`;
        });
}

function updateRemovePenaltyModal(data, paymentId) {
    const infoDiv = document.getElementById('removePenaltyInfo');
    const form = document.getElementById('removePenaltyForm');
    
    // Formatar valores monetários
    const amount = parseFloat(data.amount || 0).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    const currentPenalty = parseFloat(data.penalty || 0).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    const totalAmount = parseFloat(data.total_amount || 0).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    infoDiv.innerHTML = `
        <strong>Referência:</strong> ${data.reference_number || 'N/A'}<br>
        <strong>Aluno:</strong> ${data.student?.full_name || 'N/A'}<br>
        <strong>Valor Original:</strong> ${amount} MT<br>
        <strong>Multa Atual:</strong> <span class="text-danger">${data.penalty_percentage || 0}% (${currentPenalty} MT)</span><br>
        <strong>Total com Multa:</strong> ${totalAmount} MT
    `;
    
    form.action = `/payments/${paymentId}/remove-penalty`;
    new bootstrap.Modal(document.getElementById('removePenaltyModal')).show();
}

// Mostrar/ocultar campo de porcentagem personalizada
document.addEventListener('DOMContentLoaded', function() {
    const penaltyPercentage = document.getElementById('penaltyPercentage');
    if (penaltyPercentage) {
        penaltyPercentage.addEventListener('change', function() {
            const customContainer = document.getElementById('customPenaltyContainer');
            if (customContainer) {
                customContainer.style.display = this.value === 'custom' ? 'block' : 'none';
            }
        });
    }
});

// Função auxiliar para formatar valores
function formatCurrency(value) {
    return parseFloat(value || 0).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        style: 'currency',
        currency: 'MZN'
    });
}
</script>
@endpush