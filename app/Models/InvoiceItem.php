<?php
namespace Models; 

class InvoiceItem extends BasedModel
{
    // Relasi
        public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function revenueAccount()
    {
        return $this->belongsTo(RevenueAccount::class, 'revenue_account_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}