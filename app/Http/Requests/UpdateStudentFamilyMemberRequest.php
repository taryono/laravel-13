<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentFamilyMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $member   = $this->route('member');
        $memberId = is_object($member) ? $member->id : $member;
        $familyId = $this->input('student_family_id', is_object($member) ? $member->student_family_id : null);

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
                    )
                    ->ignore($memberId),
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
                    )
                    ->ignore($memberId),
            ],
            'relation'          => ['nullable', 'string', 'max:30'],
            'is_active'         => ['sometimes', 'boolean'],
            'joined_at'         => ['nullable', 'date'],
            'left_at'           => ['nullable', 'date', 'after_or_equal:joined_at'],
        ];
    }
}