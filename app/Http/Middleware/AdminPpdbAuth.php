<?php
// app/Http/Middleware/AdminPpdbAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPpdbAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('ppdb-admin.login')
                ->with('error', 'Silakan login sebagai admin PPDB.');
        }

        $user = Auth::guard('web')->user();

        if (!$user->isSuperUser() && !$user->hasAnyRole(['admin-ppdb', 'super-admin', 'admin_ppdb', 'super_admin', 'Panitia PPDB', 'Super Administrator'])) {
            abort(403, 'Anda tidak memiliki akses ke panel admin PPDB.');
        }

        return $next($request);
    }
}
