<?php
namespace Models;

class RkasItem extends SoftDeletableModel
{
    // Relasi
    public function rkasSubActivity()
    {
        return $this->belongsTo(RkasSubActivity::class, 'rkas_sub_activity_id');
    }

    public function rkasComponent()
    {
        return $this->belongsTo(RkasComponent::class, 'rkas_component_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}