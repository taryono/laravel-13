<?php
namespace Models; 

class LibraryVisit extends BasedModel
{
    // Relasi
        public function libraryMembership()
    {
        return $this->belongsTo(LibraryMembership::class, 'library_membership_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}