<?php

namespace Models;

class Role extends BasedModel
{
    public function user()
    {
        return $this->belongsToMany(\App\Models\User::class);
    }

    public function menu()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function hasAction($id)
    {
        return $this->action_role()->whereHas('action', function ($query) use ($id) {$query->where('id', $id);})->where("is_allowed", 1)->count() > 0;
    }

    /**
     * A description of the entire PHP function.
     *
     * @param datatype $paramname description
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */

    public function action_role()
    {
        return $this->hasMany(ActionRole::class);
    }
}
