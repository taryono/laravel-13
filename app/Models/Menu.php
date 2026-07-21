<?php 
namespace Models;
use Models\Role;
class Menu extends BasedModel
{
    protected $fillable = [
        'name',
        'text',
        'url',
        'action',
        'parent_id',
        'is_active',
        'role',
        'icon',
        'icon_type',
        'sequence',
        'can',
        'node_id',
        'is_private',
        'is_publish',
        'classname',
        'module_id',
        'menu_type_id',
        'icon_color',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class,'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class,'parent_id')
            ->orderBy('sequence');
    } 

    public function getRoleByName()
    {
        if (empty($this->role)) return null;
        return Role::where('name', $this->role)->first();
    }
}