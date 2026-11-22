<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends SoftDeletableModel
{
    protected $fillable = [
        'organization_id',
        'employee_number',
        'employee_id',
        'user_id',
        'department_id',
        'position_id',
        'supervisor_id',
        'salary_structure_id',
        'employment_status_id',
        'name',
        'full_name',
        'nick_name',
        'nik',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'email',
        'join_date',
        'resign_date',
        'employment_status',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'npwp',
        'bpjs_ketenagakerjaan',
        'bpjs_kesehatan',
        'photo_path',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'resign_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected $dates = [
        'birth_date',
        'join_date',
        'resign_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relationship: Employee belongs to Department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relationship: Employee belongs to Position
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Relationship: Employee has supervisor
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    /**
     * Relationship: Employee is supervisor for subordinates
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    /**
     * Relationship: Employee has user account
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    /**
     * Relationship: Employee has attendance logs
     */
    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    /**
     * Relationship: Employee has daily attendance
     */
    public function attendanceDailies()
    {
        return $this->hasMany(AttendanceDaily::class);
    }

    /**
     * Relationship: Employee has employee shifts
     */
    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    /**
     * Relationship: Employee has payroll details
     */
    public function payrollDetails(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }

    /**
     * Scope: Active employees only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Employees who are not resigned
     */
    public function scopeActiveEmployees($query)
    {
        return $query->where('is_active', true)
            ->whereNull('resign_date')
            ->orWhere('resign_date', '>', now());
    }

    /**
     * Scope: Employees by department
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope: Employees by position
     */
    public function scopeByPosition($query, $positionId)
    {
        return $query->where('position_id', $positionId);
    }

    /**
     * Get full name with employee ID
     */
    public function getFullNameWithIdAttribute(): string
    {
        return $this->employee_id . ' - ' . $this->full_name;
    }

    /**
     * Get age from birth date
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }

    /**
     * Get years of service
     */
    public function getYearsOfServiceAttribute(): ?float
    {
        if (!$this->join_date) {
            return null;
        }
        return $this->join_date->diffInYears(now());
    }

    /**
     * Check if employee is active (not resigned)
     */
    public function getIsActiveEmployeeAttribute(): bool
    {
        return $this->is_active && (!$this->resign_date || $this->resign_date > now());
    }

    /**
     * Get employment status label
     */
    public function getEmploymentStatusLabelAttribute(): string
    {
        $labels = [
            'tetap' => 'Tetap',
            'kontrak' => 'Kontrak',
            'probation' => 'Probation',
            'freelance' => 'Freelance',
        ];
        return $labels[$this->employment_status] ?? '-';
    }

    /**
     * Get gender label
     */
    public function getGenderLabelAttribute(): string
    {
        $labels = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
        ];
        return $labels[$this->gender] ?? '-';
    }

    /**
     * Relasi ke SalaryStructure
     * Satu karyawan memiliki satu struktur gaji
     */
    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }

    /**
     * Hitung gaji karyawan berdasarkan struktur yang digunakan
     */
    public function calculateSalary()
    {
        if (!$this->salaryStructure) {
            return null;
        }

        $baseSalary = $this->base_salary ?? 0;
        return $this->salaryStructure->calculateNetSalary($baseSalary);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function isTeacher(): bool
    {
        return $this->teacher()->exists();
    }
}
