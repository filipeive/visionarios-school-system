@extends('layouts.app')

@section('title', 'Novo Pagamento')
@section('page-title', 'Novo Pagamento')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pagamentos</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-plus-circle"></i> Registrar Novo Pagamento
                </div>
                <div class="school-card-body">
                    <form action="{{ route('payments.store') }}" method="POST" id="payment-form">
                        @csrf
                        
                        {{-- Seleção de Aluno --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-graduate text-primary"></i> Aluno *
                            </label>
                            <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                <option value="">Selecione o aluno...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                            data-fee="{{ $student->currentEnrollment?->monthly_fee ?? $student->monthly_fee }}"
                                            data-class="{{ $student->currentEnrollment?->class?->name ?? 'Sem turma' }}"
                                            {{ old('student_id', $selectedStudent?->id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->student_number }} - {{ $student->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Info do Aluno Selecionado --}}
                        <div id="student-info" class="alert alert-info-school mb-4" style="display: none;">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <strong>Turma:</strong> <span id="info-class">-</span> | 
                                <strong>Mensalidade:</strong> <span id="info-fee">-</span> MT
                            </div>
                        </div>

                        <div class="row">
                            {{-- Tipo de Pagamento --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-tag text-primary"></i> Tipo de Pagamento *
                                </label>
                                <select name="type" id="payment_type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Selecione...</option>
                                    @foreach($types as $value => $label)
                                        <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Valor --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-money-bill text-primary"></i> Valor (MT) *
                                </label>
                                <input type="number" step="0.01" name="amount" id="amount" 
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Mês --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar text-primary"></i> Mês
                                </label>
                                <select name="month" id="month" class="form-select @error('month') is-invalid @enderror">
                                    <option value="">N/A</option>
                                    @foreach($months as $num => $name)
                                        <option value="{{ $num }}" {{ old('month', date('n')) == $num ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ano --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt text-primary"></i> Ano *
                                </label>
                                <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                        <option value="{{ $y }}" {{ old('year', date('Y')) == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Data de Vencimento --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-clock text-primary"></i> Vencimento *
                                </label>
                                <input type="date" name="due_date" 
                                       class="form-control @error('due_date') is-invalid @enderror"
                                       value="{{ old('due_date', date('Y-m-10')) }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Desconto --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-percent text-success"></i> Desconto (MT)
                                </label>
                                <input type="number" step="0.01" name="discount" 
                                       class="form-control @error('discount') is-invalid @enderror"
                                       value="{{ old('discount', 0) }}" min="0">
                                @error('discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Total Calculado --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calculator text-primary"></i> Total a Pagar
                                </label>
                                <div class="form-control bg-light" id="total-display">
                                    <strong>0,00 MT</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Observações --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-comment text-primary"></i> Observações
                            </label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" placeholder="Observações adicionais...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Botões --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary-school">
                                <i class="fas fa-save"></i> Registrar Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const typeSelect = document.getElementById('payment_type');
    const amountInput = document.getElementById('amount');
    const discountInput = document.querySelector('input[name="discount"]');
    const studentInfo = document.getElementById('student-info');
    const totalDisplay = document.getElementById('total-display');

    // Atualizar info do aluno
    studentSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('info-class').textContent = selected.dataset.class;
            document.getElementById('info-fee').textContent = parseFloat(selected.dataset.fee).toLocaleString('pt-MZ');
            studentInfo.style.display = 'flex';
            
            // Auto-preencher valor se for mensalidade
            if (typeSelect.value === 'mensalidade') {
                amountInput.value = selected.dataset.fee;
            }
        } else {
            studentInfo.style.display = 'none';
        }
        calculateTotal();
    });

    // Atualizar valor baseado no tipo
    typeSelect.addEventListener('change', function() {
        const selected = studentSelect.options[studentSelect.selectedIndex];
        if (this.value === 'mensalidade' && selected && selected.dataset.fee) {
            amountInput.value = selected.dataset.fee;
        } else if (this.value === 'matricula') {
            amountInput.value = 500;
        }
        calculateTotal();
    });

    // Calcular total
    function calculateTotal() {
        const amount = parseFloat(amountInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const total = amount - discount;
        totalDisplay.innerHTML = `<strong>${total.toLocaleString('pt-MZ', {minimumFractionDigits: 2})} MT</strong>`;
    }

    amountInput.addEventListener('input', calculateTotal);
    discountInput.addEventListener('input', calculateTotal);

    // Trigger inicial
    if (studentSelect.value) {
        studentSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush