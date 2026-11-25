<?php
namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dormitory extends BasedModel
{
    // Relasi

    protected $fillable = [
        'organization_id',
        'code',
        'name',
        'gender_type',
        'capacity',
        'address',
        'phone',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function soroganSessions(): HasMany
    {
        return $this->hasMany(SoroganSession::class);
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