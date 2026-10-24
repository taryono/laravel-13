<?php
namespace Models; 

class ScholarshipRecipient extends BasedModel
{
    // Relasi
        public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id');
    }

    public function scholarshipApplication()
    {
        return $this->belongsTo(ScholarshipApplication::class, 'scholarship_application_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function academicSemester()
    {
        return $this->belongsTo(AcademicSemester::class, 'academic_semester_id');
    }

    public function benefitCurrency()
    {
        return $this->belongsTo(BenefitCurrency::class, 'benefit_currency_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}