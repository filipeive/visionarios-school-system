@extends('layouts.app')

@section('title', 'Pagamentos - ' . $student->first_name)
@section('page-title', 'Histórico de Pagamentos')
@section('page-title-icon', 'fas fa-money-bill-wave')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->first_name }}</a></li>
    <li class="breadcrumb-item active">Pagamentos</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Informações do Aluno -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>{{ $student->first_name }} {{ $student->last_name }}</h4>
                        <p class="text-muted mb-0">
                            <strong>Número:</strong> {{ $student->student_number }} |
                            <strong>Mensalidade:</strong> {{ number_format($student->monthly_fee, 2, ',', '.') }} MT |
                            <strong>Turma:</strong> {{ $student->enrollments->where('status', 'active')->first()->class->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('students.show', $student) }}" class="btn btn-secondary-school">
                            <i class="fas fa-arrow-left"></i> Voltar ao Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas Financeiras -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon students">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ number_format($paymentStats['total_paid'], 2, ',', '.') }} MT</div>
                        <div class="stat-label">Total Pago</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon teachers">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ number_format($paymentStats['total_pending'], 2, ',', '.') }} MT</div>
                        <div class="stat-label">Pendente</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon payments">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ number_format($paymentStats['total_overdue'], 2, ',', '.') }} MT</div>
                        <div class="stat-label">Em Atraso</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon events">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-content">
                        @php
                            $total = $paymentStats['total_paid'] + $paymentStats['total_pending'] + $paymentStats['total_overdue'];
                            $paidPercentage = $total > 0 ? round(($paymentStats['total_paid'] / $total) * 100) : 0;
                        @endphp
                        <div class="stat-value">{{ $paidPercentage }}%</div>
                        <div class="stat-label">Taxa de Pagamento</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-success btn-lg w-100" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                            Novo Pagamento
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-primary-school btn-lg w-100">
                            <i class="fas fa-file-invoice-dollar fa-2x mb-2"></i><br>
                            Gerar Referência
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-print fa-2x mb-2"></i><br>
                            Imprimir Recibo
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                            Relatório
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Mês</label>
                        <select name="month" class="form-select" onchange="this.form.submit()">
                            <option value="">Todos os meses</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ano</label>
                        <select name="year" class="form-select" onchange="this.form.submit()">
                            @for($i = current_school_year() - 2; $i <= current_school_year() + 1; $i++)
                                <option value="{{ $i }}" {{ request('year', current_school_year()) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Todos</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pago</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Em Atraso</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo</label>
                        <select name="type" class="form-select" onchange="this.form.submit()">
                            <option value="">Todos</option>
                            <option value="matricula" {{ request('type') == 'matricula' ? 'selected' : '' }}>Matrícula</option>
                            <option value="mensalidade" {{ request('type') == 'mensalidade' ? 'selected' : '' }}>Mensalidade</option>
                            <option value="material" {{ request('type') == 'material' ? 'selected' : '' }}>Material</option>
                            <option value="uniforme" {{ request('type') == 'uniforme' ? 'selected' : '' }}>Uniforme</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Pagamentos -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-list"></i>
                    Histórico de Pagamentos
                </h3>
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Referência</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Data Venc.</th>
                            <th>Data Pag.</th>
                            <th>Status</th>
                            <th>Método</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->payments as $payment)
                            <tr>
                                <td>
                                    <code>{{ $payment->reference_number }}</code>
                                </td>
                                <td>
                                    <strong>
                                        @switch($payment->type)
                                            @case('matricula') Matrícula @break
                                            @case('mensalidade') Mensalidade @break
                                            @case('material') Material @break
                                            @case('uniforme') Uniforme @break
                                            @default {{ $payment->type }}
                                        @endswitch
                                    </strong>
                                    @if($payment->month)
                                        <br>
                                        <small class="text-muted">
                                            {{ $payment->month }}/{{ $payment->year }}
                                            @if($payment->notes)
                                                - {{ $payment->notes }}
                                            @endif
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-success">
                                        {{ number_format($payment->amount, 2, ',', '.') }} MT
                                    </strong>
                                    @if($payment->discount > 0)
                                        <br>
                                        <small class="text-danger">
                                            -{{ number_format($payment->discount, 2, ',', '.') }} MT
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    {{ $payment->due_date->format('d/m/Y') }}
                                    @if($payment->due_date->isPast() && $payment->status !== 'paid')
                                        <br>
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Vencido
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->payment_date)
                                        {{ $payment->payment_date->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($payment->status)
                                        @case('paid')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Pago
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Pendente
                                            </span>
                                            @break
                                        @case('overdue')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-triangle"></i> Atrasado
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times"></i> Cancelado
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    @if($payment->payment_method)
                                        @switch($payment->payment_method)
                                            @case('cash') Dinheiro @break
                                            @case('mpesa') MPesa @break
                                            @case('emola') eMola @break
                                            @case('bank') Banco @break
                                            @case('multicaixa') Multicaixa @break
                                            @default {{ $payment->payment_method }}
                                        @endswitch
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if($payment->status === 'pending' || $payment->status === 'overdue')
                                            <button class="btn btn-sm btn-success" 
                                                    onclick="markAsPaid({{ $payment->id }})"
                                                    title="Marcar como Pago">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        
                                        <button type="button" class="btn btn-sm btn-primary-school" 
                                                onclick="openPaymentDetailsModal({{ $payment->id }}, '{{ $payment->reference_number }}', '{{ ucfirst($payment->type) }}', '{{ $payment->month ? $payment->month.'/'.$payment->year : '-' }}', '{{ number_format($payment->amount, 2, ',', '.') }}', '{{ number_format($payment->discount, 2, ',', '.') }}', '{{ $payment->due_date->format('d/m/Y') }}', '{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '-' }}', '{{ $payment->status }}', '{{ $payment->payment_method ?? '-' }}', '{{ $payment->notes ?? 'Sem observações' }}')"
                                                title="Ver Detalhes do Pagamento">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        @if($payment->status === 'pending')
                                            <button class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhum pagamento registrado.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Resumo Financeiro -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="school-card">
                    <div class="school-card-header">
                        <h3 class="school-card-title">
                            <i class="fas fa-chart-pie"></i>
                            Resumo por Status
                        </h3>
                    </div>
                    <div class="school-card-body">
                        <canvas id="paymentStatusChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="school-card">
                    <div class="school-card-header">
                        <h3 class="school-card-title">
                            <i class="fas fa-calendar"></i>
                            Próximos Vencimentos
                        </h3>
                    </div>
                    <div class="school-card-body">
                        @php
                            $upcomingPayments = $student->payments
                                ->where('status', 'pending')
                                ->where('due_date', '>=', now())
                                ->sortBy('due_date')
                                ->take(5);
                        @endphp
                        
                        @if($upcomingPayments->count() > 0)
                            @foreach($upcomingPayments as $payment)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                    <div>
                                        <strong>{{ $payment->type === 'mensalidade' ? 'Mensalidade' : $payment->type }}</strong>
                                        <br>
                                        <small class="text-muted">Vence: {{ $payment->due_date->format('d/m/Y') }}</small>
                                    </div>
                                    <div class="text-end">
                                        <strong>{{ number_format($payment->amount, 2, ',', '.') }} MT</strong>
                                        <br>
                                        <span class="badge bg-warning">Pendente</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <p>Nenhum pagamento pendente!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Pagamento -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Novo Pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Pagamento *</label>
                                <select class="form-select" name="type" required>
                                    <option value="mensalidade">Mensalidade</option>
                                    <option value="matricula">Matrícula</option>
                                    <option value="material">Material</option>
                                    <option value="uniforme">Uniforme</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Valor *</label>
                                <div class="input-group">
                                    <span class="input-group-text">MT</span>
                                    <input type="number" step="0.01" class="form-control" name="amount" 
                                           value="{{ $student->monthly_fee }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mês</label>
                                <select class="form-select" name="month">
                                    <option value="">Selecione...</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ano</label>
                                <input type="number" class="form-control" name="year" value="{{ current_school_year() }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Data de Vencimento *</label>
                                <input type="date" class="form-control" name="due_date" 
                                       value="{{ now()->addDays(10)->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Método de Pagamento</label>
                                <select class="form-select" name="payment_method">
                                    <option value="">Selecione...</option>
                                    <option value="cash">Dinheiro</option>
                                    <option value="mpesa">MPesa</option>
                                    <option value="emola">eMola</option>
                                    <option value="bank">Transferência Bancária</option>
                                    <option value="multicaixa">Multicaixa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary-school" onclick="submitPayment()">
                    <i class="fas fa-save"></i> Registrar Pagamento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalhes do Pagamento Especifico -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-bold d-flex align-items-center gap-2">
                    <i class="fas fa-receipt"></i> Detalhes do Pagamento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4 pb-3 border-bottom">
                    <small class="text-uppercase tracking-wider text-muted font-semibold">Número de Referência</small>
                    <h3 class="font-mono font-bold text-dark my-1" id="detailRef">---</h3>
                    <span id="detailStatusBadge" class="badge bg-success">---</span>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Tipo de Pagamento:</span>
                        <strong id="detailType" class="text-dark">---</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Mês / Ano:</span>
                        <strong id="detailPeriod" class="text-dark">---</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Valor Base:</span>
                        <strong id="detailAmount" class="text-dark">--- MT</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Desconto:</span>
                        <strong id="detailDiscount" class="text-danger">--- MT</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Data Vencimento:</span>
                        <strong id="detailDueDate" class="text-dark">---</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Data Pagamento:</span>
                        <strong id="detailPayDate" class="text-dark">---</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Método de Pagamento:</span>
                        <strong id="detailMethod" class="text-dark">---</strong>
                    </div>
                    <div class="py-2">
                        <span class="text-muted d-block mb-1">Observações:</span>
                        <p id="detailNotes" class="bg-light p-2 rounded small text-secondary mb-0">---</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Fechar</button>
                <a id="printReceiptBtn" href="#" class="btn btn-success btn-sm rounded-pill px-4" onclick="showToast('Imprimindo comprovativo de pagamento...', 'info')">
                    <i class="fas fa-print me-1"></i> Imprimir Recibo
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paidCount = {{ $paymentStats['total_paid'] }};
    const pendingCount = {{ $paymentStats['total_pending'] }};
    const overdueCount = {{ $paymentStats['total_overdue'] }};
    const totalPayments = paidCount + pendingCount + overdueCount;

    // Gráfico de status de pagamentos
    const ctx = document.getElementById('paymentStatusChart').getContext('2d');
    const paymentStatusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: totalPayments > 0 ? ['Pago', 'Pendente', 'Em Atraso'] : ['Sem Lançamentos'],
            datasets: [{
                data: totalPayments > 0 ? [paidCount, pendingCount, overdueCount] : [1],
                backgroundColor: totalPayments > 0 ? ['#28a745', '#ffc107', '#dc3545'] : ['#cbd5e1'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: totalPayments > 0 ? 'Distribuição por Status' : 'Aguardando Registos Financeiros'
                }
            }
        }
    });

    // Função para abrir modal de detalhes de pagamento específico
    window.openPaymentDetailsModal = function(id, ref, type, period, amount, discount, dueDate, payDate, status, method, notes) {
        document.getElementById('detailRef').textContent = ref;
        document.getElementById('detailType').textContent = type;
        document.getElementById('detailPeriod').textContent = period;
        document.getElementById('detailAmount').textContent = amount + ' MT';
        document.getElementById('detailDiscount').textContent = discount + ' MT';
        document.getElementById('detailDueDate').textContent = dueDate;
        document.getElementById('detailPayDate').textContent = payDate;
        document.getElementById('detailMethod').textContent = method;
        document.getElementById('detailNotes').textContent = notes;

        const badge = document.getElementById('detailStatusBadge');
        const statusMap = {
            paid: { label: 'Pago', class: 'bg-success' },
            pending: { label: 'Pendente', class: 'bg-warning' },
            overdue: { label: 'Em Atraso', class: 'bg-danger' },
            cancelled: { label: 'Cancelado', class: 'bg-secondary' }
        };
        const s = statusMap[status] || { label: status, class: 'bg-secondary' };
        badge.textContent = s.label;
        badge.className = 'badge ' + s.class;

        new bootstrap.Modal(document.getElementById('paymentDetailModal')).show();
    };

    // Função para marcar pagamento como pago
    window.markAsPaid = function(paymentId) {
        confirmAction('Deseja marcar este pagamento como pago?', function() {
            showToast('Funcionalidade de marcar como pago será implementada em breve.', 'info');
        }, {title: 'Confirmar Pagamento', confirmText: 'Marcar como Pago', danger: false});
    };

    // Função para submeter novo pagamento
    window.submitPayment = function() {
        showToast('Funcionalidade de registrar pagamento será implementada em breve.', 'info');
    };
});
</script>
@endpush