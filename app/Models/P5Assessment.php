<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class P5Assessment extends SoftDeletableModel
{
    protected $table = 'p5_assessments';

    protected $fillable = [
        'project_id',
        'student_id',
        'group_id',
        'achievement_description',
        'dimension_notes',
        'overall_predicate',
        'student_reflection',
        'facilitator_notes',
        'status',
        'assessed_by',
        'assessed_at',
        'approved_by',
        'approved_at',
        'is_published_to_report',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'dimension_notes' => 'array',
        'assessed_at' => 'datetime',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
        'is_published_to_report' => 'boolean',
    ];

    // ============ KONSTANTA ============

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PUBLISHED = 'published';

    public const PREDICATE_SB = 'sangat_berkembang';
    public const PREDICATE_BSH = 'berkembang_sesuai_harapan';
    public const PREDICATE_MB = 'mulai_berkembang';
    public const PREDICATE_BB = 'belum_berkembang';

    public static function predicateLabels(): array
    {
        return [
            self::PREDICATE_SB => 'Sangat Berkembang',
            self::PREDICATE_BSH => 'Berkembang Sesuai Harapan',
            self::PREDICATE_MB => 'Mulai Berkembang',
            self::PREDICATE_BB => 'Belum Berkembang',
        ];
    }

    public static function predicateBadges(): array
    {
        return [
            self::PREDICATE_SB => 'success',
            self::PREDICATE_BSH => 'primary',
            self::PREDICATE_MB => 'warning',
            self::PREDICATE_BB => 'danger',
        ];
    }

    // ============ RELASI ============

    public function project(): BelongsTo
    {
        return $this->belongsTo(P5Project::class, 'project_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(P5Group::class, 'group_id');
    }

    public function assessedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assessed_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(P5AssessmentDetail::class, 'assessment_id');
    }

    // ============ SCOPES ============

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published_to_report', true);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    // ============ ACCESSORS ============

    public function getPredicateLabelAttribute(): string
    {
        return self::predicateLabels()[$this->overall_predicate] ?? '-';
    }

    public function getPredicateBadgeAttribute(): string
    {
        return self::predicateBadges()[$this->overall_predicate] ?? 'secondary';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'success',
            self::STATUS_SUBMITTED => 'info',
            self::STATUS_PUBLISHED => 'primary',
            default => 'secondary',
        };
    }

    // ============ HELPERS ============

    public function canBeEdited(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_SUBMITTED]);
    }

    public function markAsSubmitted(int $employeeId): void
    {
        $this->update([
            'status' => self::STATUS_SUBMITTED,
            'assessed_by' => $employeeId,
            'assessed_at' => now(),
        ]);
    }

    public function markAsApproved(int $userId): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    public function publishToReport(): void
    {
        $this->update([
            'is_published_to_report' => true,
            'published_at' => now(),
            'status' => self::STATUS_PUBLISHED,
        ]);
    }
}
