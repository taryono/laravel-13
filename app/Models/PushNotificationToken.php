<?php
namespace Models; 

class PushNotificationToken extends BasedModel
{
    // Relasi
        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}