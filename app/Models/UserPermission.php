<?php

namespace Models;

class UserPermission extends SoftDeletableModel
{
    protected $table = 'user_permission';

    protected $fillable = [
        'user_id',
        'permission_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
