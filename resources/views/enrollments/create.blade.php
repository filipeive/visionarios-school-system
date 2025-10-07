@extends('layouts.app')

@section('title', 'Nova Matrícula')
@section('page-title', 'Nova Matrícula')
@section('page-title-icon', 'fas fa-user-plus')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
    <li class="breadcrumb-item active">Nova Matrícula</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-user-plus"></i>
                Dados da Matrícula
            </div>
            <div class="school-card-body">
                <form action="{{ route('enrollments.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aluno *</label>
                            <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                <option value="">Selecione o aluno</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->student_number }} - {{ $student->first_name }} {{ $student->last_name }}
                                        @if($student->birthdate)
                                            ({{ $student->age }} anos)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($students->isEmpty())
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Todos os alunos já possuem matrícula ativa para o ano corrente.
                                    <a href="{{ route('students.create') }}">Cadastrar novo aluno</a>
                                </small>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Turma *</label>
                            <select name="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                                <option value="">Selecione a turma</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ano Letivo *</label>
                            <input type="number" name="school_year" class="form-control @error('school_year') is-invalid @enderror" 
                                   value="{{ old('school_year', $currentYear) }}" min="2020" max="2030" required>
                            @error('school_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Data da Matrícula *</label>
                            <input type="date" name="enrollment_date" class="form-control @error('enrollment_date') is-invalid @enderror" 
                                   value="{{ old('enrollment_date', date('Y-m-d')) }}" required>
                            @error('enrollment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Dia de Pagamento *</label>
                            <input type="number" name="payment_day" class="form-control @error('payment_day') is-invalid @enderror" 
                                   value="{{ old('payment_day', 10) }}" min="1" max="28" required>
                            @error('payment_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mensalidade (MZN) *</label>
                            <input type="number" step="0.01" name="monthly_fee" class="form-control @error('monthly_fee') is-invalid @enderror" 
                                   value="{{ old('monthly_fee', 2500.00) }}" required>
                            @error('monthly_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Valor que o aluno pagará mensalmente</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Taxa de Matrícula (MZN) *</label>
                            <input type="number" step="0.01" name="enrollment_fee" class="form-control @error('enrollment_fee') is-invalid @enderror" 
                                   value="{{ old('enrollment_fee', 500.00) }}" required>
                            @error('enrollment_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Valor único pago no ato da matrícula</small>
                        </div>

                        <!-- Opção de Pagamento Imediato -->
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="pay_now" id="pay_now" value="1" {{ old('pay_now') ? 'checked' : '' }}>
                                <label class="form-check-label" for="pay_now">
                                    <strong>Pagamento realizado agora</strong>
                                </label>
                                <small class="text-muted d-block">
                                    Marque esta opção se a taxa de matrícula já foi paga. A matrícula será ativada automaticamente.
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3" id="payment_method_field" style="display: none;">
                            <label class="form-label">Método de Pagamento</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">Dinheiro</option>
                                <option value="mpesa">M-Pesa</option>
                                <option value="emola">e-Mola</option>
                                <option value="bank">Transferência Bancária</option>
                                <option value="multicaixa">Multicaixa</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="observations" class="form-control @error('observations') is-invalid @enderror" 
                                      rows="3">{{ old('observations') }}</textarea>
                            @error('observations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Informação:</strong> 
                        <span id="status_info">
                            Se a taxa de matrícula for maior que 0, a matrícula ficará com status "Pendente" até a confirmação do pagamento.
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('enrollments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary-school" {{ $students->isEmpty() ? 'disabled' : '' }}>
                            <i class="fas fa-save"></i> Realizar Matrícula
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const enrollmentFeeInput = document.querySelector('input[name="enrollment_fee"]');
    const payNowCheckbox = document.querySelector('input[name="pay_now"]');
    const paymentMethodField = document.getElementById('payment_method_field');
    const statusInfo = document.getElementById('status_info');

    function updateStatusInfo() {
        const enrollmentFee = parseFloat(enrollmentFeeInput.value) || 0;
        const payNow = payNowCheckbox.checked;

        if (enrollmentFee === 0) {
            statusInfo.textContent = 'Matrícula será ativada automaticamente (sem taxa de matrícula).';
        } else if (payNow) {
            statusInfo.textContent = 'Matrícula será ativada automaticamente (pagamento confirmado).';
        } else {
            statusInfo.textContent = 'Matrícula ficará com status "Pendente" até a confirmação do pagamento.';
        }
    }

    enrollmentFeeInput.addEventListener('input', updateStatusInfo);
    
    payNowCheckbox.addEventListener('change', function() {
        paymentMethodField.style.display = this.checked ? 'block' : 'none';
        updateStatusInfo();
    });

    // Initial update
    updateStatusInfo();
    if (payNowCheckbox.checked) {
        paymentMethodField.style.display = 'block';
    }
});
</script>
@endpush