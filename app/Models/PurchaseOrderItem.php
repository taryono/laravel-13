<?php
namespace Models; 

class PurchaseOrderItem extends BasedModel
{
    // Relasi
        public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function purchaseRequestItem()
    {
        return $this->belongsTo(PurchaseRequestItem::class, 'purchase_request_item_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}