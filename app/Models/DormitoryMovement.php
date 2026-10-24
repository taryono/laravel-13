<?php
namespace Models; 

class DormitoryMovement extends BasedModel
{
    // Relasi
        public function dormitoryAssignment()
    {
        return $this->belongsTo(DormitoryAssignment::class, 'dormitory_assignment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function fromDormitory()
    {
        return $this->belongsTo(FromDormitory::class, 'from_dormitory_id');
    }

    public function fromRoom()
    {
        return $this->belongsTo(FromRoom::class, 'from_room_id');
    }

    public function toDormitory()
    {
        return $this->belongsTo(ToDormitory::class, 'to_dormitory_id');
    }

    public function toRoom()
    {
        return $this->belongsTo(ToRoom::class, 'to_room_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}