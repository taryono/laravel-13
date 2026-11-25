<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // Models\Room
    public function dormitory(): BelongsTo
    {
        return $this->belongsTo(Dormitory::class);
    }

    public function supervisors(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'room_supervisors')
            ->using(RoomSupervisor::class)
            ->withPivot(['role', 'start_date', 'end_date', 'is_active'])
            ->withTimestamps();
    }
}