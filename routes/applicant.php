<?php

use Illuminate\Support\Facades\Route;
// ==================================================
// PORTAL PENDAFTAR — prefix /ppdb
// ==================================================
use App\Http\Controllers\Ppdb\Applicant\RegistrationController;
use App\Http\Controllers\Ppdb\Applicant\ApplicantDashboardController;
use App\Http\Controllers\Ppdb\Applicant\DocumentController;
use App\Http\Controllers\Ppdb\Applicant\PaymentController;
use App\Http\Controllers\Auth\ApplicantLoginController;
// ---------- PUBLIC (tanpa login) ---------- 
Route::prefix('ppdb')->name('ppdb.')->middleware(['web'])->group(function () {
    // Form utama
    Route::get('/', [RegistrationController::class, 'index'])->name('index');
    Route::get('/daftar/{admission:code}', [RegistrationController::class, 'create'])->name('register');
    Route::post('/daftar/{admission:code}', [RegistrationController::class, 'store'])->name('register.store');
    Route::put('/daftar/{applicant:registration_number}', [RegistrationController::class, 'update'])->name('register.update');
    Route::get('/daftar-edit/{applicant:registration_number}', [RegistrationController::class, 'edit'])->name('register.edit');
    Route::get('/cek-status', [RegistrationController::class, 'statusForm'])->name('status');
    Route::post('/cek-status', [RegistrationController::class, 'checkStatus'])->name('checkStatus');
    // Draft
    Route::post('/draft/{applicant?}', [RegistrationController::class, 'saveDraft'])->name('draft');
    // Dokumen
    Route::post('/dokumen/{applicant}', [DocumentController::class, 'upload'])->name('document.upload');
    Route::delete('/dokumen/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');
    Route::get('/dokumen/{document}/preview', [DocumentController::class, 'preview'])->name('document.preview');
    // Submit final
    Route::post('/submit/{applicant}', [RegistrationController::class, 'submit'])->name('submit');
    // Cek status
    // Route::get('/status', [RegistrationController::class, 'status'])->name('status');
    // Pembayaran
    Route::get('/invoice/{applicant}', [PaymentController::class, 'invoice'])->name('invoice');
    Route::post('/payment/{invoice}', [PaymentController::class, 'store'])->name('payment.store');
    // API untuk AJAX
    Route::get('/api/sekolah/{admission}', [RegistrationController::class, 'getSchools'])->name('api.schools');
    Route::get('/api/jalur/{admission}', [RegistrationController::class, 'getPaths'])->name('api.paths');
    Route::get('/api/prodi/{school}', [RegistrationController::class, 'getPrograms'])->name('api.programs');
    Route::post('/api/geocode', [RegistrationController::class, 'geocode'])->name('api.geocode');
});
Route::prefix('ppdb')->name('ppdb.')->middleware(['web'])->group(function () {
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
