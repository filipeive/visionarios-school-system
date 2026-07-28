@extends('layouts.app')

@section('title', 'Novo Pagamento')
@section('page-title', 'Novo Pagamento')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}" class="no-underline"><i class="fas fa-wallet me-1"></i>Pagamentos</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
    <div class="payments-create mx-auto max-w-5xl">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <form action="{{ route('payments.store') }}" method="POST" id="payment-form" class="grid gap-4 md:grid-cols-12">
                @csrf

                <div class="md:col-span-12">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Aluno *</label>
                    <select name="student_id" id="student_id"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('student_id') border-rose-400 @enderror"
                        required>
                        <option value="">Selecione o aluno...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" data-fee="{{ $student->currentEnrollment?->monthly_fee ?? $student->monthly_fee }}"
                                data-class="{{ $student->currentEnrollment?->class?->name ?? 'Sem turma' }}"
                                {{ old('student_id', $selectedStudent?->id) == $student->id ? 'selected' : '' }}>
                                {{ $student->student_number }} - {{ $student->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div id="student-info" class="md:col-span-12 hidden rounded-lg border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">
                    <strong>Turma:</strong> <span id="info-class">-</span> |
                    <strong>Mensalidade:</strong> <span id="info-fee">-</span> MT
                </div>

                <div class="md:col-span-6">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Tipo de Pagamento *</label>
                    <select name="type" id="payment_type"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('type') border-rose-400 @enderror"
                        required>
                        <option value="">Selecione...</option>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-6">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Valor (MT) *</label>
                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('amount') border-rose-400 @enderror"
                        required>
                    @error('amount')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-4">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Mes</label>
                    <select name="month" id="month"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('month') border-rose-400 @enderror">
                        <option value="">N/A</option>
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ old('month', date('n')) == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('month')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-4">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Ano *</label>
                    <select name="year"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('year') border-rose-400 @enderror"
                        required>
                        @for($y = current_school_year() - 1; $y <= current_school_year() + 1; $y++)
                            <option value="{{ $y }}" {{ old('year', current_school_year()) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    @error('year')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-4">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Vencimento *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', current_school_year() . '-' . date('m') . '-10') }}"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('due_date') border-rose-400 @enderror"
                        required>
                    @error('due_date')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-6">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Desconto (MT)</label>
                    <input type="number" step="0.01" name="discount" value="{{ old('discount', 0) }}" min="0"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('discount') border-rose-400 @enderror">
                    @error('discount')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-6">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Total a Pagar</label>
                    <div id="total-display" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-800">0,00 MT</div>
                </div>

                <div class="md:col-span-12">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Observacoes</label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('notes') border-rose-400 @enderror"
                        placeholder="Observacoes adicionais...">{{ old('notes') }}</textarea>
                    @error('notes')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-12 flex items-center justify-between pt-2">
                    <a href="{{ route('payments.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 no-underline hover:bg-slate-50"><i class="fas fa-arrow-left me-2"></i>Voltar</a>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800"><i class="fas fa-floppy-disk me-2"></i>Registrar Pagamento</button>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        [data-bs-theme="dark"] .payments-create .bg-white { background-color: var(--card-bg) !important; }
        [data-bs-theme="dark"] .payments-create .bg-slate-50 { background-color: rgba(148, 163, 184, 0.08) !important; }
        [data-bs-theme="dark"] .payments-create .border-slate-200,
        [data-bs-theme="dark"] .payments-create .border-slate-300 { border-color: var(--border-color) !important; }
        [data-bs-theme="dark"] .payments-create .text-slate-800,
        [data-bs-theme="dark"] .payments-create .text-slate-700,
        [data-bs-theme="dark"] .payments-create .text-slate-600 { color: var(--text-secondary) !important; }
    </style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const typeSelect = document.getElementById('payment_type');
    const amountInput = document.getElementById('amount');
    const discountInput = document.querySelector('input[name="discount"]');
    const studentInfo = document.getElementById('student-info');
    const totalDisplay = document.getElementById('total-display');

    studentSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('info-class').textContent = selected.dataset.class;
            document.getElementById('info-fee').textContent = parseFloat(selected.dataset.fee || 0).toLocaleString('pt-MZ');
            studentInfo.classList.remove('hidden');

            if (typeSelect.value === 'mensalidade') {
                amountInput.value = selected.dataset.fee;
            }
        } else {
            studentInfo.classList.add('hidden');
        }
        calculateTotal();
    });

    typeSelect.addEventListener('change', function() {
        const selected = studentSelect.options[studentSelect.selectedIndex];
        if (this.value === 'mensalidade' && selected && selected.dataset.fee) {
            amountInput.value = selected.dataset.fee;
        } else if (this.value === 'matricula') {
            amountInput.value = 500;
        }
        calculateTotal();
    });

    function calculateTotal() {
        const amount = parseFloat(amountInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const total = amount - discount;
        totalDisplay.textContent = `${total.toLocaleString('pt-MZ', {minimumFractionDigits: 2})} MT`;
    }

    amountInput.addEventListener('input', calculateTotal);
    discountInput.addEventListener('input', calculateTotal);

    if (studentSelect.value) {
        studentSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
