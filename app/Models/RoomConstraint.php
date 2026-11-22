<?php
namespace Models;

class RoomConstraint extends SoftDeletableModel
{
    // Relasi
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}