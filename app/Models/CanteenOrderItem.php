<?php
namespace Models;

class CanteenOrderItem extends SoftDeletableModel
{
    // Relasi
    public function canteenOrder()
    {
        return $this->belongsTo(CanteenOrder::class, 'canteen_order_id');
    }

    public function canteenMenu()
    {
        return $this->belongsTo(CanteenMenu::class, 'canteen_menu_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}