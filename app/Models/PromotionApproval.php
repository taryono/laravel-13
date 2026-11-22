<?php
namespace Models;

class PromotionApproval extends SoftDeletableModel
{
    // Relasi
    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }

    public function approver()
    {
        return $this->belongsTo(Approver::class, 'approver_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}