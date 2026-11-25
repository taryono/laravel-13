<?php

use App\Http\Controllers\WelcomeController; 
use Illuminate\Support\Facades\Route;

// Rute khusus domain utama SaaS jika diperlukan di luar routes/web.php
Route::domain(config('app.central_domain'))
    ->middleware('web')
    ->group(function () {
        Route::get('/', [WelcomeController::class, 'index'])->name('central.home');
    });
