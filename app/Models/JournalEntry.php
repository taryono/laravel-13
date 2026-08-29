<?php

namespace Models; 

class JournalEntry extends BasedModel
{ 
    protected $table = 'journal_entries';
    
    protected $fillable = [
        'journal_number',
        'journal_date',
        'period',
        'description',
        'source_type',
        'source_id',
        'reference_number',
        'total_debit',
        'total_credit',
        'status',
        'approved_by',
        'approved_at',
        'posted_by',
        'posted_at',
        'created_by',
        'notes'
    ];

    protected $casts = [
        'journal_date' => 'date',
        'approved_at' => 'datetime',
        'posted_at' => 'datetime',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
    ];

    // Override generate code
    public function generateUniqueCode(): string
    {
        $date = date('Ymd');
        
        $last = $this->where('journal_number', 'LIKE', 'JRN-' . $date . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->journal_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'JRN-' . $date . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Relasi
    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}