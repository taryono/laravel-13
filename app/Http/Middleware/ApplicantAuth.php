<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class ApplicantAuth
{
    public function handle(Request $request, Closure $next)
    {
        $loginRoute = Route::has('ppdb.login') ? 'ppdb.login' : 'login';

        if (!Auth::guard('applicant')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }
            return redirect()->route($loginRoute)
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $applicant = Auth::guard('applicant')->user();
        if (!$applicant || !($applicant->is_active ?? true)) {
            Auth::guard('applicant')->logout();
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akun Anda tidak aktif.'], 403);
            }
            return redirect()->route($loginRoute)
                ->with('error', 'Akun Anda tidak aktif.');
        }

        return $next($request);
    }
}
