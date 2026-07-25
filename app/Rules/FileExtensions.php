<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileExtensions implements Rule
{
    protected $extensions;

    public function __construct($extensions)
    {
        $this->extensions = is_array($extensions) ? $extensions : explode(',', $extensions);
    }

    public function passes($attribute, $value)
    {
        $extension = strtolower($value->getClientOriginalExtension());
        return in_array($extension, $this->extensions);
    }

    public function message()
    {
        return 'Format file tidak valid. Hanya boleh: ' . implode(', ', $this->extensions);
    }
}