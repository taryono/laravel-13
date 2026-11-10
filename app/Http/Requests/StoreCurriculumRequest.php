<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCurriculumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sesuaikan dengan policy/Gate
    }

    public function rules(): array
    {
        return [
            // Master curriculum
            'organization_id' => ['required', 'exists:organizations,id'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('curriculums', 'name')
                    ->where(fn($q) => $q->where('organization_id', $this->organization_id))
                    ->whereNull('deleted_at')
                    ->ignore($this->route('curriculum')),
            ],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('curriculums', 'code')
                    ->where(fn($q) => $q->where('organization_id', $this->organization_id))
                    ->whereNull('deleted_at')
                    ->ignore($this->route('curriculum')),
            ],
            'type' => ['required', Rule::in(['national', 'international', 'local', 'hybrid'])],
            'description' => ['nullable', 'string'],
            'start_year' => ['nullable', 'integer', 'min:1901', 'max:2155'],
            'end_year' => ['nullable', 'integer', 'min:1901', 'max:2155', 'gte:start_year'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'organization_id.required' => 'Organisasi wajib dipilih.',
            'name.required' => 'Nama kurikulum wajib diisi.',
            'name.unique' => 'Nama kurikulum sudah dipakai di organisasi ini.',
            'code.required' => 'Kode kurikulum wajib diisi.',
            'code.unique' => 'Kode kurikulum sudah dipakai di organisasi ini.',
            'type.required' => 'Tipe kurikulum wajib dipilih.',
            'end_year.gte' => 'Tahun selesai tidak boleh sebelum tahun mulai.',
        ];
    }

}