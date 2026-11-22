<?php
namespace Models;

class ApplicantNotification extends SoftDeletableModel
{
    // Relasi
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
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