<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class ActionUser extends BasedModel
{
    protected $table = 'action_user';
    protected $fillable = [
        'action_id',
        'user_id',
        'allow_view',
        'allow_create',
        'allow_edit',
        'allow_delete',
    ];

    public function action()
    {
        return $this->belongsTo(Action::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
