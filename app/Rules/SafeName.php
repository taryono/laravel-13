<?php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafeName implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        // Cek karakter berbahaya
        $dangerousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
            '/javascript:/i',
            '/on\w+\s*=/i',     // onclick, onload, dll
            '/<\s*\/?\s*\w+/i', // HTML tags
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $fail('Nama mengandung karakter yang tidak diperbolehkan.');
                return;
            }
        }

        // Validasi hanya huruf, spasi, dan beberapa tanda baca
        if (! preg_match('/^[a-zA-Z\s\.\-\']+$/', $value)) {
            $fail('Nama hanya boleh mengandung huruf, spasi, titik, tanda hubung, dan apostrof.');
        }
    }
}
