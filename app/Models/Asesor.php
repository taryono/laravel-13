<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
