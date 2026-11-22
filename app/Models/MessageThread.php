<?php
namespace Models;

class MessageThread extends SoftDeletableModel
{
    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }

    public function lastMessage()
    {
        return $this->belongsTo(LastMessage::class, 'last_message_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}