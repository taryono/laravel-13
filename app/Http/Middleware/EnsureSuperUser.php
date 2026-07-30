<?php
// app/Http/Middleware/EnsureSuperUser.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSuperUser
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !isSuperUser()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        return $next($request);
    }
}