<?php
namespace Models; 

class Assessment extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(\App\Models\School::class, 'school_id');
    }

    public function curriculumStructure()
    {
        return $this->belongsTo(\App\Models\CurriculumStructure::class, 'curriculum_structure_id');
    }

    public function assessmentType()
    {
        return $this->belongsTo(\App\Models\AssessmentType::class, 'assessment_type_id');
    }

    public function assessmentMethod()
    {
        return $this->belongsTo(\App\Models\AssessmentMethod::class, 'assessment_method_id');
    }

    public function assessmentRubric()
    {
        return $this->belongsTo(\App\Models\AssessmentRubric::class, 'assessment_rubric_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(\App\Models\AcademicYear::class, 'academic_year_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}