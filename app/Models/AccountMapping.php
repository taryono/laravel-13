<?php

namespace Models; 

class AccountMapping extends BasedModel
{ 
   protected $table = 'account_mappings';

    protected $fillable = [
        'organization_id',
        'source_type',
        'source_code',
        'debit_account_id',
        'credit_account_id',
        'description',
        'is_active',
    ]; 

    // ─── Source Type Constants ───
    public const SOURCE_PAYROLL_COMPONENT = 'payroll_component';
    public const SOURCE_SALES             = 'sales';
    public const SOURCE_PURCHASE          = 'purchase';
    public const SOURCE_EXPENSE           = 'expense';
    public const SOURCE_TAX               = 'tax';
    public const SOURCE_LOAN              = 'loan';
    public const SOURCE_BANK              = 'bank';
    public const SOURCE_OTHER             = 'other';

    public const SOURCE_TYPES = [
        self::SOURCE_PAYROLL_COMPONENT,
        self::SOURCE_SALES,
        self::SOURCE_PURCHASE,
        self::SOURCE_EXPENSE,
        self::SOURCE_TAX,
        self::SOURCE_LOAN,
        self::SOURCE_BANK,
        self::SOURCE_OTHER,
    ];

    // ─── Relationships ───
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function debit_account()
    {
        return $this->belongsTo(Account::class, 'debit_account_id');
    }

    public function credit_account()
    {
        return $this->belongsTo(Account::class, 'credit_account_id');
    }
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}