<?php

namespace Models;

class DemoRequest extends BasedModel
{
    protected $table = 'demo_requests';

    protected $guarded = [];

    protected $casts = [
        'demo_datetime' => 'datetime',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
