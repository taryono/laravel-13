<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiblingDiscountProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $program        = $this->route('sibling_discount_program');
        $programId      = is_object($program) ? $program->id : $program;
        $organizationId = $this->input('organization_id', is_object($program) ? $program->organization_id : null);

        return [
            // Data Program Utama
            'organization_id'   => ['sometimes', 'required', 'integer', 'exists:organizations,id'],
            'school_id'         => ['nullable', 'integer', 'exists:schools,id'],
            'code'              => [
                'required',
                'string',
                'max:50',
                Rule::unique('sibling_discount_programs', 'code')
                    ->where(fn ($query) => $query
                        ->where('organization_id', $organizationId)
                        ->whereNull('deleted_at')
                    )
                    ->ignore($programId),
            ],
            'name'              => ['required', 'string', 'max:150'],
            'description'       => ['nullable', 'string'],
            'min_sibling_count' => ['required', 'integer', 'min:2'],
            'calculation_basis' => ['required', Rule::in(['percentage', 'fixed_amount'])],
            'allocation_method' => ['required', Rule::in(['all_members', 'selected_members', 'tiered'])],
            'start_date'        => ['nullable', 'date'],
            'end_date'          => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'         => ['sometimes', 'boolean'],

            // Nested Rules (menyertakan id opsional untuk update record eksisting)
            'rules'                       => ['required', 'array', 'min:1'],
            'rules.*.id'                  => ['nullable', 'integer', 'exists:sibling_discount_rules,id'],
            'rules.*.min_sibling_count'   => ['required', 'integer', 'min:2'],
            'rules.*.max_sibling_count'   => ['nullable', 'integer', 'gte:rules.*.min_sibling_count'],
            'rules.*.discount_type'       => ['required', Rule::in(['percentage', 'fixed_amount'])],
            'rules.*.discount_value'      => ['required', 'numeric', 'min:0'],
            'rules.*.priority'            => ['nullable', 'integer', 'min:0'],
            'rules.*.is_active'           => ['sometimes', 'boolean'],

            // Nested Allocations (menyertakan id opsional untuk update record eksisting)
            'rules.*.allocations'                      => ['required', 'array', 'min:1'],
            'rules.*.allocations.*.id'                 => ['nullable', 'integer', 'exists:sibling_discount_allocations,id'],
            'rules.*.allocations.*.target_type'        => [
                'required',
                Rule::in(['all_members', 'second_onward', 'specific_order', 'youngest', 'oldest']),
            ],
            'rules.*.allocations.*.sibling_order_from' => [
                'nullable',
                'required_if:rules.*.allocations.*.target_type,specific_order',
                'integer',
                'min:1',
            ],
            'rules.*.allocations.*.sibling_order_to'   => [
                'nullable',
                'integer',
                'gte:rules.*.allocations.*.sibling_order_from',
            ],
            'rules.*.allocations.*.discount_type'      => ['required', Rule::in(['percentage', 'fixed_amount'])],
            'rules.*.allocations.*.discount_value'     => ['required', 'numeric', 'min:0'],
        ];
    }
}