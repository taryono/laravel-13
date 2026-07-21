<?php

namespace App\Listeners;

use App\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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

        // Ambil data
        $role_ids = $user->roles->pluck('id')->toArray();
        $isSuper = $user->roles->where('name', 'superuser')->isNotEmpty();
        $login_as = $isSuper ? 'superuser' : 'user';

        // ✅ Tulis Log untuk pengecekan
        Log::info('[Login Session] User ID: ' . $user->id);
        Log::info('[Login Session] Role IDs: ', $role_ids);
        Log::info('[Login Session] Login As: ' . $login_as);

        // Simpan ke session seperti biasa
        session()->put('role_ids', $role_ids);
        session()->put('user_auth.login_as', $login_as);

        // Opsional: Cek ulang apakah sudah tersimpan
        Log::info('[Login Session] Session role_ids setelah disimpan: ', [session('role_ids')]);
    }
}
