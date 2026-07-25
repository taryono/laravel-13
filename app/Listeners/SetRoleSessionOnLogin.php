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
     public function handle(Login $event)
    {
        $user = $event->user;

        $roles = $user->roles; // pastikan sudah eager load kalau perlu
        $role_ids = $roles->pluck('id')->toArray();
        $default_role = $user->roles()->firstWhere('is_default', 1);
        $isSuper = $roles->contains('name', 'superuser');
        $login_as = $isSuper ? 'superuser' : 'user';

        Log::info('[LOGIN BERHASIL] User: ' . $user->email);
        Log::info('[LOGIN BERHASIL] Role IDs: ', $role_ids);
        Log::info('[LOGIN BERHASIL] Superuser: ' . ($isSuper ? 'YA' : 'TIDAK'));

        session()->put('role_ids', $role_ids);
        session()->put('user_auth.login_as', $login_as);
        session()->put('user_auth.role_as', $default_role->name ?? 'user');
    }
}
