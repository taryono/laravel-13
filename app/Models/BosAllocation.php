<?php
namespace Models;

class BosAllocation extends SoftDeletableModel
{
    // Relasi
    public function bosPeriod()
    {
        return $this->belongsTo(BosPeriod::class, 'bos_period_id');
    }

    public function bosComponent()
    {
        return $this->belongsTo(BosComponent::class, 'bos_component_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}