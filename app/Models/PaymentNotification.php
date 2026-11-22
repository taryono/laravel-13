<?php
namespace Models;

class PaymentNotification extends SoftDeletableModel
{
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class, 'payment_transaction_id');
    }

    public function studentInvoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'student_invoice_id');
    }

    public function applicantInvoice()
    {
        return $this->belongsTo(ApplicantInvoice::class, 'applicant_invoice_id');
    }

    public function recipientUser()
    {
        return $this->belongsTo(RecipientUser::class, 'recipient_user_id');
    }

    public function providerMessage()
    {
        return $this->belongsTo(ProviderMessage::class, 'provider_message_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}