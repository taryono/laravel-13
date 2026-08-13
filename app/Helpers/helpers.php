<?php

if (!function_exists('__dict')) {
    function __dict(string $key, ?string $locale = null, array $params = []): string
    {
        $locale = $locale ?? app()->getLocale();
        
        $dictionary = cache()->remember("dict.{$key}.{$locale}", 3600, function () use ($key, $locale) {
            $dict = \Models\Dictionary::where('key', $key)->first();
            
            if (!$dict) {
                return null;
            }
            
            return $locale === 'in'
                ? $dict->in 
                : $dict->en;
        });
        
        if (!$dictionary) {
            return $key;
        }
        
        foreach ($params as $param => $value) {
            $dictionary = str_replace("{{$param}}", $value, $dictionary);
        }
        
        return $dictionary;
    }
}
// Penggunaan di Blade:
// {{ __dict('home.welcome') }}
// {{ __dict('user.greeting', 'id', ['name' => 'Budi']) }}