<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResolveActiveOrganization
{
    /**
     * Resolve and bind the user's active organization into session and shared views.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('web')->check() || ! $request->hasSession()) {
            return $next($request);
        }

        $user = Auth::guard('web')->user();
        if (! $user || ! method_exists($user, 'organizations')) {
            return $next($request);
        }

        // Ambil semua organisasi yang aktif milik pengguna
        $organizations = $user->organizations()
            ->wherePivot('is_active', 1)
            ->get();

        if ($organizations->isEmpty()) {
            // Pengguna tidak punya organisasi aktif sama sekali → blokir
            $loginUrl = PortalAuth::loginUrlFor($request);

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = 'Akun Anda belum terdaftar di organisasi mana pun. Hubungi administrator.';

            if (PortalAuth::wantsJson($request)) {
                return response()->json(['error' => $message], 403);
            }

            return redirect()->to($loginUrl)->with('error', $message);
        }

        // 1. Cek dari sesi
        $sessionOrgId = $request->session()->get('active_organization_id');
        $activeOrg = $sessionOrgId ? $organizations->firstWhere('id', $sessionOrgId) : null;

        // 2. Kalau tidak ada di sesi, cari yang is_default = true
        if (! $activeOrg) {
            $activeOrg = $organizations->firstWhere('pivot.is_default', true);
        }

        // 3. Kalau belum ada default, ambil yang pertama
        if (! $activeOrg) {
            $activeOrg = $organizations->first();
        }

        // Simpan ke sesi (id & objek agar MainController dan MenuManagement sinkron)
        $request->session()->put('active_organization_id', $activeOrg->id);
        $request->session()->put('active_organization', $activeOrg);

        // Bagikan ke semua tampilan secara otomatis
        view()->share('activeOrganization', $activeOrg);

        return $next($request);
    }
}
