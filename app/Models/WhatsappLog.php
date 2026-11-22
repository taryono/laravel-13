<?php
namespace Models;

class WhatsappLog extends SoftDeletableModel
{
    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function notificationTemplate()
    {
        return $this->belongsTo(NotificationTemplate::class, 'notification_template_id');
    }

    public function recipientUser()
    {
        return $this->belongsTo(RecipientUser::class, 'recipient_user_id');
    }

    public function recipientStudent()
    {
        return $this->belongsTo(RecipientStudent::class, 'recipient_student_id');
    }

    public function recipientEmployee()
    {
        return $this->belongsTo(RecipientEmployee::class, 'recipient_employee_id');
    }

    public function providerMessage()
    {
        return $this->belongsTo(ProviderMessage::class, 'provider_message_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}