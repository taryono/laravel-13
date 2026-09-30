<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Ppdb\Admin\AdminDashboardController;

// ==================================================
// ADMIN PPDB — prefix /admin-ppdb
// ==================================================
Route::prefix('admin-ppdb')->name('ppdb-admin.')->group(function () {
    // ---------- LOGIN ADMIN PPDB ----------
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
    });
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // ---------- AUTH: ADMIN PPDB ----------
    Route::middleware('ppdb-admin.auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
        Route::resource('applicants', AdminDashboardController::class);
        Route::resource('admissions', AdminDashboardController::class);

        Route::get('/verifikasi', [AdminDashboardController::class, 'index'])->name('verification');
        Route::post('/verifikasi/{document}', [AdminDashboardController::class, 'verify'])->name('verification.verify');
        Route::post('/verifikasi/{document}/reject', [AdminDashboardController::class, 'reject'])->name('verification.reject');

        Route::get('/seleksi', [AdminDashboardController::class, 'selection'])->name('selection');
        Route::get('/laporan', [AdminDashboardController::class, 'reports'])->name('reports');
    });
});
Route::prefix('dashboard/admin')->name('dashboard.admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
    Route::post('/verify/{applicantId}', [AdminDashboardController::class, 'updateVerification'])->name('verify');
    Route::post('/run-selection', [AdminDashboardController::class, 'runSelection'])->name('run-selection');
    Route::get('/export', [AdminDashboardController::class, 'export'])->name('export');
});
