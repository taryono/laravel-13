<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class P5GroupMember extends BasedModel
{

    protected $table = 'p5_group_members';

    protected $fillable = [
        'group_id',
        'student_id',
        'role',
        'specific_task',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============ KONSTANTA ============

    public const ROLE_LEADER = 'leader';
    public const ROLE_MEMBER = 'member';
    public const ROLE_SECRETARY = 'secretary';
    public const ROLE_TREASURER = 'treasurer';

    public static function roleLabels(): array
    {
        return [
            self::ROLE_LEADER => 'Ketua',
            self::ROLE_MEMBER => 'Anggota',
            self::ROLE_SECRETARY => 'Sekretaris',
            self::ROLE_TREASURER => 'Bendahara',
        ];
    }

    // ============ RELASI ============

    public function group(): BelongsTo
    {
        return $this->belongsTo(P5Group::class, 'group_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // ============ ACCESSORS ============

    public function getRoleLabelAttribute(): string
    {
        return self::roleLabels()[$this->role] ?? $this->role;
    }

    // ============ SCOPES ============

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLeaders($query)
    {
        return $query->where('role', self::ROLE_LEADER);
    }
}
