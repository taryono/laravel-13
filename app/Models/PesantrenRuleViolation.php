<?php
namespace Models;

class PesantrenRuleViolation extends SoftDeletableModel
{
    // Relasi
    public function pesantrenRule()
    {
        return $this->belongsTo(PesantrenRule::class, 'pesantren_rule_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function dormitory()
    {
        return $this->belongsTo(Dormitory::class, 'dormitory_id');
    }

    public function reporter()
    {
        return $this->belongsTo(Reporter::class, 'reporter_id');
    }

    public function handler()
    {
        return $this->belongsTo(Handler::class, 'handler_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}