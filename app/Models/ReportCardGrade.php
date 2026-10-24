<?php
namespace Models; 

class ReportCardGrade extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function reportCard()
    {
        return $this->belongsTo(ReportCard::class, 'report_card_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function curriculumStructure()
    {
        return $this->belongsTo(CurriculumStructure::class, 'curriculum_structure_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}