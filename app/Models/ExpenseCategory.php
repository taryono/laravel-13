<?php

namespace Models;

class ExpenseCategory extends SoftDeletableModel
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'parent_id',
        'sort_order',
        'created_by',
    ];

    // Relasi: Kategori anak
    public function children()
    {
        return $this->hasMany(ExpenseCategory::class, 'parent_id');
    }

    // Relasi: Kategori parent
    public function parent()
    {
        return $this->belongsTo(ExpenseCategory::class, 'parent_id');
    }

    // Relasi: Expenses
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // Scope: Hanya yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Hanya parent (level 1)
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}