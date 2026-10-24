<?php
namespace Models; 

class EnrollmentConversion extends BasedModel
{
    // Relasi
        public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}