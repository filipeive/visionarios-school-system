@extends('layouts.app')

@section('title', 'Pagamentos')
@section('page-title', 'Pagamentos Pendentes')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-money-bill-wave me-2"></i> Lista de Pagamentos
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-school">
                            <thead>
                                <tr>
                                    <th>Referência</th>
                                    <th>Aluno</th>
                                    <th>Descrição</th>
                                    <th>Vencimento</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td class="font-monospace">{{ $payment->reference_number }}</td>
                                        <td>{{ $payment->student->first_name }}</td>
                                        <td>
                                            {{ ucfirst($payment->type) }}
                                            @if($payment->month) - {{ $payment->month_name }}/{{ $payment->year }} @endif
                                        </td>
                                        <td
                                            class="{{ $payment->due_date < now() && $payment->status == 'pending' ? 'text-danger fw-bold' : '' }}">
                                            {{ $payment->due_date->format('d/m/Y') }}
                                        </td>
                                        <td class="fw-bold">
                                            {{ number_format($payment->total_amount, 2, ',', '.') }} MT
                                        </td>
                                        <td>
                                            {!! $payment->status_badge !!}
                                        </td>
                                        <td>
                                            @if($payment->status == 'pending' || $payment->status == 'overdue')
                                                <button type="button" class="btn btn-sm btn-primary-school"
                                                    onclick="openPaymentModal({{ $payment->id }}, '{{ $payment->reference_number }}', {{ $payment->total_amount }})">
                                                    <i class="fas fa-credit-card me-1"></i> Pagar
                                                </button>
                                            @else
                                                <span class="text-muted"><i class="fas fa-check-circle me-1"></i> Pago</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                                            Nenhum pagamento pendente encontrado.
                                        </td>
                                    </tr>
                                @endforelse
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

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-wallet me-2"></i>Pagamento Online</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">
                        Pagamento da referência <strong id="modalReference"></strong> no valor de <strong
                            id="modalAmount"></strong>.
                    </p>

                    <form id="paymentForm">
                        <input type="hidden" id="paymentId">

                        <div class="mb-3">
                            <label for="provider" class="form-label fw-bold">Método de Pagamento</label>
                            <select class="form-select" id="provider" required>
                                <option value="mpesa">M-Pesa</option>
                                <option value="emola">e-Mola</option>
                                <option value="mkesh">mKesh</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Número de Telefone</label>
                            <input type="text" class="form-control" id="phone" placeholder="841234567" required>
                            <div class="form-text">Insira o número que receberá a notificação de pagamento.</div>
                        </div>

                        <div id="paymentMessage" class="alert d-none" role="alert"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary-school" id="btnConfirmPayment" onclick="processPayment()">
                        <span id="btnText">Confirmar Pagamento</span>
                        <span id="btnLoading" class="d-none"><i
                                class="fas fa-spinner fa-spin me-2"></i>Processando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let paymentModal;

            document.addEventListener('DOMContentLoaded', function () {
                paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            });

            function openPaymentModal(id, reference, amount) {
                document.getElementById('paymentId').value = id;
                document.getElementById('modalReference').textContent = reference;
                document.getElementById('modalAmount').textContent = new Intl.NumberFormat('pt-MZ', { style: 'currency', currency: 'MZN' }).format(amount);
                document.getElementById('phone').value = '';
                document.getElementById('paymentMessage').classList.add('d-none');

                paymentModal.show();
            }

            function processPayment() {
                const id = document.getElementById('paymentId').value;
                const phone = document.getElementById('phone').value;
                const provider = document.getElementById('provider').value;
                const btnConfirm = document.getElementById('btnConfirmPayment');
                const btnText = document.getElementById('btnText');
                const btnLoading = document.getElementById('btnLoading');
                const messageDiv = document.getElementById('paymentMessage');

                if (!phone) {
                    showMessage('Por favor, insira o número de telefone.', 'danger');
                    return;
                }

                // Loading state
                btnConfirm.disabled = true;
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
                messageDiv.classList.add('d-none');

                fetch(`/parent/payments/${id}/pay-online`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        phone_number: phone,
                        provider: provider
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage(data.message, 'success');
                            setTimeout(() => {
                                paymentModal.hide();
                                window.location.reload();
                            }, 3000);
                        } else {
                            showMessage(data.message || 'Erro ao processar pagamento.', 'danger');
                            resetButton();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('Erro ao processar pagamento. Tente novamente.', 'danger');
                        resetButton();
                    });
            }

            function showMessage(text, type) {
                const messageDiv = document.getElementById('paymentMessage');
                messageDiv.textContent = text;
                messageDiv.className = `alert alert-${type}`;
                messageDiv.classList.remove('d-none');
            }

            function resetButton() {
                const btnConfirm = document.getElementById('btnConfirmPayment');
                const btnText = document.getElementById('btnText');
                const btnLoading = document.getElementById('btnLoading');

                btnConfirm.disabled = false;
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
            }
        </script>
    @endpush
@endsection