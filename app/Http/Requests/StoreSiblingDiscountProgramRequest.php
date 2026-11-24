<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSiblingDiscountProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $organizationId = $this->input('organization_id', auth()->user()?->organization_id);

        return [
            // Data Program Utama
            'organization_id'   => ['required', 'integer', 'exists:organizations,id'],
            'school_id'         => ['nullable', 'integer', 'exists:schools,id'],
            'code'              => [
                'required',
                'string',
                'max:50',
                Rule::unique('sibling_discount_programs', 'code')
                    ->where(fn ($query) => $query
                        ->where('organization_id', $organizationId)
                        ->whereNull('deleted_at')
                    ),
            ],
            'name'              => ['required', 'string', 'max:150'],
            'description'       => ['nullable', 'string'],
            'min_sibling_count' => ['required', 'integer', 'min:2'],
            'calculation_basis' => ['required', Rule::in(['percentage', 'fixed_amount'])],
            'allocation_method' => ['required', Rule::in(['all_members', 'selected_members', 'tiered'])],
            'start_date'        => ['nullable', 'date'],
            'end_date'          => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'         => ['sometimes', 'boolean'],

            // Nested Rules (Minimal 1 Rule)
            'rules'                       => ['required', 'array', 'min:1'],
            'rules.*.min_sibling_count'   => ['required', 'integer', 'min:2'],
            'rules.*.max_sibling_count'   => ['nullable', 'integer', 'gte:rules.*.min_sibling_count'],
            'rules.*.discount_type'       => ['required', Rule::in(['percentage', 'fixed_amount'])],
            'rules.*.discount_value'      => ['required', 'numeric', 'min:0'],
            'rules.*.priority'            => ['nullable', 'integer', 'min:0'],
            'rules.*.is_active'           => ['sometimes', 'boolean'],

            // Nested Allocations di dalam masing-masing Rule
            'rules.*.allocations'                      => ['required', 'array', 'min:1'],
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

    public function messages(): array
    {
        return [
            'code.unique'                                               => 'Kode program diskon sudah digunakan pada organisasi ini.',
            'end_date.after_or_equal'                                   => 'Tanggal berakhir tidak boleh lebih awal dari tanggal mulai.',
            'rules.required'                                            => 'Minimal harus terdapat 1 aturan jumlah saudara (rule).',
            'rules.*.max_sibling_count.gte'                             => 'Maksimal jumlah saudara harus lebih besar atau sama dengan minimal jumlah saudara.',
            'rules.*.allocations.required'                              => 'Setiap rule wajib memiliki minimal 1 alokasi target penerima diskon.',
            'rules.*.allocations.*.sibling_order_from.required_if'      => 'Urutan anak awal (sibling_order_from) wajib diisi jika target adalah specific_order.',
            'rules.*.allocations.*.sibling_order_to.gte'                => 'Urutan anak akhir harus lebih besar atau sama dengan urutan anak awal.',
        ];
    }
}