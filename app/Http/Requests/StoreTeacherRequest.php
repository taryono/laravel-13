<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeacherRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'school_id' => 'nullable|exists:schools,id',
            'user_id' => 'nullable|exists:users,id',
            'nip' => [
                'nullable', 'string', 'max:50',
                Rule::unique('teachers')->where(fn($q) =>
                    $q->where('organization_id', $this->organization_id)
                )
            ],
            'name' => 'required|string|max:150',
            'specialization' => 'nullable|string|max:100',
            'education_level_id' => 'nullable|integer',
            'join_date' => 'nullable|date',
            'status' => 'nullable|in:ACTIVE,RESIGNED,RETIRED',
            'subject' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'employee_id' => 'nullable|integer',
        ];
    }
}
