<?php

namespace Models;

class AssetCategory extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'school_id',
        'code',
        'name',
        'description',
        'parent_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'parent_id' => 'integer',
        'organization_id' => 'integer',
        'school_id' => 'integer',
    ];

    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function parent()
    {
        return $this->belongsTo(AssetCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AssetCategory::class, 'parent_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function isParent()
    {
        return $this->children()->exists();
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Hanya kategori tingkat teratas
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    // Semua anak termasuk turunan bertingkat
    public function getAllChildrenIds(): array
    {
        $ids = collect([$this->id]);
        foreach ($this->children as $child) {
            $ids = $ids->merge($child->getAllChildrenIds());
        }
        return $ids->unique()->all();
    }

    // Tambahkan metode bantu
    public function isDescendantOf($categoryId): bool
    {
        if ($this->parent_id === null) return false;
        if ($this->parent_id === $categoryId) return true;

        $parent = $this->parent;
        return $parent?->isDescendantOf($categoryId) ?? false;
    }

    // Boot method untuk mencegah simpan
    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->parent_id && $model->isDescendantOf($model->id)) {
                throw new \Exception('Tidak dapat menetapkan kategori turunan sebagai induk — akan membentuk siklus!');
            }
        });
    }
}
