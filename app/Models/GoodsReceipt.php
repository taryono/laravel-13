<?php
namespace Models; 

class GoodsReceipt extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}