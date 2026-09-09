<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuRole extends Model
{
    use SoftDeletes;

    protected $table = 'menu_role';

    protected $fillable = [
        'menu_id',
        'role_id',
        'is_view',
        'is_create',
        'is_edit',
        'is_delete',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_view' => 'boolean',
        'is_create' => 'boolean',
        'is_edit' => 'boolean',
        'is_delete' => 'boolean',
    ];

    /**
     * Get the menu
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Get the role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}