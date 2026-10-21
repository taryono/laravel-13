<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantInvoiceItem extends Model
{
    protected $table = 'applicant_invoice_items';
    protected $guarded = ['id'];

    public function invoice()
    {
        return $this->belongsTo(ApplicantInvoice::class, 'applicant_invoice_id');
    }
}
