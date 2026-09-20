<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function switch(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
        ]);

        $user = auth()->user();
        $orgId = $request->organization_id;

        // Pastikan pengguna memang punya akses & aktif ke organisasi ini
        $hasAccess = $user->organizations()
            ->where('organizations.id', $orgId)
            ->wherePivot('is_active', 1)
            ->exists();

        abort_if(!$hasAccess, 403, 'Anda tidak memiliki akses ke organisasi tersebut.');

        // Simpan ke sesi
        session(['active_organization_id' => $orgId]);

        return redirect()->back()
            ->with('success', 'Beralih ke organisasi berhasil.');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Proses penyimpanan data...
        $user = auth()->user();
        $user->fill($request->validated());
        if ($request->password) $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
