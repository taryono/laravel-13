<?php
// app/Http/Requests/StudentParentRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'relationship' => ['required', 'in:FATHER,MOTHER,GUARDIAN'],
            'is_primary' => ['nullable', 'boolean'],
        ];

        // student_id & parent_id hanya diinput saat create (saat edit diambil dari URL)
        if ($this->isMethod('post')) {
            $rules['student_id'] = ['required', 'exists:students,id'];
            $rules['parent_id'] = [
                'required',
                'exists:parents,id',
                Rule::unique('student_parents', 'parent_id')->where(
                    fn($q) => $q->where('student_id', $this->input('student_id'))->whereNull('deleted_at')
                ),
            ];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'student_id' => 'siswa',
            'parent_id' => 'orang tua',
            'relationship' => 'hubungan',
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.unique' => 'Orang tua ini sudah terhubung dengan siswa tersebut.',
        ];
    }
}