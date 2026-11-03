<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class StudentAuth
{
    public function handle(Request $request, Closure $next)
    {
        $loginRoute = Route::has('student.login') ? 'student.login' : 'login';

        if (!Auth::guard('web')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login sebagai siswa.'], 401);
            }
            return redirect()->route($loginRoute)
                ->with('error', 'Silakan login sebagai siswa.');
        }

        $user = Auth::guard('web')->user();

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if ($user->isSuperUser()) {
            return $next($request);
        }

        // Cek role: student ATAU parent (ortu bisa lihat portal anak)
        if (!$user->hasAnyRole(['student', 'parent'])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda tidak memiliki akses ke portal siswa.'], 403);
            }
            abort(403, 'Anda tidak memiliki akses ke portal siswa.');
        }

        return $next($request);
    }
}
