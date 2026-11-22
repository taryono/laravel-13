<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class P5Element extends SoftDeletableModel
{

    protected $table = 'p5_elements';

    protected $fillable = [
        'dimension_id',
        'code',
        'name',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // ============ RELASI ============

    public function dimension(): BelongsTo
    {
        return $this->belongsTo(P5Dimension::class, 'dimension_id');
    }

    // ============ SCOPES ============

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
