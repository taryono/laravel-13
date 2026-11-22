<?php

namespace Models;

class Invoice extends SoftDeletableModel
{
    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'discount',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'journal_entry_id',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    // Override generate code
    public function generateUniqueCode(): string
    {
        $year = date('Y');

        $last = $this->where('invoice_number', 'LIKE', 'INV-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->invoice_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'INV-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Relasi
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'draft' => 'Draft',
            'sent' => 'Terkirim',
            'paid' => 'Lunas',
            'overdue' => 'Jatuh Tempo',
            'cancelled' => 'Dibatalkan'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getIsOverdueAttribute()
    {
        return $this->status !== 'paid' && $this->due_date < now();
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}