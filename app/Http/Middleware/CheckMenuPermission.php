<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMenuPermission
{
    public function handle(Request $request, Closure $next, $permission = 'is_view')
    {
        $routeName = $request->route()->getName();
        $menuSlug = str_replace('.', '-', $routeName);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->hasMenuPermission($menuSlug, $permission)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}

// // Hanya yang bisa lihat
// Route::get('/siswa', [SiswaController::class, 'index'])
//     ->name('siswa.index')
//     ->middleware('auth', 'menu.permission:is_view');

// // Harus bisa buat
// Route::post('/siswa', [SiswaController::class, 'store'])
//     ->middleware('auth', 'menu.permission:is_create');

// // Aksi khusus
// Route::post('/siswa/export', [SiswaController::class, 'export'])
//     ->name('siswa.export')
//     ->middleware('auth');

// if (auth()->user()->hasActionPermission('export-siswa')) {
//     // tampilkan tombol export
// }