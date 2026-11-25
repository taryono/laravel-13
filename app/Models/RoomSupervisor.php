<?php
namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomSupervisor extends BasedModel
{
    // Relasi


    protected $fillable = [
        'room_id',
        'employee_id',
        'role',
        'start_date',
        'end_date',
        'is_active',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}