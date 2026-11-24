<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $admissionId = $this->route('admission')?->id ?? $this->route('admission');
        $schoolId = $this->input('school_id') ?? $this->user()?->school_id;

        return [
            'school_id' => ['required', 'exists:schools,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'class_level_id' => ['nullable', 'exists:class_levels,id'],
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
                Rule::unique('admissions', 'code')
                    ->where(fn($q) => $q->where('school_id', $schoolId)->whereNull('deleted_at'))
                    ->ignore($admissionId),
            ],
            'type' => ['nullable', 'string', 'in:REGULAR,ACHIEVEMENT,AFFIRMATION,TRANSFER'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'quota' => ['required', 'integer', 'min:0'],
            'registration_fee' => ['required', 'numeric', 'min:0'],
            'min_age_years' => ['nullable', 'required_with:min_age_reference_date', 'integer', 'min:0', 'max:30'],
            'min_age_months' => ['nullable', 'integer', 'min:0', 'max:11'],
            'min_age_reference_date' => ['nullable', 'required_with:min_age_years', 'date'],

            'max_age_years' => ['nullable', 'required_with:max_age_reference_date', 'integer', 'min:0', 'max:30'],
            'max_age_months' => ['nullable', 'integer', 'min:0', 'max:11'],
            'max_age_reference_date' => ['nullable', 'required_with:max_age_years', 'date'],
            'requirements' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->input('code'))),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}