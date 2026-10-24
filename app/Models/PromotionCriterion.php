<?php
namespace Models; 

class PromotionCriterion extends BasedModel
{
    // Relasi
        public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class, 'class_level_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}