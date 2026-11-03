<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Proctor
{
    /**
     * Daftar peran yang diizinkan mengakses dasbor pengawas.
     */
    protected array $allowedRoles = ['proctor', 'pengawas', 'admin', 'supervisor'];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }
            return redirect()->route('login');
        }

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if ($user->isSuperUser()) {
            return $next($request);
        }

        if ($user->hasAnyRole($this->allowedRoles)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Akses hanya untuk Pengawas.'], 403);
        }

        abort(403, 'Akses hanya untuk Pengawas.');
    }
}
