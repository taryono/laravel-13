<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAcademicCalendarEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sesuaikan dengan policy/Gate Anda
    }

    public function rules(): array
    {
        $id = $this->route("id");
        return [
            'code' => [
                'nullable',
                'string',
                'max:64',
                'regex:/^[A-Za-z0-9\-]+$/',
                Rule::unique('academic_calendar_events', 'code')
                    ->whereNull('deleted_at')
                    ->ignore($id),
            ],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'school_id' => ['required', 'exists:schools,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'academic_semester_id' => ['nullable', 'exists:academic_semesters,id'],

            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'event_type' => [
                'nullable',
                Rule::in([
                    'holiday',
                    'exam',
                    'lecture',
                    'registration',
                    'meeting',
                    'other',
                ])
            ],

            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time'],

            'location' => ['nullable', 'string', 'max:200'],
            'is_holiday' => ['boolean'],
            'is_all_day' => ['boolean'],
            'color' => ['nullable', 'string', 'max:20'],
            'attachment_url' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'school_id.required' => 'Sekolah wajib dipilih.',
            'title.required' => 'Judul event wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'end_time.after_or_equal' => 'Jam selesai tidak boleh sebelum jam mulai.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_holiday' => $this->boolean('is_holiday'),
            'is_all_day' => $this->boolean('is_all_day'),
            'is_active' => $this->boolean('is_active'),
        ]);

        // Jika all-day, kosongkan waktu
        if ($this->boolean('is_all_day')) {
            $this->merge(['start_time' => null, 'end_time' => null]);
        }
    }
}