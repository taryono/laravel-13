<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'region_id',
        'postal_code',
        'type',
        'is_free_text',
        'verified_at',
        'is_user_input',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'city_id');
    }
}
