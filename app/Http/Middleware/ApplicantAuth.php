<?php
// app/Http/Middleware/ApplicantAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('applicant')->check()) {
            return redirect()->route('ppdb.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek status applicant masih aktif
        $applicant = Auth::guard('applicant')->user();
        if (!$applicant->is_active) {
            Auth::guard('applicant')->logout();
            return redirect()->route('ppdb.login')
                ->with('error', 'Akun Anda tidak aktif.');
        }

        return $next($request);
    }
}
