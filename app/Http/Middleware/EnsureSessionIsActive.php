<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip kalau user memang belum login (biar tidak loop redirect)
        if (!Auth::check() && !$request->session()->has('login_web_' . sha1(config('auth.guards.web.provider' ?? '')))) {
            return $next($request);
        }

        // Cek apakah session masih "fresh" (masih ada data user)
        if (Auth::check() && !$request->session()->has('_token')) {
            return $this->handleExpired($request);
        }

        // Cek session lifetime manual (double protection)
        $lastActivity = $request->session()->get('_last_activity_at');
        $lifetime     = config('session.lifetime', 120); // menit

        if ($lastActivity && now()->diffInMinutes($lastActivity) >= $lifetime) {
            return $this->handleExpired($request);
        }

        // Update last activity
        $request->session()->put('_last_activity_at', now());

        return $next($request);
    }

    /**
     * Handle session expired: logout, invalidate, redirect.
     */
    protected function handleExpired(Request $request): Response
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                'code'    => 'SESSION_EXPIRED',
            ], 401);
        }

        return redirect()->guest(route('login'))
            ->with('warning', 'Sesi Anda telah berakhir. Silakan login kembali.');
    }
}
