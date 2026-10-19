<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResolveActiveOrganization
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !$request->hasSession()) {
            return $next($request);
        }

        $user = Auth::user();

        // Ambil semua organisasi yang aktif milik pengguna
        $organizations = $user->organizations()
            ->wherePivot('is_active', 1)
            ->get();
        logger('Daftar organisasi: ' . print_r($organizations->toArray(), true));

        if ($organizations->isEmpty()) {
            // Pengguna tidak punya organisasi aktif sama sekali → blokir
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akun Anda belum terdaftar di organisasi mana pun. Hubungi administrator.');
        }

        // 1. Cek dari sesi
        $sessionOrgId = $request->session()->get('active_organization_id');
        $activeOrg = null;

        if ($sessionOrgId) {
            $activeOrg = $organizations->firstWhere('id', $sessionOrgId);
        }

        // 2. Kalau tidak ada di sesi, cari yang is_default = true
        if (!$activeOrg) {
            $activeOrg = $organizations->firstWhere('pivot.is_default', true);
        }

        // 3. Kalau belum ada default, ambil yang pertama
        if (!$activeOrg) {
            $activeOrg = $organizations->first();
        }

        // Simpan ke sesi untuk pemakaian berikutnya
        $request->session()->put('active_organization_id', $activeOrg->id);

        // Bagikan ke semua tampilan secara otomatis
        view()->share('activeOrganization', $activeOrg);

        return $next($request);
    }
}
