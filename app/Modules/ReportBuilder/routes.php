<?php

use Illuminate\Support\Facades\Route;
use App\Modules\ReportBuilder\Controllers\ReportBuilderController;
use App\Modules\ReportBuilderComponent\Controllers\ReportBuilderComponentController;
use App\Modules\ReportBuilder\Controllers\ReportDataSourceController;

Route::middleware(['web', 'auth'])->group(function () {
    $routesDefinition = function () {
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
            Route::match(['POST', 'PATCH'], '/{component}/toggle', [ReportBuilderComponentController::class, 'toggleStatus'])->name('toggle');
        });

        // Data Sources Management
        Route::prefix('data-sources')->name('data-sources.')->group(function () {
            Route::get('/', [ReportDataSourceController::class, 'index'])->name('index');
            Route::get('/create', [ReportDataSourceController::class, 'create'])->name('create');
            Route::post('/', [ReportDataSourceController::class, 'store'])->name('store');
            Route::get('/api/list', [ReportDataSourceController::class, 'apiList'])->name('api-list');
            Route::get('/{dataSource}/edit', [ReportDataSourceController::class, 'edit'])->name('edit');
            Route::put('/{dataSource}', [ReportDataSourceController::class, 'update'])->name('update');
            Route::delete('/{dataSource}', [ReportDataSourceController::class, 'destroy'])->name('destroy');
            Route::match(['POST', 'PATCH'], '/{dataSource}/toggle', [ReportDataSourceController::class, 'toggleStatus'])->name('toggle');
        });
    };

    // Primary route group: name('admin.report-builder.') with prefix('admin/report-builder')
    Route::prefix('admin/report-builder')->name('admin.report-builder.')->group($routesDefinition);

    // Alias route group: name('report-builder.') with prefix('report-builder')
    Route::prefix('report-builder')->name('report-builder.')->group($routesDefinition);
});
