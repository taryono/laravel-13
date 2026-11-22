<?php
namespace Models;

class ReportCardTemplateSection extends SoftDeletableModel
{
    // Relasi
    public function reportCardTemplate()
    {
        return $this->belongsTo(ReportCardTemplate::class, 'report_card_template_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}