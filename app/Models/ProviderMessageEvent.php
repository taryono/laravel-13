<?php
namespace Models; 

class ProviderMessageEvent extends BasedModel
{
    // Relasi
        public function smsLog()
    {
        return $this->belongsTo(SmsLog::class, 'sms_log_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}