<?php
namespace Models; 

class InventoryStock extends BasedModel
{
    // Relasi
        public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}