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
        return view('ppdb.admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();

            // Cek role panitia PPDB atau super admin
            if (!$user->isSuperUser() && !$user->hasAnyRole(['admin-ppdb', 'super-admin', 'admin_ppdb', 'super_admin', 'Panitia PPDB', 'Super Administrator'])) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Anda tidak memiliki akses ke panel admin PPDB.',
                ]);
            }

            $request->session()->regenerate();
            $targetUrl = route('ppdb-admin.dashboard');
            return redirect()->intended($targetUrl);
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
