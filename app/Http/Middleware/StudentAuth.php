<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentAuth
{
    /**
     * Authenticate students (or parents viewing student portal) and superusers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('web')->check()) {
            return PortalAuth::unauthenticatedResponse($request, 'Silakan login sebagai siswa.', 'student.login');
        }

        $user = Auth::guard('web')->user();

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if (PortalAuth::isSuperUser($user)) {
            return $next($request);
        }

        // Cek role: student/siswa ATAU parent/orang-tua (ortu bisa lihat portal anak)
        if (! $user || ! method_exists($user, 'hasAnyRole') || ! $user->hasAnyRole(PortalAuth::STUDENT_PORTAL_ROLES)) {
            return PortalAuth::forbiddenResponse($request, 'Anda tidak memiliki akses ke portal siswa.');
        }

        return $next($request);
    }
}
