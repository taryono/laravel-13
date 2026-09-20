<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Set organisasi aktif SEBELUM regenerate sesi
        $this->authenticated($request, Auth::user());

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Logika penetapan organisasi aktif saat login
     */
    protected function authenticated(Request $request, $user): void
    {
        // Ambil organisasi yang aktif
        $organizations = $user->organizations()
            ->wherePivot('is_active', 1)
            ->get();

        if ($organizations->isEmpty()) {
            // Tidak ada organisasi aktif — hapus sesi & tolak
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Pakai session()->flash karena baru di-invalidate
            session()->flash('error', 'Akun Anda belum terdaftar di organisasi mana pun. Hubungi administrator.');
            return;
        }

        // Urutan prioritas: default → yang pertama
        $activeOrganization = $organizations->firstWhere('pivot.is_default', true)
            ?? $organizations->first();

        // Simpan ke sesi
        session()->put('active_organization_id', $activeOrganization->id);
        session()->put('active_organization', $activeOrganization);
        $user->current_organization_id = $activeOrganization->id;
        $user->save();
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Hapus data sesi khusus
        session()->forget(['role_ids', 'user_auth', 'active_organization_id']);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
