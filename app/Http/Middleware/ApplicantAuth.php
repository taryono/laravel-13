<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApplicantAuth
{
    /**
     * Authenticate PPDB applicants using the dedicated applicant guard.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('applicant')->check()) {
            return PortalAuth::unauthenticatedResponse($request, 'Silakan login terlebih dahulu.', 'ppdb.login');
        }

        $applicant = Auth::guard('applicant')->user();
        if (! $applicant || ! ($applicant->is_active ?? true)) {
            Auth::guard('applicant')->logout();

            if (PortalAuth::wantsJson($request)) {
                return response()->json(['error' => 'Akun Anda tidak aktif.'], 403);
            }

            return redirect()->to(PortalAuth::loginUrlFor($request, 'ppdb.login'))
                ->with('error', 'Akun Anda tidak aktif.');
        }

        return $next($request);
    }
}
