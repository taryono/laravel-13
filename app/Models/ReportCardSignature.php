<?php
namespace Models;

class ReportCardSignature extends SoftDeletableModel
{
    // Relasi
    public function reportCard()
    {
        return $this->belongsTo(ReportCard::class, 'report_card_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}