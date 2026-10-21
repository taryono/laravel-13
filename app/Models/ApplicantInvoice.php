<?php

namespace Models; 

class ApplicantInvoice extends BasedModel
{ 
    protected $table = 'applicant_invoices';
    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(ApplicantInvoiceItem::class, 'applicant_invoice_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function payments()
    {
        return $this->hasMany(ApplicantPayment::class, 'invoice_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}