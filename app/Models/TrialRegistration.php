<?php

namespace Models;

class TrialRegistration extends SoftDeletableModel
{
    protected $guarded = [];
    protected $casts = ['notif_whatsapp' => 'bool', 'notif_email' => 'bool', 'notif_sms' => 'bool'];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'trial_registration_module');
    }
    public function education_level()
    {
        return $this->belongsToMany(EducationLevel::class, 'trial_registration_education_level');
    }
    public function education_levels()
    {
        return $this->education_level();
    }
    public function education_level_ids()
    {
        return $this->education_level();
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'trial_registration_role');
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

}
