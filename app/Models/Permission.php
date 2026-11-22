<?php

namespace Models;

class Permission extends SoftDeletableModel
{
    protected $table = 'permissions';
    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }
}
