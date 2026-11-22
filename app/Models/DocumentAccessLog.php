<?php
namespace Models;

class DocumentAccessLog extends SoftDeletableModel
{
    // Relasi
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function documentVersion()
    {
        return $this->belongsTo(DocumentVersion::class, 'document_version_id');
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