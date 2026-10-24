<?php
namespace Models; 

class StudentTranscript extends BasedModel
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


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}