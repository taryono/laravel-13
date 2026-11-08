<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Modules\Dashboard\Controllers\DashboardController;

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
Route::post('/submit_request_demo', [WelcomeController::class, 'submitRequestDemo'])->name('submit_request_demo.form');
Route::get('/submit_request_demo', fn() => redirect()->route('request_demo.form'));
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);

Route::post('/organization/switch', [ProfileController::class, 'switch'])
    ->name('organization.switch');
require __DIR__ . '/admin_ppdb.php';
require __DIR__ . '/applicant.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/canteen.php';
