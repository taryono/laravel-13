<?php
namespace Models; 

class TransportStop extends BasedModel
{
    // Relasi
        public function transportRoute()
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}