<?php
namespace Models;

class ReportCardComponent extends SoftDeletableModel
{
    // Relasi
    public function reportCardTemplateSection()
    {
        return $this->belongsTo(ReportCardTemplateSection::class, 'report_card_template_section_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}