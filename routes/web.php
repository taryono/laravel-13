<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;

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

use App\Http\Controllers\Dashboard\AdminDashboardController;

Route::prefix('dashboard/admin')->name('dashboard.admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
    Route::post('/verify/{applicantId}', [AdminDashboardController::class, 'updateVerification'])->name('verify');
    Route::post('/run-selection', [AdminDashboardController::class, 'runSelection'])->name('run-selection');
    Route::get('/export', [AdminDashboardController::class, 'export'])->name('export');
});

Route::post('/organization/switch', [ProfileController::class, 'switch'])
    ->name('organization.switch');

require __DIR__ . '/auth.php';
