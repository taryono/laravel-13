<?php
namespace Models; 

class AssetDepreciationLog extends BasedModel
{
    // Relasi
        public function asset()
    {
        return $this->belongsTo(\App\Models\Asset::class, 'asset_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}