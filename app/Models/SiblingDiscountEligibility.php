<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiblingDiscountEligibility extends Model
{
    use HasFactory, SoftDeletes, Blameable;

    protected $table = 'sibling_discount_eligibilities';

    protected $fillable = [
        'organization_id',
        'academic_year_id',
        'student_family_id',
        'student_id',
        'applicant_id',
        'sibling_count',
        'sibling_order',
        'is_eligible',
        'discount_program_id',
        'snapshot_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sibling_count' => 'integer',
        'sibling_order' => 'integer',
        'is_eligible'   => 'boolean',
        'snapshot_date' => 'date',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(StudentFamily::class, 'student_family_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function discountProgram(): BelongsTo
    {
        return $this->belongsTo(SiblingDiscountProgram::class, 'discount_program_id');
    }
}