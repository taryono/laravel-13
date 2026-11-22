<?php

namespace Models;

class Room extends BasedModel
{

    protected $table = 'rooms';
    protected $guarded = ['id'];
    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relasi
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}