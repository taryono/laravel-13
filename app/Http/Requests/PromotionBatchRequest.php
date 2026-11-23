<?php
// app/Http/Requests/PromotionBatchRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromotionBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'code' => [
                'required',
                'string',
                'max:50',
                // saat update: abaikan baris sendiri (route model binding)
                Rule::unique('promotion_batches', 'code')->ignore($this->route('promotion_batch')),
            ],
            'name' => ['required', 'string', 'max:150'],
            'from_academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'to_academic_year_id' => ['required', 'integer', 'exists:academic_years,id', 'different:from_academic_year_id'],
        ];

        // Status hanya bisa diubah lewat form saat edit: draft <-> cancelled
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['status'] = ['required', Rule::in(['draft', 'cancelled'])];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode batch',
            'name' => 'nama batch',
            'from_academic_year_id' => 'tahun ajaran asal',
            'to_academic_year_id' => 'tahun ajaran tujuan',
            'status' => 'status',
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'Kode batch sudah dipakai.',
            'to_academic_year_id.different' => 'Tahun ajaran tujuan harus berbeda dengan tahun ajaran asal.',
        ];
    }
}