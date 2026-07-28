<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGradeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->can('create_grades');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Sistema de Avaliação de Moçambique (Ensino Primário)
        // ACS1, ACS2, ACS3: Avaliação Contínua e Sistemática (3 provas por trimestre)
        // ACP: Avaliação Contínua Parcial (prova trimestral)
        // ACF: Avaliação Contínua Final (exame da 6ª classe)
        $assessmentTypes = [
            'ACS1',  // Avaliação Contínua e Sistemática 1
            'ACS2',  // Avaliação Contínua e Sistemática 2
            'ACS3',  // Avaliação Contínua e Sistemática 3
            'ACP',   // Avaliação Contínua Parcial (prova trimestral)
            'ACF',   // Avaliação Contínua Final
        ];

        $maxYear = now()->year + 1;

        return [
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'grade' => ['required', 'numeric', 'min:0', 'max:20'],
            'assessment_type' => ['required', Rule::in($assessmentTypes)],
            'term' => ['required', Rule::in([1, 2, 3])],
            'year' => ['required', 'integer', 'min:2020', 'max:'.$maxYear],
            'date_recorded' => ['required', 'date', 'before_or_equal:today'],
            'comments' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'grade.min' => 'A nota deve ser pelo menos 0.',
            'grade.max' => 'A nota não pode exceder 20.',
            'date_recorded.before_or_equal' => 'A data da avaliação não pode ser futura.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $studentId = $this->student_id;
            $classId = $this->class_id;

            if ($studentId && $classId) {
                $isEnrolled = \App\Models\Enrollment::where('student_id', $studentId)
                    ->where('class_id', $classId)
                    ->where('status', 'active')
                    ->exists();

                if (! $isEnrolled) {
                    $validator->errors()->add(
                        'student_id',
                        'O aluno não está matrículado nesta turma.'
                    );
                }
            }
        });
    }
}
