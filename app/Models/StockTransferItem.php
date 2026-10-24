<?php
namespace Models; 

class StockTransferItem extends BasedModel
{
    // Relasi
        public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
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