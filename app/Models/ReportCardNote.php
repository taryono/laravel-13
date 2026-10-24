<?php
namespace Models; 

class ReportCardNote extends BasedModel
{
    // Relasi
        public function reportCard()
    {
        return $this->belongsTo(ReportCard::class, 'report_card_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}