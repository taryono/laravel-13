<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subdistrict extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'district_id',
        'zip',
        'latitude',
        'longitude',
        'elevation',
        'timezone',
        'ordering',
        'last_id',
        'is_free_text',
        'verified_at',
        'is_user_input',
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
