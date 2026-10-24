<?php
namespace Models; 

class PaymentChannel extends BasedModel
{
    // Relasi
        public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}