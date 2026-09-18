<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class P5Group extends BasedModel
{
    protected $table = 'p5_groups';

    protected $fillable = [
        'project_id',
        'name',
        'topic',
        'description',
        'leader_student_id',
        'facilitator_id',
        'product_name',
        'product_photo',
        'product_description',
        'status',
        'order',
        'created_by',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // ============ KONSTANTA ============

    public const STATUS_FORMING = 'forming';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';

    // ============ RELASI ============

    public function project(): BelongsTo
    {
        return $this->belongsTo(P5Project::class, 'project_id');
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'leader_student_id');
    }

    public function facilitator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'facilitator_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function members(): HasMany
    {
        return $this->hasMany(P5GroupMember::class, 'group_id');
    }

    public function activeMembers(): HasMany
    {
        return $this->members()->where('is_active', true);
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'p5_group_members',
            'group_id',
            'student_id'
        )->withPivot(['role', 'specific_task', 'is_active'])->withTimestamps();
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(P5Assessment::class, 'group_id');
    }

    // ============ SCOPES ============

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ============ ACCESSORS ============

    public function getMemberCountAttribute(): int
    {
        return $this->activeMembers()->count();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'success',
            self::STATUS_COMPLETED => 'primary',
            default => 'secondary',
        };
    }
}
