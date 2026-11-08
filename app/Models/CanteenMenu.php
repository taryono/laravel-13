<?php
namespace Models; 

class CanteenMenu extends BasedModel
{
     public const CATEGORIES = ['food', 'drink', 'snack', 'stationery', 'other'];

    // canteen_tenant_id aman di fillable karena TIDAK pernah diambil dari input
    // (validated() tidak memuatnya; controller mengisinya dari route).
    protected $fillable = [
        'canteen_tenant_id', 'name', 'barcode', 'category',
        'price', 'cost_price', 'photo_url', 'is_available',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'cost_price'   => 'decimal:2',
        'is_available' => 'boolean',
    ];
    
    // Relasi
    public function canteenTenant()
    {
        return $this->belongsTo(CanteenTenant::class, 'canteen_tenant_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}