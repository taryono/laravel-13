<?php

namespace Models;

class ActionPermission extends SoftDeletableModel
{
    protected $table = 'action_permission';

    protected $fillable = [
        'action_id',
        'permission_id',
    ];
}
