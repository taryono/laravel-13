<?php

namespace Models; 

class Account extends BasedModel
{ 
   protected $table = 'accounts';
    
    protected $fillable = [
        'code',
        'name',
        'category_id',
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

    // Override generate code for COA
    public function generateUniqueCode(): string
    {
        $category = AccountCategory::find($this->category_id);
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
    public function category()
    {
        return $this->belongsTo(AccountCategory::class);
    }

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
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

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}