@extends('layouts.app')

@section('title', 'Editar Matrícula')
@section('page-title', 'Editar Matrícula')
@section('page-title-icon', 'fas fa-edit')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('enrollments.show', $enrollment->id) }}">Detalhes</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-edit"></i>
                Editar Matrícula
            </div>
            <div class="school-card-body">
                <form action="{{ route('enrollments.update', $enrollment->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="row">
                        <!-- Informações do Aluno (somente leitura) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aluno</label>
                            <div class="form-control bg-light">
                                <strong>{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</strong>
                                <br>
                                <small class="text-muted">
                                    Nº: {{ $enrollment->student->student_number ?? 'N/A' }}
                                    @if($enrollment->student->birthdate)
                                        | {{ $enrollment->student->age }} anos
                                    @endif
                                </small>
                            </div>
                            <small class="text-muted">Não é possível alterar o aluno. Crie uma nova matrícula se necessário.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ano Letivo</label>
                            <div class="form-control bg-light">
                                <strong>{{ $enrollment->school_year }}</strong>
                            </div>
                            <small class="text-muted">Ano letivo não pode ser alterado.</small>
                        </div>

                        <!-- Campos editáveis -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Turma *</label>
                            <select name="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                                <option value="">Selecione a turma</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $enrollment->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $enrollment->status) == 'active' ? 'selected' : '' }}>Ativa</option>
                                <option value="inactive" {{ old('status', $enrollment->status) == 'inactive' ? 'selected' : '' }}>Inativa</option>
                                <option value="cancelled" {{ old('status', $enrollment->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>  
                                <option value="pending" {{ old('status', $enrollment->status) == 'pending' ? 'selected' : '' }}>Pendente</option>
                                <option value="transferred" {{ old('status', $enrollment->status) == 'transferred' ? 'selected' : '' }}>Transferida</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mensalidade (MZN) *</label>
                            <input type="number" step="0.01" name="monthly_fee" 
                                   class="form-control @error('monthly_fee') is-invalid @enderror" 
                                   value="{{ old('monthly_fee', $enrollment->monthly_fee) }}" required>
                            @error('monthly_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Valor da mensalidade mensal</small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Dia de Pagamento *</label>
                            <input type="number" name="payment_day" 
                                   class="form-control @error('payment_day') is-invalid @enderror" 
                                   value="{{ old('payment_day', $enrollment->payment_day) }}" min="1" max="28" required>
                            @error('payment_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Dia do mês para pagamento</small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Data da Matrícula</label>
                            <input type="date" name="enrollment_date" 
                                   class="form-control @error('enrollment_date') is-invalid @enderror" 
                                   value="{{ old('enrollment_date', $enrollment->enrollment_date->format('Y-m-d')) }}" readonly>
                            @error('enrollment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Data original não pode ser alterada</small>
                        </div>

                        @if($enrollmentPayment)
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Taxa de Matrícula</label>
                            <div class="form-control bg-light">
                                <strong>{{ number_format($enrollmentPayment->amount, 2, ',', '.') }} MZN</strong>
                                <span class="badge bg-{{ $enrollmentPayment->status == 'paid' ? 'success' : 'warning' }} ms-2">
                                    {{ $enrollmentPayment->status == 'paid' ? 'Pago' : 'Pendente' }}
                                </span>
                            </div>
                            <small class="text-muted">
                                Taxa registrada no sistema financeiro. 
                                <a href="{{ route('payments.store', $enrollmentPayment->id) }}" class="text-primary">Editar pagamento</a>
                            </small>
                        </div>
                        @endif

                        @if($enrollment->cancellation_date)
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data de Cancelamento</label>
                            <div class="form-control bg-light">
                                <strong>{{ $enrollment->cancellation_date->format('d/m/Y') }}</strong>
                            </div>
                            <small class="text-muted">Data em que a matrícula foi cancelada/transferida</small>
                        </div>
                        @endif

                        <div class="col-12 mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="observations" class="form-control @error('observations') is-invalid @enderror" 
                                      rows="4" placeholder="Observações sobre a matrícula...">{{ old('observations', $enrollment->observations) }}</textarea>
                            @error('observations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Informações de Auditoria -->
                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-calendar-plus"></i>
                                Criado em: {{ $enrollment->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="fas fa-calendar-check"></i>
                                Última atualização: {{ $enrollment->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('enrollments.show', $enrollment->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary-school">
                                <i class="fas fa-save"></i> Atualizar Matrícula
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card de Ações Rápidas -->
        <div class="school-card mt-4">
            <div class="school-card-header">
                <i class="fas fa-bolt"></i>
                Ações Rápidas
            </div>
            <div class="school-card-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <a href="{{ route('enrollments.print', $enrollment->id) }}" class="btn btn-outline-primary w-100" target="_blank">
                            <i class="fas fa-print"></i> Imprimir
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('students.show', $enrollment->student_id) }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-user-graduate"></i> Ver Aluno
                        </a>
                    </div>
                    <div class="col-md-4">
                        @if($enrollmentPayment)
                        <a href="{{ route('payments.show', $enrollmentPayment->id) }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-money-bill-wave"></i> Ver Pagamento
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação para Status Cancelado -->
<div class="modal fade" id="confirmCancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Cancelamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja cancelar esta matrícula?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Atenção:</strong> Ao cancelar a matrícula, o aluno não poderá mais frequentar as aulas 
                    e todos os pagamentos futuros serão marcados como cancelados.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Manter Ativa</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">Confirmar Cancelamento</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('select[name="status"]');
    const cancelModal = new bootstrap.Modal(document.getElementById('confirmCancelModal'));
    
    let originalStatus = statusSelect.value;
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'cancelled' && originalStatus !== 'cancelled') {
            // Mostrar modal de confirmação para cancelamento
            cancelModal.show();
            
            // Se o usuário confirmar o cancelamento
            document.getElementById('confirmCancelBtn').onclick = function() {
                cancelModal.hide();
                originalStatus = 'cancelled';
            };
            
            // Se o usuário fechar o modal, reverter para o status anterior
            cancelModal._element.addEventListener('hidden.bs.modal', function() {
                if (originalStatus !== 'cancelled') {
                    statusSelect.value = originalStatus;
                }
            });
        }
        originalStatus = this.value;
    });
});
</script>
@endpush