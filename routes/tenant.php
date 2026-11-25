<?php

use App\Http\Controllers\Public\OrganizationPortalController;
use App\Http\Controllers\Public\PpdbController;
use App\Http\Middleware\IdentifyTenant;
use Illuminate\Support\Facades\Route;

// Menangkap semua domain selain domain utama (central domains) dan menjalankan IdentifyTenant
$centralDomains = config('app.central_domains', [
    config('app.central_domain', '127.0.0.1'),
    '127.0.0.1',
    'localhost',
]);

$tenantPattern = '^(?!' . implode('|', array_map('preg_quote', array_unique(array_filter($centralDomains)))) . '$).*';

Route::domain('{tenant_domain}')
    ->where(['tenant_domain' => $tenantPattern])
    ->middleware(['web', IdentifyTenant::class])
    ->group(function () {
        Route::get('/', [OrganizationPortalController::class, 'index'])->name('tenant.home');
        Route::get('/profil', [OrganizationPortalController::class, 'profile'])->name('tenant.profile');
        Route::get('/berita', [OrganizationPortalController::class, 'news'])->name('tenant.news');
    });
