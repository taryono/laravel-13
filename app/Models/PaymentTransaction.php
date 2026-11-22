<?php
namespace Models;

class PaymentTransaction extends SoftDeletableModel
{
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }

    public function paymentChannel()
    {
        return $this->belongsTo(PaymentChannel::class, 'payment_channel_id');
    }

    public function studentInvoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'student_invoice_id');
    }

    public function applicantInvoice()
    {
        return $this->belongsTo(ApplicantInvoice::class, 'applicant_invoice_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function midtransTransaction()
    {
        return $this->belongsTo(MidtransTransaction::class, 'midtrans_transaction_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function studentPayment()
    {
        return $this->belongsTo(StudentPayment::class, 'student_payment_id');
    }

    public function applicantPayment()
    {
        return $this->belongsTo(ApplicantPayment::class, 'applicant_payment_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}