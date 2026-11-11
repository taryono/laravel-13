<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        if (! $request->hasSession()) {
            return $next($request);
        }

        $isAuthenticated = Auth::guard('web')->check() || Auth::guard('applicant')->check();
        if (! $isAuthenticated) {
            return $next($request);
        }

        // Cek apakah session masih memiliki CSRF token aktif
        if (! $request->session()->has('_token')) {
            return $this->handleExpired($request);
        }

        // Cek session lifetime manual (kompatibel Carbon 3: hindari diffInMinutes negatif)
        $lastActivity = $request->session()->get('_last_activity_at');
        $lifetime     = (int) config('session.lifetime', 120);

        if ($lastActivity && $lifetime > 0) {
            $lastActivityAt = match (true) {
                $lastActivity instanceof \DateTimeInterface => Carbon::instance($lastActivity),
                is_numeric($lastActivity) => Carbon::createFromTimestamp((int) $lastActivity),
                default => Carbon::parse($lastActivity),
            };

            if ($lastActivityAt->copy()->addMinutes($lifetime)->lessThanOrEqualTo(now())) {
                return $this->handleExpired($request);
            }
        }

        // Update last activity timestamp
        $request->session()->put('_last_activity_at', now());

        return $next($request);
    }

    /**
     * Handle session expired: logout, invalidate, redirect.
     */
    protected function handleExpired(Request $request): Response
    {
        $loginUrl = PortalAuth::loginUrlFor($request);

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('applicant')->check()) {
            Auth::guard('applicant')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (PortalAuth::wantsJson($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                'code'    => 'SESSION_EXPIRED',
            ], 401);
        }

        return redirect()->guest($loginUrl)
            ->with('warning', 'Sesi Anda telah berakhir. Silakan login kembali.');
    }
}
