<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function competence()
    {
        return $this->belongsTo(Competence::class);
    }

    public function certification()
    {
        return $this->belongsTo(Certification::class);
    }
}
