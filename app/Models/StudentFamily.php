<?php

namespace Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentFamily extends Model
{
    use HasFactory, SoftDeletes, Blameable;

    protected $table = 'student_families';

    protected $fillable = [
        'organization_id',
        'family_code',
        'family_name',
        'primary_parent_id',
        'notes',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function primaryParent(): BelongsTo
    {
        return $this->belongsTo(ParentModel::class, 'primary_parent_id'); // Sesuaikan nama model Parent Anda (misal: Guardian/StudentParent)
    }

    public function members(): HasMany
    {
        return $this->hasMany(StudentFamilyMember::class, 'student_family_id');
    }

    public function eligibilities(): HasMany
    {
        return $this->hasMany(SiblingDiscountEligibility::class, 'student_family_id');
    }
}