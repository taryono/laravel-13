<?php

use Illuminate\Support\Facades\Route;
// ==================================================
// PORTAL PENDAFTAR — prefix /ppdb
// ==================================================
use App\Http\Controllers\Ppdb\Applicant\RegistrationController;
use App\Http\Controllers\Ppdb\Applicant\ApplicantDashboardController;
use App\Http\Controllers\Auth\ApplicantLoginController;

Route::prefix('ppdb')->name('ppdb.')->group(function () {

    // ---------- PUBLIC (tanpa login) ----------
    Route::get('/', [RegistrationController::class, 'index'])->name('index');
    Route::get('/daftar/{admission:code}', [RegistrationController::class, 'create'])->name('register');
    Route::post('/daftar/{admission:code}', [RegistrationController::class, 'store'])->name('register.store');
    Route::get('/cek-status', [RegistrationController::class, 'statusForm'])->name('status');
    Route::post('/cek-status', [RegistrationController::class, 'checkStatus'])->name('status.check');
    // ---------- LOGIN PENDAFTAR ----------
    Route::middleware('guest:applicant')->group(function () {
        Route::get('/login', [ApplicantLoginController::class, 'showLogin'])->name('login');
        Route::post('/login', [ApplicantLoginController::class, 'login'])->name('login.post');
    });
    Route::post('/logout', [ApplicantLoginController::class, 'logout'])->name('logout');
    // ---------- AUTH: PENDAFTAR ----------
    Route::middleware('applicant.auth')->group(function () {
        Route::get('/dashboard', [ApplicantDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profil', [ApplicantDashboardController::class, 'profile'])->name('profile');
        Route::get('/dokumen', [ApplicantDashboardController::class, 'documents'])->name('documents');
        Route::get('/pembayaran', [ApplicantDashboardController::class, 'payments'])->name('payments');
        Route::get('/pengumuman', [ApplicantDashboardController::class, 'announcements'])->name('announcements');
    });
});
