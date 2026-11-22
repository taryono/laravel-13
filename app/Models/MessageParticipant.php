<?php
namespace Models;

class MessageParticipant extends SoftDeletableModel
{
    // Relasi
    public function messageThread()
    {
        return $this->belongsTo(MessageThread::class, 'message_thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lastReadMessage()
    {
        return $this->belongsTo(LastReadMessage::class, 'last_read_message_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}