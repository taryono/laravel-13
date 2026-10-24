<?php
namespace Models; 

class RkasBudget extends BasedModel
{
    // Relasi
        public function rkasItem()
    {
        return $this->belongsTo(RkasItem::class, 'rkas_item_id');
    }

    public function rkasSource()
    {
        return $this->belongsTo(RkasSource::class, 'rkas_source_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}