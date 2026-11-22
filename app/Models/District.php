<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'city_id',
        'last_id',
        'is_free_text',
        'verified_at',
        'is_user_input',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function subdistrict()
    {
        return $this->hasMany(Subdistrict::class, 'district_id');
    }
}
