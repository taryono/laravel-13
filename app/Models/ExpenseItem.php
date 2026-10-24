<?php
namespace Models; 

class ExpenseItem extends BasedModel
{
    // Relasi
        public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}