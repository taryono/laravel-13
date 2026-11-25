<?php

namespace Models;

class AccountingPeriod extends SoftDeletableModel
{
    protected $table = 'accounting_periods';

    protected $fillable = [
        'organization_id',
        'fiscal_year_id',
        'period',
        'start_date',
        'end_date',
        'status',
        'closed_at',
        'closed_by',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─── Period Status Constants ───
    public const STATUS_OPEN = 'open';
    public const STATUS_SOFT_CLOSED = 'soft_closed';
    public const STATUS_CLOSED = 'closed';

    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_SOFT_CLOSED,
        self::STATUS_CLOSED,
    ];

    // ─── Relationships ───
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }

    public function closedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'closed_by');
    }
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}