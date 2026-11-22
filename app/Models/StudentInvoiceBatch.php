<?php
namespace Models;

class StudentInvoiceBatch extends SoftDeletableModel
{
    // Relasi
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}