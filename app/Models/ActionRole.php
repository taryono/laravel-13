<?php

namespace Models;

class ActionRole extends BasedModel
{
    protected $table = 'action_role';

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }
}
