<?php

namespace Models;

class Action extends BasedModel
{
    protected $fillable = [
        'menu_id', 'name', 'display_name', 'description',
        'is_allow_view', 'is_allow_create', 'is_allow_edit', 'is_allow_delete', 'is_active'
    ]; 

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * @foreign
     * @comment=Relasi ke user
     * @on=user
     * @references=id
     * @type=unsignedBigInteger
     * @column=user_id
     * @nullable
     * @onDelete=cascade
     * @onUpdate=cascade
     * @constraintName=fk_actions_users
     * 
     */
    public function users()
    {
        return $this->belongsToMany(\Models\User::class, 'action_user');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'action_role');
    } 
}
