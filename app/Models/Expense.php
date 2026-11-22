<?php

namespace Models;

class Expense extends SoftDeletableModel
{
    protected $fillable = [
        'expense_number',
        'expense_date',
        'vendor_name',
        'expense_category_id', // 🔥 Berubah dari expense_category
        'description',
        'amount',
        'tax',
        'total_amount',
        'payment_method',
        'payment_status',
        'journal_entry_id',
        'attachment_path',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    // Relasi ke kategori (🔥 BARU)
    public function expense_category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    // Relasi ke journal entry
    public function journal_entry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    // Relasi ke user (approver)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relasi ke user (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor untuk nama kategori (mudah di blade)
    public function getCategoryNameAttribute()
    {
        return $this->category?->name ?? '-';
    }

    // Accessor untuk kode kategori
    public function getCategoryCodeAttribute()
    {
        return $this->category?->code ?? '-';
    }

    // Scope: Filter by category
    public function scopeOfCategory($query, $categoryId)
    {
        return $query->where('expense_category_id', $categoryId);
    }
}