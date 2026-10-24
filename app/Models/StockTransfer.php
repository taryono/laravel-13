<?php
namespace Models; 

class StockTransfer extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(FromWarehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(ToWarehouse::class, 'to_warehouse_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}