<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends SoftDeletableModel
{
    protected $appends = ['level'];
    protected $fillable = [
        'organization_id',
        'parent_id',
        'subject_category_id',
        'code',
        'name',
        'abbreviation',
        'description',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getLevelAttribute(): int
    {
        $level = 0;
        $parent = $this->parent;
        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }
        return $level;
    }


    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    // Relasi Induk
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Relasi Anak
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->with('children');
    }

    // Hanya Induk (tingkat atas)
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    // Kategori
    public function subject_category(): BelongsTo
    {
        return $this->belongsTo(SubjectCategory::class, 'subject_category_id');
    }

    // Format Data untuk Pohon
    public static function buildTree($parentId = null, $level = 0)
    {
        return self::with(['subject_category', 'children'])
            ->where('parent_id', $parentId)
            ->orderBy('name')
            ->get()
            ->map(function ($subject) use ($level) {
                $subject->level = $level;
                $subject->indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
                $subject->children_list = self::buildTree($subject->id, $level + 1);
                return $subject;
            });
    }

    protected static function booted()
    {
        static::saving(function ($subject) {
            if ($subject->parent_id) {
                $parent = static::find($subject->parent_id);
                $subject->depth = $parent->depth + 1;
                $subject->path = $parent->path . '/' . $subject->id;
            } else {
                $subject->depth = 0;
                $subject->path = (string) $subject->id;
            }
        });
    }
}
