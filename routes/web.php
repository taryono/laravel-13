<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Ppdb\Applicant\RegistrationController;
use App\Http\Controllers\Ppdb\Applicant\DocumentController;
use App\Http\Controllers\Ppdb\Applicant\PaymentController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// routes/web.php
Route::prefix('trial-signup')->group(function () {
    Route::get('/', [WelcomeController::class, 'trialSignup'])->name('trial.form');
    Route::post('/step/{step}', [WelcomeController::class, 'saveStep'])->name('trial.step');
    Route::post('/complete', [WelcomeController::class, 'completeSignup'])->name('trial.complete');
});
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::get('/request_demo', [WelcomeController::class, 'requestDemo'])->name('request_demo.form');
Route::get('/submit_request_demo', [WelcomeController::class, 'submitRequestDemo'])->name('submit_request_demo.form');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
});
// ---------- PUBLIC (tanpa login) ---------- 
Route::prefix('ppdb')->name('ppdb.')->middleware(['web'])->group(function () {
    // Form utama
    Route::get('/', [RegistrationController::class, 'index'])->name('index');
    Route::get('/daftar/{admission:code}', [RegistrationController::class, 'create'])->name('register');
    Route::post('/daftar/{admission:code}', [RegistrationController::class, 'store'])->name('register.store');
    Route::put('/daftar/{applicant:registration_number}', [RegistrationController::class, 'update'])->name('register.update');
    Route::get('/cek-status', [RegistrationController::class, 'statusForm'])->name('status');
    Route::post('/cek-status', [RegistrationController::class, 'checkStatus'])->name('status.check');
    // Draft
    Route::post('/draft/{applicant?}', [RegistrationController::class, 'saveDraft'])->name('draft');
    // Dokumen
    Route::post('/dokumen/{applicant}', [DocumentController::class, 'upload'])->name('document.upload');
    Route::delete('/dokumen/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');
    Route::get('/dokumen/{document}/preview', [DocumentController::class, 'preview'])->name('document.preview');
    // Submit final
    Route::post('/submit/{applicant}', [RegistrationController::class, 'submit'])->name('submit');
    // Cek status
    Route::get('/status', [RegistrationController::class, 'status'])->name('status');
    // Pembayaran
    Route::get('/invoice/{applicant}', [PaymentController::class, 'invoice'])->name('invoice');
    Route::post('/payment/{invoice}', [PaymentController::class, 'store'])->name('payment.store');
    // API untuk AJAX
    Route::get('/api/sekolah/{admission}', [RegistrationController::class, 'getSchools'])->name('api.schools');
    Route::get('/api/jalur/{admission}', [RegistrationController::class, 'getPaths'])->name('api.paths');
    Route::get('/api/prodi/{school}', [RegistrationController::class, 'getPrograms'])->name('api.programs');
    Route::post('/api/geocode', [RegistrationController::class, 'geocode'])->name('api.geocode');
});

Route::post('/organization/switch', [ProfileController::class, 'switch'])
    ->name('organization.switch');
require __DIR__ . '/admin_ppdb.php';
require __DIR__ . '/applicant.php';
require __DIR__ . '/auth.php';
