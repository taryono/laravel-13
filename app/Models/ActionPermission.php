<?php

namespace Models;

class ActionPermission extends BasedModel
{
    protected $table = 'action_permission';

    protected $fillable = [
        'action_id',
        'permission_id',
    ];
}
