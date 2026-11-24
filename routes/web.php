<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Modules\Dashboard\Controllers\DashboardController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\NewsCommentController;


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
Route::get('/login-option', function () {
    return view('login_option');
})->name('login_option')->middleware(['guest']);

Route::post('/organization/switch', [ProfileController::class, 'switch'])
    ->name('organization.switch');
require __DIR__ . '/admin_ppdb.php';
require __DIR__ . '/applicant.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/canteen.php';

Route::get('/blog', function () {
    return view('details.blog');
})->name('blog')->middleware(['guest']);
Route::get('/changelog', function () {
    return view('details.changelog');
})->name('changelog')->middleware(['guest']);
Route::get('/dokumentasi', function () {
    return view('details.dokumentasi');
})->name('dokumentasi')->middleware(['guest']);
Route::get('/faq', function () {
    return view('details.faq');
})->name('faq')->middleware(['guest']);
Route::get('/kontak', function () {
    return view('details.kontak');
})->name('kontak')->middleware(['guest']);
Route::get('/security', function () {
    return view('details.security');
})->name('security')->middleware(['guest']);

Route::get('/absensi', function () {
    return view('details.absensi');
})->name('absensi')->middleware(['guest']);

Route::get('/akademik', function () {
    return view('details.akademik');
})->name('akademik')->middleware(['guest']);

Route::get('/analitik', function () {
    return view('details.analitik');
})->name('analitik')->middleware(['guest']);

Route::get('/kesiswaan', function () {
    return view('details.kesiswaan');
})->name('kesiswaan')->middleware(['guest']);

Route::get('/keuangan', function () {
    return view('details.keuangan');
})->name('keuangan')->middleware(['guest']);

Route::get('/parent-portal', function () {
    return view('details.parent-portal');
})->name('parent-portal')->middleware(['guest']);


Route::post('/berita/{slug}/komentar', [NewsCommentController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('public.news.comments.store');

Route::prefix('berita')->name('public.news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('show');
});