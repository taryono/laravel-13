<?php
namespace Models; 

class RkasSubActivity extends BasedModel
{
    // Relasi
        public function rkasActivity()
    {
        return $this->belongsTo(RkasActivity::class, 'rkas_activity_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}