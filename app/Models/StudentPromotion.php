<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPromotion extends SoftDeletableModel
{
    protected $fillable = [
        'student_id',
        'batch_id',
        'from_academic_year_id',
        'to_academic_year_id',
        'from_classroom_id',
        'to_classroom_id',
        'from_class_level_id',
        'to_class_level_id',
        'promotion_status',
        'final_score',
        'attendance_rate',
        'behavior_score',
        'decision_reason',
        'notes',
        'decided_by',
        'decided_at',
        'approved_by',
        'approved_at',
        'is_manual',
        'status',
        'previous_state',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'final_score' => 'decimal:2',
        'attendance_rate' => 'decimal:2',
        'behavior_score' => 'decimal:2',
        'decided_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_manual' => 'boolean',
        'previous_state' => 'array',
    ];

    // ============ KONSTANTA ============

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROMOTED = 'promoted';
    public const STATUS_RETAINED = 'retained';
    public const STATUS_GRADUATED = 'graduated';
    public const STATUS_TRANSFERRED = 'transferred';
    public const STATUS_DROPPED_OUT = 'dropped_out';

    public const PROCESS_DRAFT = 'draft';
    public const PROCESS_PROCESSED = 'processed';
    public const PROCESS_FINALIZED = 'finalized';
    public const PROCESS_CANCELLED = 'cancelled';

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Keputusan',
            self::STATUS_PROMOTED => 'Naik Kelas',
            self::STATUS_RETAINED => 'Tinggal Kelas',
            self::STATUS_GRADUATED => 'Lulus',
            self::STATUS_TRANSFERRED => 'Pindah Sekolah',
            self::STATUS_DROPPED_OUT => 'Putus Sekolah',
        ];
    }

    // ============ RELASI ============

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(PromotionBatch::class, 'batch_id');
    }

    public function fromAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'from_academic_year_id');
    }

    public function toAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'to_academic_year_id');
    }

    public function fromClassroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'from_classroom_id');
    }

    public function toClassroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'to_classroom_id');
    }

    public function fromClassLevel(): BelongsTo
    {
        return $this->belongsTo(ClassLevel::class, 'from_class_level_id');
    }

    public function toClassLevel(): BelongsTo
    {
        return $this->belongsTo(ClassLevel::class, 'to_class_level_id');
    }

    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'decided_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    // ============ SCOPES ============

    public function scopePending($query)
    {
        return $query->where('promotion_status', self::STATUS_PENDING);
    }

    public function scopePromoted($query)
    {
        return $query->where('promotion_status', self::STATUS_PROMOTED);
    }

    public function scopeRetained($query)
    {
        return $query->where('promotion_status', self::STATUS_RETAINED);
    }

    public function scopeGraduated($query)
    {
        return $query->where('promotion_status', self::STATUS_GRADUATED);
    }

    public function scopeByBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    public function scopeFinalized($query)
    {
        return $query->where('status', self::PROCESS_FINALIZED);
    }

    // ============ ACCESSORS ============

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->promotion_status] ?? '-';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->promotion_status) {
            self::STATUS_PROMOTED => 'success',
            self::STATUS_RETAINED => 'warning',
            self::STATUS_GRADUATED => 'primary',
            self::STATUS_TRANSFERRED => 'info',
            self::STATUS_DROPPED_OUT => 'danger',
            default => 'secondary',
        };
    }

    public function getIsFinalizedAttribute(): bool
    {
        return $this->status === self::PROCESS_FINALIZED;
    }

    // ============ HELPERS ============

    public function markAsDecided(int $userId, ?string $reason = null): void
    {
        $this->update([
            'decided_by' => $userId,
            'decided_at' => now(),
            'decision_reason' => $reason ?? $this->decision_reason,
        ]);
    }

    public function markAsApproved(int $userId): void
    {
        $this->update([
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
