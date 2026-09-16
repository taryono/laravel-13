<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Proctor
{
    /**
     * Daftar nama peran yang DIIZINKAN mengakses dasbor pengawas
     * SESUAIKAN dengan nama/nilai kolom `name` di tabel `roles` Anda!
     */
    protected array $allowedRoles = ['proctor', 'pengawas', 'admin', 'supervisor'];

    // public function handle(Request $request, Closure $next): Response
    // {
    //     $user = $request->user();

    //     // 1. Pastikan pengguna sudah terautentikasi
    //     if (!$user) {
    //         abort(401, 'Silakan login terlebih dahulu.');
    //     }

    //     // 2. Muat relasi roles jika belum dimuat
    //     if (!$user->relationLoaded('roles')) {
    //         $user->load('roles');
    //     }

    //     // 3. Cek apakah pengguna memiliki salah satu peran yang diizinkan
    //     $hasProctorRole = $user->roles->pluck('name')
    //         ->intersect($this->allowedRoles)
    //         ->isNotEmpty();

    //     if (!$hasProctorRole) {
    //         abort(403, 'Akses ditolak: Hanya pengguna berperan Pengawas yang dapat mengakses halaman ini.');
    //     }

    //     return $next($request);
    // }

    /**
     * Jika Anda ingin kode yang lebih ringkas:
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = ['proctor', 'pengawas', 'admin'];

        if (!optional($request->user())->roles->pluck('name')->contains(fn($n) => in_array($n, $allowed))) {
            abort(403, 'Akses hanya untuk Pengawas.');
        }

        return $next($request);
    }
}