<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAcademicSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Saat update, ambil id dari route. Sesuaikan nama parameternya
        // dengan route kamu, misalnya {academic_semester}
        $id = $this->route('id');
        return [
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('academic_semesters', 'code')
                    ->where(fn($query) => $query
                        ->where('school_id', $this->school_id)
                        ->where('academic_year_id', $this->academic_year_id))
                    ->ignore($id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'krs_start_date' => ['required', 'date'],
            'krs_end_date' => ['required', 'date', 'after_or_equal:krs_start_date'],
        ];
    }

    /**
     * Nama field yang ramah dibaca (menggantikan "school id", "krs start date", dst).
     */
    public function attributes(): array
    {
        return [
            'school_id' => 'Sekolah',
            'academic_year_id' => 'Tahun Akademik',
            'code' => 'Kode Semester',
            'name' => 'Nama Semester',
            'type' => 'Tipe Semester',
            'start_date' => 'Tanggal Mulai',
            'end_date' => 'Tanggal Selesai',
            'krs_start_date' => 'Tanggal Mulai KRS',
            'krs_end_date' => 'Tanggal Selesai KRS',
        ];
    }

    /**
     * Pesan error kustom berbahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer' => ':attribute tidak valid.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute bukan tanggal yang valid.',
            'code.unique' => 'Kode Semester sudah digunakan pada sekolah dan tahun akademik ini.',
            'end_date.after_or_equal' => 'Tanggal Selesai tidak boleh sebelum Tanggal Mulai.',
            'krs_end_date.after_or_equal' => 'Tanggal Selesai KRS tidak boleh sebelum Tanggal Mulai KRS.',
        ];
    }
}