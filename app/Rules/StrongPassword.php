<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Minimal 8 karakter
        if (strlen($value) < 8) {
            $fail('Password minimal harus 8 karakter.');
            return;
        }

        // Harus mengandung minimal 1 huruf kecil
        if (!preg_match('/[a-z]/', $value)) {
            $fail('Password harus mengandung minimal 1 huruf kecil.');
            return;
        }

        // Harus mengandung minimal 1 huruf besar
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('Password harus mengandung minimal 1 huruf besar.');
            return;
        }

        // Harus mengandung minimal 1 angka
        if (!preg_match('/\d/', $value)) {
            $fail('Password harus mengandung minimal 1 angka.');
            return;
        }

        // Harus mengandung minimal 1 karakter khusus
        if (!preg_match('/[@$!%*?&]/', $value)) {
            $fail('Password harus mengandung minimal 1 karakter khusus (@$!%*?&).');
            return;
        }
    }
}