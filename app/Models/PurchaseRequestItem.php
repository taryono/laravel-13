<?php
namespace Models; 

class PurchaseRequestItem extends BasedModel
{
    // Relasi
        public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function rkasItem()
    {
        return $this->belongsTo(RkasItem::class, 'rkas_item_id');
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