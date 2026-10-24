<?php
namespace Models; 

class PurchaseRequest extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function rkasItem()
    {
        return $this->belongsTo(RkasItem::class, 'rkas_item_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}