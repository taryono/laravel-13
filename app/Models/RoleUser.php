<?php

namespace Models;

use Models\User;

class RoleUser extends SoftDeletableModel
{
    public $table = 'role_user';

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function user()
    {
        return $this->belongsTo(\Models\User::class);
    }
}
