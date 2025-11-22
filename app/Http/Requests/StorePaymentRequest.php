<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create_payments');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:matricula,mensalidade,material,uniforme,outro',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
            'due_date' => 'required|date|after_or_equal:today',
            'discount' => 'nullable|numeric|min:0|lte:amount',
            'penalty' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'aluno',
            'type' => 'tipo de pagamento',
            'amount' => 'valor',
            'month' => 'mês',
            'year' => 'ano',
            'due_date' => 'data de vencimento',
            'discount' => 'desconto',
            'penalty' => 'multa',
            'notes' => 'observações',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'Selecione um aluno.',
            'student_id.exists' => 'O aluno selecionado não existe.',
            'type.required' => 'Selecione o tipo de pagamento.',
            'type.in' => 'Tipo de pagamento inválido.',
            'amount.required' => 'Informe o valor do pagamento.',
            'amount.numeric' => 'O valor deve ser numérico.',
            'amount.min' => 'O valor mínimo é 0,01 MT.',
            'amount.max' => 'O valor máximo é 999.999,99 MT.',
            'month.min' => 'Mês inválido.',
            'month.max' => 'Mês inválido.',
            'year.required' => 'Informe o ano.',
            'year.min' => 'Ano inválido.',
            'year.max' => 'Ano inválido.',
            'due_date.required' => 'Informe a data de vencimento.',
            'due_date.date' => 'Data de vencimento inválida.',
            'due_date.after_or_equal' => 'A data de vencimento não pode ser no passado.',
            'discount.lte' => 'O desconto não pode ser maior que o valor do pagamento.',
            'notes.max' => 'As observações não podem ter mais de 500 caracteres.',
        ];
    }
}