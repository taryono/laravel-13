<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class PromotionBatch extends SoftDeletableModel
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    protected $fillable = [
        'code',
        'name',
        'description',
        'from_academic_year_id',
        'to_academic_year_id',
        'total_students',
        'processed_count',
        'promoted_count',
        'retained_count',
        'graduated_count',
        'transferred_count',
        'dropped_out_count',
        'status',
        'started_at',
        'completed_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_students' => 'integer',
        'processed_count' => 'integer',
        'promoted_count' => 'integer',
        'retained_count' => 'integer',
        'graduated_count' => 'integer',
        'transferred_count' => 'integer',
        'dropped_out_count' => 'integer',
    ];

    // ============ RELASI ============

    public function fromAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'from_academic_year_id');
    }

    public function toAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'to_academic_year_id');
    }

    public function student_promotions(): HasMany
    {
        return $this->hasMany(StudentPromotion::class, 'batch_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    // ============ SCOPES ============

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ============ ACCESSORS ============

    public function getProgressPercentAttribute(): float
    {
        if ($this->total_students === 0) {
            return 0;
        }
        return round(($this->processed_count / $this->total_students) * 100, 2);
    }

    public function getIsFinalizedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function recalculateStats(): void
    {
        $stats = $this->student_promotions()
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN promotion_status = 'promoted' THEN 1 ELSE 0 END) as promoted,
                SUM(CASE WHEN promotion_status = 'retained' THEN 1 ELSE 0 END) as retained,
                SUM(CASE WHEN promotion_status = 'graduated' THEN 1 ELSE 0 END) as graduated,
                SUM(CASE WHEN promotion_status = 'transferred' THEN 1 ELSE 0 END) as transferred,
                SUM(CASE WHEN promotion_status = 'dropped_out' THEN 1 ELSE 0 END) as dropped_out,
                SUM(CASE WHEN status IN ('processed','finalized') THEN 1 ELSE 0 END) as processed
            ")
            ->first();

        $this->update([
            'total_students' => $stats->total ?? 0,
            'processed_count' => $stats->processed ?? 0,
            'promoted_count' => $stats->promoted ?? 0,
            'retained_count' => $stats->retained ?? 0,
            'graduated_count' => $stats->graduated ?? 0,
            'transferred_count' => $stats->transferred ?? 0,
            'dropped_out_count' => $stats->dropped_out ?? 0,
        ]);
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function getProgressAttribute(): int
    {
        $total = (int) $this->total_students;

        return $total > 0 ? (int) round(($this->processed_count / $total) * 100) : 0;
    }
}
