<?php
// app/Http/Middleware/ParentAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('parent.login')
                ->with('error', 'Silakan login sebagai orang tua.');
        }

        $user = Auth::guard('web')->user();

        if (!$user->hasRole('parent')) {
            abort(403, 'Anda tidak memiliki akses ke portal orang tua.');
        }

        return $next($request);
    }
}
