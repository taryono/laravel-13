<?php

namespace Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiblingDiscountRule extends Model
{
    use HasFactory, SoftDeletes, Blameable;

    protected $table = 'sibling_discount_rules';

    protected $fillable = [
        'sibling_discount_program_id',
        'min_sibling_count',
        'max_sibling_count',
        'discount_type',
        'discount_value',
        'priority',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'min_sibling_count' => 'integer',
        'max_sibling_count' => 'integer',
        'discount_value'    => 'decimal:2',
        'priority'          => 'integer',
        'is_active'         => 'boolean',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(SiblingDiscountProgram::class, 'sibling_discount_program_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(SiblingDiscountAllocation::class, 'sibling_discount_rule_id');
    }
}