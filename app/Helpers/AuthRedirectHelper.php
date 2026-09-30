<?php
// app/Helpers/AuthRedirectHelper.php

namespace App\Helpers;

class AuthRedirectHelper
{
    /**
     * Tentukan URL redirect setelah login berdasarkan role user
     */
    public static function redirectAfterLogin($user): string
    {
        // Prioritas tertinggi dulu
        if ($user->hasRole('super_admin')) {
            return route('admin.dashboard');
        }
        if ($user->hasRole('admin_ppdb')) {
            return route('ppdb-admin.dashboard');
        }
        if ($user->hasRole('admin_sekolah')) {
            return route('admin.dashboard');
        }
        if ($user->hasRole('guru')) {
            return route('admin.dashboard');
        }
        if ($user->hasRole('student')) {
            return route('student.dashboard');
        }
        if ($user->hasRole('parent')) {
            return route('student.dashboard'); // Ortu pakai portal siswa
        }

        return '/';
    }
}
