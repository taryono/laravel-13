<?php

namespace Models;

class School extends BasedModel { 
    public function modules()  { return $this->belongsToMany(Module::class, 'school_modules'); }
    public function education_level() { return $this->belongsToMany(EducationLevel::class, 'school_education_level'); }
    public function users()    { return $this->hasMany(\Models\User::class); }
 
}
