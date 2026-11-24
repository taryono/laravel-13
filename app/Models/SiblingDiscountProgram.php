<?php

namespace Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiblingDiscountProgram extends SoftDeletableModel
{
    use Blameable;

    protected $table = 'sibling_discount_programs';

    protected $fillable = [
        'organization_id',
        'school_id',
        'code',
        'name',
        'description',
        'min_sibling_count',
        'calculation_basis',
        'allocation_method',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'min_sibling_count' => 'integer',
        'start_date'        => 'date',
        'end_date'          => 'date',
        'is_active'         => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function rules(): HasMany
    {
        return $this->hasMany(SiblingDiscountRule::class, 'sibling_discount_program_id');
    }

    public function eligibilities(): HasMany
    {
        return $this->hasMany(SiblingDiscountEligibility::class, 'discount_program_id');
    }
}