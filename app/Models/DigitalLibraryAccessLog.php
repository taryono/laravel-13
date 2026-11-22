<?php
namespace Models;

class DigitalLibraryAccessLog extends BasedModel
{
    // Relasi
    public function digitalLibraryItem()
    {
        return $this->belongsTo(DigitalLibraryItem::class, 'digital_library_item_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}