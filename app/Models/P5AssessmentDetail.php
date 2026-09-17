<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class P5AssessmentDetail extends BasedModel
{

    protected $table = 'p5_assessment_details';

    protected $fillable = [
        'assessment_id',
        'dimension_id',
        'element_id',
        'level',
        'description',
        'evidence_file',
        'evidence_notes',
    ];

    // ============ RELASI ============

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(P5Assessment::class, 'assessment_id');
    }

    public function dimension(): BelongsTo
    {
        return $this->belongsTo(P5Dimension::class, 'dimension_id');
    }

    public function element(): BelongsTo
    {
        return $this->belongsTo(P5Element::class, 'element_id');
    }

    // ============ ACCESSORS ============

    public function getLevelLabelAttribute(): string
    {
        return P5Assessment::predicateLabels()[$this->level] ?? '-';
    }

    public function getLevelBadgeAttribute(): string
    {
        return P5Assessment::predicateBadges()[$this->level] ?? 'secondary';
    }

    // ============ SCOPES ============

    public function scopeByDimension($query, $dimensionId)
    {
        return $query->where('dimension_id', $dimensionId);
    }
}
