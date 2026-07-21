<?php

namespace Models;

class Action extends BasedModel
{
    /**
     * @foreign
     * @comment=Relasi ke modul
     * @on=module
     * @references=id
     * @type=unsignedBigInteger
     * @column=module_id
     * @nullable
     * @onDelete=cascade 
     * @onUpdate=cascade
     * @constraintName=fk_actions_modules
     * 
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
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
    public function user()
    {
        return $this->belongsToMany(\App\Models\User::class);
    }

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }
}
