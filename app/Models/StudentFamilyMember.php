<?php

namespace Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\StudentFamilyMemberObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([StudentFamilyMemberObserver::class])]
class StudentFamilyMember extends Model
{
    use HasFactory, SoftDeletes, Blameable;

    protected $table = 'student_family_members';

    protected $fillable = [
        'organization_id',
        'student_family_id',
        'student_id',
        'applicant_id',
        'sibling_order',
        'relation',
        'is_active',
        'joined_at',
        'left_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sibling_order' => 'integer',
        'is_active'     => 'boolean',
        'joined_at'     => 'date',
        'left_at'       => 'date',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
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
}