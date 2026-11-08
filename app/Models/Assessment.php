<?php
namespace Models; 

class Assessment extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function curriculumStructure()
    {
        return $this->belongsTo(CurriculumStructure::class, 'curriculum_structure_id');
    }

    public function assessmentType()
    {
        return $this->belongsTo(AssessmentType::class, 'assessment_type_id');
    }

    public function assessmentMethod()
    {
        return $this->belongsTo(AssessmentMethod::class, 'assessment_method_id');
    }

    public function assessmentRubric()
    {
        return $this->belongsTo(AssessmentRubric::class, 'assessment_rubric_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}