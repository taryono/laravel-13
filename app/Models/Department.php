<?php
 namespace Models; 
// use App\Traits\HasUniqueCode;

class Department extends BasedModel
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
}