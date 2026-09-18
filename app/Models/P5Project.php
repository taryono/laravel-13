<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class P5Project extends BasedModel
{
    protected $table = 'p5_projects';

    protected $fillable = [
        'code',
        'name',
        'description',
        'objectives',
        'essential_questions',
        'academic_year_id',
        'school_id',
        'theme_id',
        'phase',
        'class_level_id',
        'start_date',
        'end_date',
        'total_hours',
        'schedule_notes',
        'product_name',
        'product_description',
        'product_photo',
        'attachments',
        'coordinator_id',
        'co_coordinator_id',
        'status',
        'published_at',
        'completed_at',
        'settings',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'published_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_hours' => 'integer',
        'attachments' => 'array',
        'settings' => 'array',
    ];

    // ============ KONSTANTA ============

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ARCHIVED = 'archived';

    public const PHASE_LABELS = [
        'A' => 'Fase A (Kelas 1-2 SD)',
        'B' => 'Fase B (Kelas 3-4 SD)',
        'C' => 'Fase C (Kelas 5-6 SD)',
        'D' => 'Fase D (Kelas 7-9 SMP)',
        'E' => 'Fase E (Kelas 10 SMA/SMK)',
        'F' => 'Fase F (Kelas 11-12 SMA/SMK)',
    ];

    // ============ RELASI ============

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(P5Theme::class, 'theme_id');
    }

    public function classLevel(): BelongsTo
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'coordinator_id');
    }

    public function coCoordinator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'co_coordinator_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function dimensions(): BelongsToMany
    {
        return $this->belongsToMany(
            P5Dimension::class,
            'p5_project_dimensions',
            'project_id',
            'dimension_id'
        )->withPivot(['element_ids', 'weight', 'notes'])->withTimestamps();
    }

    public function projectDimensions(): HasMany
    {
        return $this->hasMany(P5ProjectDimension::class, 'project_id');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(P5Group::class, 'project_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(P5Assessment::class, 'project_id');
    }

    // ============ SCOPES ============

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeForYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    public function scopeForPhase($query, string $phase)
    {
        return $query->where('phase', $phase);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    // ============ ACCESSORS ============

    public function getPhaseLabelAttribute(): string
    {
        return self::PHASE_LABELS[$this->phase] ?? $this->phase;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'success',
            self::STATUS_COMPLETED => 'primary',
            self::STATUS_ARCHIVED => 'secondary',
            default => 'warning',
        };
    }

    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getTotalGroupsAttribute(): int
    {
        return $this->groups()->count();
    }

    public function getTotalStudentsAttribute(): int
    {
        return P5GroupMember::whereIn('group_id', $this->groups()->pluck('id'))
            ->where('is_active', true)
            ->count();
    }

    public function getProgressPercentAttribute(): float
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return 100;
        }
        if ($this->status === self::STATUS_DRAFT) {
            return 0;
        }

        $total = $this->total_students;
        if ($total === 0) {
            return 0;
        }

        $assessed = $this->assessments()
            ->where('status', 'approved')
            ->count();

        return round(($assessed / $total) * 100, 2);
    }

    // ============ HELPERS ============

    public function isOngoing(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->start_date <= now()
            && $this->end_date >= now();
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_ACTIVE]);
    }
}
