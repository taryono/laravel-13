<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'iso',
        'name',
        'printable_name',
        'iso3',
        'numcode',
    ];

    public function region()
    {
        return $this->hasMany(Region::class, 'country_id');
    }
}
