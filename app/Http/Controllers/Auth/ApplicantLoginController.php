<?php
// Modules/Ppdb/Http/Controllers/Portal/ApplicantLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantLoginController extends Controller
{
    public function showLogin()
    {
        return view('ppdb::portal.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'registration_number' => 'required|string',
            'password'            => 'required|string',
        ]);

        if (Auth::guard('applicant')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('applicant')->user();
            $request->session()->regenerate();
            return redirect()->intended(
                \App\Helpers\AuthRedirectHelper::redirectAfterLogin($user)
            );
        }

        return back()->withErrors([
            'registration_number' => 'Nomor registrasi atau password salah.',
        ])->onlyInput('registration_number');
    }

    public function logout(Request $request)
    {
        Auth::guard('applicant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('ppdb.index');
    }
}
