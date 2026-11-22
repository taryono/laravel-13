<?php
namespace Models;

class StockAdjustmentItem extends SoftDeletableModel
{
    // Relasi
    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_id');
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