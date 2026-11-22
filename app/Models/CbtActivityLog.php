<?php

namespace Models;

class CbtActivityLog extends SoftDeletableModel
{
    protected $table = 'cbt_activity_logs';
    protected $fillable = [
        'session_id',
        'activity_type',
        'activity_data',
        'severity',
        'snapshot_url',
        'recorded_at'
    ];
    protected $casts = ['activity_data' => 'array', 'recorded_at' => 'datetime'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(CbtSession::class, 'session_id');
    }
}