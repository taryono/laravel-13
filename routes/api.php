<?php 
// routes/api.php
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api'])->group(function () {
    Route::post('/auth/login', [ApiAuthController::class, 'login'])
        ->withoutMiddleware('throttle:api')
        ->middleware('throttle:login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index']);
        
        // Endpoint berat diberi limit lebih ketat
        Route::post('/reports/export', [ReportController::class, 'export'])
            ->middleware('throttle:heavy-operations');
    });
});