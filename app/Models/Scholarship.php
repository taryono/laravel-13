<?php
namespace Models; 

class Scholarship extends BasedModel
{
    // Relasi
        public function scholarshipType()
    {
        return $this->belongsTo(ScholarshipType::class, 'scholarship_type_id');
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

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class, 'education_level_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
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