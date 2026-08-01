<?php

namespace Models;

class Role extends BasedModel
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\Models\User::class, 'role_user')
            ->withPivot(['is_default'])
            ->withTimestamps();
    }

     public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menu_role')
            ->withPivot(['is_view', 'is_create', 'is_edit', 'is_delete'])
            ->withTimestamps();
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

    public function actions()
    {
        return $this->hasMany(Action::class, 'action_role')->withTimestamps();;
    } 
}
