<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentFamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $family         = $this->route('student_family');
        $familyId       = is_object($family) ? $family->id : $family;
        $organizationId = $this->input('organization_id', is_object($family) ? $family->organization_id : null);

        return [
            'organization_id'   => ['sometimes', 'required', 'integer', 'exists:organizations,id'],
            'family_code'       => [
                'required',
                'string',
                'max:50',
                Rule::unique('student_families', 'family_code')
                    ->where(fn ($query) => $query
                        ->where('organization_id', $organizationId)
                        ->whereNull('deleted_at')
                    )
                    ->ignore($familyId),
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
            'family_code.unique' => 'Kode keluarga sudah digunakan oleh keluarga lain.',
        ];
    }
}