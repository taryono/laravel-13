<?php
namespace Models;

class Message extends SoftDeletableModel
{
    // Relasi
    public function sender()
    {
        return $this->belongsTo(Sender::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Receiver::class, 'receiver_id');
    }

    public function parentMessage()
    {
        return $this->belongsTo(ParentMessage::class, 'parent_message_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}