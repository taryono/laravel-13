<?php

namespace Models;

class Action extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'menu_id',
        'name',
        'display_name',
        'description',
        'is_active'
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
    public function actionRoles()
    {
        return $this->hasMany(ActionRole::class);
    }

    public function actionUsers()
    {
        return $this->hasMany(ActionUser::class);
    }
}
