<?php
namespace Models;

class ThesisSupervision extends SoftDeletableModel
{
    // Relasi
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
    }

    public function supervisor1()
    {
        return $this->belongsTo(Supervisor1::class, 'supervisor_1_id');
    }

    public function supervisor2()
    {
        return $this->belongsTo(Supervisor2::class, 'supervisor_2_id');
    }

    public function examiner1()
    {
        return $this->belongsTo(Examiner1::class, 'examiner_1_id');
    }

    public function examiner2()
    {
        return $this->belongsTo(Examiner2::class, 'examiner_2_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}