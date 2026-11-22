<?php
namespace Models;

class BosUsagePlan extends SoftDeletableModel
{
    // Relasi
    public function bosPeriod()
    {
        return $this->belongsTo(BosPeriod::class, 'bos_period_id');
    }

    public function rkasDocument()
    {
        return $this->belongsTo(RkasDocument::class, 'rkas_document_id');
    }

    public function rkasItem()
    {
        return $this->belongsTo(RkasItem::class, 'rkas_item_id');
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