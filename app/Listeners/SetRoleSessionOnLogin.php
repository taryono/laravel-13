<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;   
use Illuminate\Support\Facades\Log;

class SetRoleSessionOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    // app/Listeners/SetRoleSessionOnLogin.php
    public function handle(Login $event)
    {
        $user = $event->user;

        // Jika bukan instance User utama atau tidak memiliki relasi roles (misal: Applicant), abaikan
        if (!($user instanceof \App\Models\User) || !method_exists($user, 'roles')) {
            return;
        }

        // Ambil semua role milik user beserta data pivot-nya
        $roles = $user->roles;
        if (!$roles) {
            return;
        }

        // Cari yang ditandai sebagai default di tabel pivot
        $default_role = $roles->firstWhere('pivot.is_default', 1);

        // Jika belum ada yang ditandai default, ambil role pertama dan tandai otomatis
        if (!$default_role && $roles->isNotEmpty()) {
            $default_role = $roles->first();
            // Update otomatis agar nanti tidak bingung
            $user->roles()->updateExistingPivot($default_role->id, ['is_default' => 1]);
        }

        // Jika benar-benar tidak punya role
        if (!$default_role) {
            session()->forget(['user_auth', 'all_role_ids', 'active_role_id']);
            return;
        }

        // Simpan ke session
        $user->setAuthSession($default_role, $roles);
    }
}
