<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asesi extends Model
{ 

    protected $guarded = [];

    public $timestamps = true;

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function getFillable()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }


}
