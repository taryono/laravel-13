<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        // Validasi locale
        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'en';
        }
        
        session(['locale' => $locale]);
        
        return redirect()->back();
    }
}