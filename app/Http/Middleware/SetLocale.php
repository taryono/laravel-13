<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Cek dari session, cookie, atau URL parameter
        $locale = session('locale', 'en');
        
        // Validasi locale yang didukung
        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'en';
        }
        
        app()->setLocale($locale);
        
        return $next($request);
    }
}