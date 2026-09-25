<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends BasedModel
{
    protected $table = 'role_permission';

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
