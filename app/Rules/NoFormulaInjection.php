<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoFormulaInjection implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value)
    {
        if (empty($value)) {
            return true;
        }

        $dangerousChars = ['=', '+', '-', '@'];
        
        // Check jika dimulai dengan karakter berbahaya
        return !in_array(substr(trim($value), 0, 1), $dangerousChars);
    }

    /**
     * Get the validation error message.
     */
    public function message()
    {
        return 'Field :attribute tidak boleh dimulai dengan karakter =, +, -, atau @';
    }
}

// Penggunaan di FormRequest:
/*
use App\Rules\NoFormulaInjection;

public function rules()
{
    return [
        'title' => ['required', 'string', new NoFormulaInjection],
        'name' => ['required', 'string', new NoFormulaInjection],
        // ...
    ];
}
*/