<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; // Tambahkan ini

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

         // ==== TARUH KODE KAMU DISINI ====
        $user = auth()->user();
        $role_ids = $user->roles->pluck('id')->toArray();
        $isSuper = $user->roles->where('name', 'superuser')->isNotEmpty();
        $login_as = $isSuper ? 'superuser' : 'user';

        // Log untuk cek
        Log::info('[LOGIN BERHASIL] User: ' . $user->email);
        Log::info('[LOGIN BERHASIL] Role IDs: ', $role_ids);
        Log::info('[LOGIN BERHASIL] Superuser: ' . ($isSuper ? 'YA' : 'TIDAK'));

        // Simpan ke session
        session()->put('role_ids', $role_ids);
        session()->put('user_auth.login_as', $login_as);
        // =================================

        
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        session()->forget(['role_ids', 'user_auth']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
