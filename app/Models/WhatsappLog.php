<?php
namespace Models;

class WhatsappLog extends BasedModel
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

    public function recipient()
    {
        return $this->morphTo(); // recipient_type + recipient_id
    }

    public function events()
    {
        return $this->hasMany(ProviderMessageEvent::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function reference()
    {
        return $this->belongsTo(ReferenceValue::class, 'reference_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}