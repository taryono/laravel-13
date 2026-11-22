<?php

namespace Models;

class ApplicantPayment extends SoftDeletableModel
{
    protected $table = 'applicant_payments';
    protected $guarded = ['id'];

    public function invoice()
    {
        return $this->belongsTo(ApplicantInvoice::class, 'invoice_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}