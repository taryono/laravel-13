<?php
namespace Models;

class StudentKrs extends SoftDeletableModel
{
    // Relasi
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function academicSemester()
    {
        return $this->belongsTo(AcademicSemester::class, 'academic_semester_id');
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
    }

    public function academicAdvisor()
    {
        return $this->belongsTo(AcademicAdvisor::class, 'academic_advisor_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}