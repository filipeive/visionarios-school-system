<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('manage_enrollments');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['required', 'exists:students,id'],
            'action' => ['required', 'in:promote,retain'],
            'target_class_id' => [
                'required_if:action,promote',
                'nullable',
                'exists:classes,id',
                function ($attribute, $value, $fail) {
                    if ($this->action === 'promote' && empty($value)) {
                        $fail('A turma de destino é obrigatória para promoção.');
                    }
                },
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'student_ids.required' => 'Selecione pelo menos um aluno.',
            'student_ids.*.exists' => 'Um ou mais alunos seleccionados são inválidos.',
            'action.required' => 'A acção é obrigatória.',
            'action.in' => 'A acção deve ser "promote" ou "retain".',
            'target_class_id.required_if' => 'A turma de destino é obrigatória para promoção.',
            'target_class_id.exists' => 'A turma de destino é inválida.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->student_ids)) {
            $this->merge([
                'student_ids' => json_decode($this->student_ids, true) ?? explode(',', $this->student_ids),
            ]);
        }
    }
}
