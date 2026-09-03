<?php

namespace Models; 

class AccountCategory extends BasedModel
{ 
    protected $table = 'account_categories';

    protected $fillable = [
        'organization_id',
        'code',
        'name',
        'type',
        'normal_balance',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ─── Account Type Constants (English) ───
    public const TYPE_ASSET = 'asset';
    public const TYPE_LIABILITY = 'liability';
    public const TYPE_EQUITY = 'equity';
    public const TYPE_REVENUE = 'revenue';
    public const TYPE_EXPENSE = 'expense';

    public const ACCOUNT_TYPES = [
        self::TYPE_ASSET,
        self::TYPE_LIABILITY,
        self::TYPE_EQUITY,
        self::TYPE_REVENUE,
        self::TYPE_EXPENSE,
    ];

    // ─── Normal Balance Constants ───
    public const BALANCE_DEBIT = 'debit';
    public const BALANCE_CREDIT = 'credit';

    public const NORMAL_BALANCES = [
        self::BALANCE_DEBIT,
        self::BALANCE_CREDIT,
    ];

    // ─── Relationships ───
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}