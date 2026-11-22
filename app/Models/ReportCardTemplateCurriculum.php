<?php
namespace Models;

class ReportCardTemplateCurriculum extends SoftDeletableModel
{
    // Relasi
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