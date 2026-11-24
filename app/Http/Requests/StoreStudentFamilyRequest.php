<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentFamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $organizationId = $this->input('organization_id', auth()->user()?->organization_id);

        return [
            'organization_id'   => ['required', 'integer', 'exists:organizations,id'],
            'family_code'       => [
                'required',
                'string',
                'max:50',
                Rule::unique('student_families', 'family_code')
                    ->where(fn ($query) => $query
                        ->where('organization_id', $organizationId)
                        ->whereNull('deleted_at')
                    ),
            ],
            'family_name'       => ['required', 'string', 'max:150'],
            'primary_parent_id' => ['nullable', 'integer', 'exists:parents,id'],
            'notes'             => ['nullable', 'string'],
            'is_active'         => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'family_code.unique' => 'Kode keluarga sudah digunakan pada organisasi ini.',
        ];
    }
}