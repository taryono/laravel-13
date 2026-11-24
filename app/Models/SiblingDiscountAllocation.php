<?php

namespace Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiblingDiscountAllocation extends Model
{
    use HasFactory, SoftDeletes, Blameable;

    protected $table = 'sibling_discount_allocations';

    protected $fillable = [
        'sibling_discount_rule_id',
        'target_type',
        'sibling_order_from',
        'sibling_order_to',
        'discount_type',
        'discount_value',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sibling_order_from' => 'integer',
        'sibling_order_to'   => 'integer',
        'discount_value'     => 'decimal:2',
    ];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(SiblingDiscountRule::class, 'sibling_discount_rule_id');
    }
}