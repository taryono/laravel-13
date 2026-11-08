<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClassLevelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'code' => [
                'required', 'string', 'max:20',
                Rule::unique('class_levels')->where(fn($q) =>
                    $q->where('organization_id', $this->organization_id)
                )
            ],
            'name' => 'required|string|max:50',
            'level_order' => 'required|integer',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }
}
