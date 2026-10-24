<?php
namespace Models; 

class GeneratedReport extends BasedModel
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

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    public function reportCardTemplate()
    {
        return $this->belongsTo(ReportCardTemplate::class, 'report_card_template_id');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}