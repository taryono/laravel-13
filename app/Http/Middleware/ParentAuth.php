<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class ParentAuth
{
    public function handle(Request $request, Closure $next)
    {
        $loginRoute = Route::has('parent.login') ? 'parent.login' : 'login';

        if (!Auth::guard('web')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login sebagai orang tua.'], 401);
            }
            return redirect()->route($loginRoute)
                ->with('error', 'Silakan login sebagai orang tua.');
        }

        $user = Auth::guard('web')->user();

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if ($user->isSuperUser()) {
            return $next($request);
        }

        if (!$user->hasRole('parent')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda tidak memiliki akses ke portal orang tua.'], 403);
            }
            abort(403, 'Anda tidak memiliki akses ke portal orang tua.');
        }

        return $next($request);
    }
}
