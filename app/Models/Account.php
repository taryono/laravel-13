<?php

namespace Models; 

class Account extends BasedModel
{ 
   protected $table = 'accounts';
    
    protected $fillable = [
        'code',
        'name',
        'account_category_id',
        'parent_id',
        'account_type',
        'normal_balance',
        'currency_code',
        'description',
        'opening_balance',
        'current_balance',
        'is_active',
        'is_system'
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    // ─── Account Type Constants ───
    public const TYPE_HEADER = 'header';
    public const TYPE_DETAIL = 'detail';

    public const ACCOUNT_TYPES = [
        self::TYPE_HEADER,
        self::TYPE_DETAIL,
    ];

    // ─── Normal Balance Constants ───
    public const BALANCE_DEBIT  = 'debit';
    public const BALANCE_CREDIT = 'credit';

    public const NORMAL_BALANCES = [
        self::BALANCE_DEBIT,
        self::BALANCE_CREDIT,
    ];
    // Relasi ke parent (induk)
    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    // Relasi ke children (anak-anak)
    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    } 

    // Scope untuk hanya akun detail (bukan header)
    public function scopeDetails($query)
    {
        return $query->where('account_type', 'detail');
    }

    // Scope untuk hanya akun aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk root (parent_id null)
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    // ─── Relationships ───
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Override generate code for COA
    public function generateUniqueCode(): string
    {
        $category = AccountCategory::find($this->account_category_id);
        $prefix = $category ? $category->code : 'ACC';
        
        $last = $this->where('code', 'LIKE', $prefix . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->code, strrpos($last->code, '-') + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Relasi
    public function account_category()
    {
        return $this->belongsTo(AccountCategory::class);
    } 

    public function journalDetails()
    {
        return $this->hasMany(JournalDetail::class);
    }

    // Helper
    public function getBalanceAttribute()
    {
        $debit = $this->journalDetails()->sum('debit');
        $credit = $this->journalDetails()->sum('credit');
        
        if ($this->normal_balance === 'debit') {
            return $this->opening_balance + $debit - $credit;
        } else {
            return $this->opening_balance + $credit - $debit;
        }
    } 
}