<?php
// app/Core/Menu/MenuPermissionResolver.php
namespace App\Core\Menu;

use Illuminate\Support\Facades\DB;

class MenuPermissionResolver
{
    public function allowedModuleIds(array $roleIds): array
    {
        return DB::table('action_role')
            ->join('actions', 'actions.id', '=', 'action_role.action_id')
            ->whereIn('action_role.role_id', $roleIds)
            ->where('actions.is_active', 1)
            ->where('action_role.is_allowed', 1)
            ->distinct()
            // ->pluck('actions.module_id')
            ->all();
    }
}
