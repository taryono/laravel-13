<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentFamilyMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $family   = $this->route('student_family');
        $familyId = is_object($family) ? $family->id : $this->input('student_family_id');

        return [
            'student_family_id' => ['sometimes', 'required', 'integer', 'exists:student_families,id'],
            'student_id'        => [
                'nullable',
                'required_without:applicant_id',
                'integer',
                'exists:students,id',
                Rule::unique('student_family_members', 'student_id')
                    ->where(fn ($query) => $query
                        ->where('student_family_id', $familyId)
                        ->whereNull('deleted_at')
                    ),
            ],
            'applicant_id'      => [
                'nullable',
                'required_without:student_id',
                'integer',
                'exists:applicants,id',
                Rule::unique('student_family_members', 'applicant_id')
                    ->where(fn ($query) => $query
                        ->where('student_family_id', $familyId)
                        ->whereNull('deleted_at')
                    ),
            ],
            'relation'          => ['nullable', 'string', 'max:30'],
            'is_active'         => ['sometimes', 'boolean'],
            'joined_at'         => ['nullable', 'date'],
            'left_at'           => ['nullable', 'date', 'after_or_equal:joined_at'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required_without'   => 'Pilih minimal salah satu antara Siswa atau Pendaftar (Applicant).',
            'applicant_id.required_without' => 'Pilih minimal salah satu antara Pendaftar (Applicant) atau Siswa.',
            'student_id.unique'             => 'Siswa tersebut sudah terdaftar dalam keluarga ini.',
            'applicant_id.unique'           => 'Pendaftar tersebut sudah terdaftar dalam keluarga ini.',
            'left_at.after_or_equal'        => 'Tanggal keluar tidak boleh lebih awal dari tanggal bergabung.',
        ];
    }
}