<?php
namespace Models;

class ReportTemplateCurriculum extends SoftDeletableModel
{
    // Relasi
    public function reportTemplate()
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
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