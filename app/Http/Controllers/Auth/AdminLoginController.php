<?php
// Modules/Ppdb/Http/Controllers/Admin/AdminLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLogin()
    {
        return view('ppdb::admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();

            // Cek role
            if (!$user->hasAnyRole(['admin_ppdb', 'super_admin'])) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Anda tidak memiliki akses ke panel admin PPDB.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended(
                \App\Helpers\AuthRedirectHelper::redirectAfterLogin($user)
            );
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('ppdb-admin.login');
    }
}
