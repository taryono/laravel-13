<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Ppdb\Admin\AdminDashboardController;
use App\Modules\Applicant\Controllers\ApplicantController;
use App\Modules\Admission\Controllers\AdmissionController;

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
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('applicants', ApplicantController::class);
        Route::resource('admissions', AdmissionController::class);

        Route::get('/verifikasi', [AdminDashboardController::class, 'index'])->name('verification');
        Route::post('/verifikasi/{document}', [AdminDashboardController::class, 'verify'])->name('verification.verify');
        Route::post('/verifikasi/{document}/reject', [AdminDashboardController::class, 'reject'])->name('verification.reject');

        Route::match(['get', 'post'], '/seleksi', [AdminDashboardController::class, 'selection'])->name('selection');
        Route::get('/laporan', [AdminDashboardController::class, 'reports'])->name('reports');
    });
});

// ==================================================
// DASHBOARD ADMIN PPDB (Portal Operasional Admin)
// ==================================================
Route::prefix('dashboard/admin')->name('dashboard.admin.')->middleware('ppdb-admin.auth')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
    Route::post('/verify/{applicantId}', [AdminDashboardController::class, 'updateVerification'])->name('verify');
    Route::post('/run-selection', [AdminDashboardController::class, 'runSelection'])->name('run-selection');
    Route::get('/export', [AdminDashboardController::class, 'export'])->name('export');
});
