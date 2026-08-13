<?php

namespace Models;

class TrialRegistration extends BasedModel {
    protected $guarded = [];
    protected $casts = ['notif_whatsapp' => 'bool', 'notif_email' => 'bool', 'notif_sms' => 'bool'];

    public function modules()  { return $this->belongsToMany(Module::class, 'trial_registration_modules'); }
    public function education_level() { return $this->belongsToMany(EducationLevel::class, 'trial_registration_education_level'); }
    public function roles()    { return $this->belongsToMany(Role::class, 'trial_registration_roles'); }
    public function school()   { return $this->belongsTo(School::class); }
    public function user()     { return $this->belongsTo(App\Models\User::class); }
 
}
