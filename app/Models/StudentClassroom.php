<?php
// app/Models/StudentClassroom.php
namespace Models;

class StudentClassroom extends SoftDeletableModel
{
    protected $table = "student_classrooms";
    protected $fillable = [
        'student_id',
        'classroom_id',
        'academic_year_id',
        'enrollment_date',
        'status',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
