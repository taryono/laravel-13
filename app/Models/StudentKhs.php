<?php
namespace Models; 

class StudentKhs extends BasedModel
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


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}