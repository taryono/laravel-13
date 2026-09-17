<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class P5ProjectDimension extends BasedModel
{

    protected $table = 'p5_project_dimensions';

    protected $fillable = [
        'project_id',
        'dimension_id',
        'element_ids',
        'weight',
        'notes',
    ];

    protected $casts = [
        'element_ids' => 'array',
        'weight' => 'decimal:2',
    ];

    // ============ RELASI ============

    public function project(): BelongsTo
    {
        return $this->belongsTo(P5Project::class, 'project_id');
    }

    public function dimension(): BelongsTo
    {
        return $this->belongsTo(P5Dimension::class, 'dimension_id');
    }

    // ============ ACCESSORS ============

    public function getElementsAttribute()
    {
        if (empty($this->element_ids)) {
            return collect();
        }
        return P5Element::whereIn('id', $this->element_ids)->get();
    }
}
