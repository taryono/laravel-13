<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminPpdbAuth
{
    public function handle(Request $request, Closure $next)
    {
        $loginRoute = Route::has('ppdb-admin.login') ? 'ppdb-admin.login' : 'login';

        if (!Auth::guard('web')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login sebagai admin.'], 401);
            }
            return redirect()->route($loginRoute)
                ->with('error', 'Silakan login sebagai admin.');
        }

        $user = Auth::guard('web')->user();

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if ($user->isSuperUser()) {
            return $next($request);
        }

        if (!$user->hasAnyRole(['admin-ppdb', 'super-admin', 'admin_ppdb', 'super_admin', 'Panitia PPDB', 'Super Administrator', 'Administrator', 'admin', 'Admin'])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda tidak memiliki akses ke panel admin PPDB.'], 403);
            }
            abort(403, 'Anda tidak memiliki akses ke panel admin PPDB.');
        }

        return $next($request);
    }
}
