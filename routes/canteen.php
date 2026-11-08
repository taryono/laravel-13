<?php

use App\Http\Controllers\Api\CanteenMenuController;
use App\Http\Controllers\Api\CanteenPurchaseController;
use Illuminate\Support\Facades\Route;

/*
 * Daftarkan alias middleware (Laravel 11, bootstrap/app.php):
 *   $middleware->alias(['tenant.role' => \App\Http\Middleware\EnsureTenantRole::class]);
 * Muat file ini dari routes/api.php dengan: require __DIR__.'/api_canteen.php';
 */
Route::middleware('auth')
    ->prefix('canteen/{tenant}')
    ->whereNumber('tenant')
    ->group(function () {

        // Pemilik dan kasir: dipakai layar kasir
        Route::middleware('tenant.role:owner,cashier')->group(function () {
            Route::get('menus/by-barcode/{code}', [CanteenMenuController::class, 'byBarcode']);
            Route::get('menus', [CanteenMenuController::class, 'index']);
            Route::get('menus/{menu}', [CanteenMenuController::class, 'show'])->whereNumber('menu');
            Route::post('purchase', [CanteenPurchaseController::class, 'store']);
        });

        // Hanya pemilik: kelola menu
        Route::middleware('tenant.role:owner')->group(function () {
            Route::post('menus', [CanteenMenuController::class, 'store']);
            Route::put('menus/{menu}', [CanteenMenuController::class, 'update'])->whereNumber('menu');
            Route::patch('menus/{menu}/availability', [CanteenMenuController::class, 'toggleAvailability'])->whereNumber('menu');
            Route::delete('menus/{menu}', [CanteenMenuController::class, 'destroy'])->whereNumber('menu');
        });
    });