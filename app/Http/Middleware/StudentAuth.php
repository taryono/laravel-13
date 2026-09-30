<?php
// app/Http/Middleware/StudentAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('student.login')
                ->with('error', 'Silakan login sebagai siswa.');
        }

        $user = Auth::guard('web')->user();

        // Cek role: student ATAU parent (ortu bisa lihat portal anak)
        if (!$user->hasAnyRole(['student', 'parent'])) {
            abort(403, 'Anda tidak memiliki akses ke portal siswa.');
        }

        return $next($request);
    }
}
