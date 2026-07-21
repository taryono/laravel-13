<?php
// app/Core/Menu/MenuPermissionResolver.php
namespace App\Core\Menu;

use Illuminate\Support\Facades\DB;

class MenuPermissionResolver
{
    public function allowedModuleIds(array $roleIds): array
    {
        return DB::table('action_roles')
            ->join('actions', 'actions.id', '=', 'action_roles.action_id')
            ->whereIn('action_roles.role_id', $roleIds)
            ->where('actions.is_active', 1)
            ->where('action_roles.is_allowed', 1)
            ->distinct()
            ->pluck('actions.module_id')
            ->all();
    }
}