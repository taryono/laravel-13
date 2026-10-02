<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Wali\RaportCetakController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
 
use App\Modules\ReportBuilder\Controllers\ReportBuilderController;
use App\Modules\ReportBuilderComponent\Controllers\ReportBuilderComponentController;

Route::middleware(['auth'])->group(function () {
    // Admin
    Route::prefix('report-builder')->name('report-builder.')->group(function () {
        Route::get('/', [ReportBuilderController::class, 'index'])->name('index');
        Route::get('/builder/{template?}', [ReportBuilderController::class, 'builder'])->name('builder');
        Route::post('/store', [ReportBuilderController::class, 'store'])->name('store');
        Route::post('/preview', [ReportBuilderController::class, 'preview'])->name('preview');
        Route::post('/upload-asset', [ReportBuilderController::class, 'uploadAsset'])->name('upload-asset');
        Route::post('/duplicate/{template}', [ReportBuilderController::class, 'duplicate'])->name('duplicate');
        Route::delete('/{template}', [ReportBuilderController::class, 'destroy'])->name('destroy');
        Route::post('/{template}/toggle', [ReportBuilderController::class, 'toggleStatus'])->name('toggle');
        Route::post('/{template}/set-default', [ReportBuilderController::class, 'setDefault'])->name('set-default');
        Route::get('/{template}/preview-render', [ReportBuilderController::class, 'previewTemplate'])->name('preview-render');
        Route::get('/{template}/preview-pdf', [ReportBuilderController::class, 'previewPdf'])->name('preview-pdf');

        // Palette Components Management
        Route::prefix('components')->name('components.')->group(function () {
            Route::get('/', [ReportBuilderComponentController::class, 'index'])->name('index');
            Route::get('/create', [ReportBuilderComponentController::class, 'create'])->name('create');
            Route::post('/', [ReportBuilderComponentController::class, 'store'])->name('store');
            Route::get('/{component}/edit', [ReportBuilderComponentController::class, 'edit'])->name('edit');
            Route::put('/{component}', [ReportBuilderComponentController::class, 'update'])->name('update');
            Route::delete('/{component}', [ReportBuilderComponentController::class, 'destroy'])->name('destroy');
            Route::post('/{component}/toggle', [ReportBuilderComponentController::class, 'toggleStatus'])->name('toggle');
        });
    });

    // Parent Portal
    // Route::get('/parent/report/{student}/print', [ReportPrintController::class, 'print'])
    //     ->name('parent.report.print');
});