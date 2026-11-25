<?php

namespace App\Http\Middleware;

use Models\Organization; // atau Tenant
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());

        // Cari data sekolah berdasarkan custom domain atau subdomain (cache 10 menit)
        $tenant = Cache::remember("tenant_domain:{$host}", now()->addMinutes(10), function () use ($host) {
            return Organization::query()
                ->where('custom_domain', $host)
                ->orWhere('subdomain', $host)
                ->where('is_active', true)
                ->first();
        });

        // Jika domain tidak terdaftar di sistem, tampilkan 404
        if (! $tenant) {
            abort(404, 'Portal sekolah tidak ditemukan atau belum aktif.');
        }

        // 1. Daftarkan instance tenant aktif ke Service Container Laravel
        app()->instance('currentTenant', $tenant);

        // 2. Bagikan variabel $tenant ke seluruh file Blade secara otomatis
        View::share('tenant', $tenant);

        return $next($request);
    }
}