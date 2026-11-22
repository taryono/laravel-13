<?php
namespace Models;
// use App\Traits\HasUniqueCode;

class Department extends SoftDeletableModel
{
    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
        'description',
        'parent_id',
        'is_active'
    ];

    // Konfigurasi untuk trait
    protected $codePrefix = 'DEPT';
    protected $codeField = 'code';

    // Relasi
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    // 🔥 Scope untuk menu dengan parent
    public function scopeHasParent($query)
    {
        return $query->whereNotNull('parent_id');
    }

    // 🔥 Scope untuk menu root (tanpa parent)
    public function scopeIsRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // 🔥 Scope untuk sorting hierarchical
    public function scopeOrderHierarchical($query, $direction = 'asc')
    {
        return $query->leftJoin('departments as parent_department', 'departments.parent_id', '=', 'parent_department.id')
            ->orderByRaw("
                CASE 
                    WHEN departments.parent_id IS NULL THEN 0 
                    ELSE 1 
                END {$direction},
                parent_department.name {$direction},
                departments.name {$direction}
            ");
    }
}