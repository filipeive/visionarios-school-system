<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'birthdate' => ['required', 'date', 'before:today', 'after:'.now()->subYears(25)->toDateString()],
            'birth_place' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'parent_id' => ['required', 'exists:parents,user_id'],
            'emergency_contact' => ['required', 'string', 'max:255'],
            'emergency_phone' => ['required', 'string', 'max:20', 'regex:/^(\+?258)?[8-9]\d{8}$/'],
            'monthly_fee' => ['required', 'numeric', 'min:0'],
            'medical_certificate' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'has_special_needs' => ['nullable', 'boolean'],
            'special_needs_description' => ['nullable', 'string', 'max:1000'],
            'observations' => ['nullable', 'string', 'max:1000'],
            'passport_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'emergency_phone.regex' => 'O telefone deve ser um número moçambicano válido (8x ou 9xxxxxxxxx).',
            'birthdate.before' => 'A data de nascimento deve ser anterior a hoje.',
            'birthdate.after' => 'O aluno não pode ter mais de 25 anos.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('emergency_phone')) {
            $phone = $this->emergency_phone;
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (strlen($phone) === 9 && in_array($phone[0], ['8', '9'])) {
                $phone = '258'.$phone;
            }
            $this->merge(['emergency_phone' => $phone]);
        }
    }
}
