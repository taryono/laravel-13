<?php


if (!function_exists('isSuperUser')) {
    function isSuperUser()
    {
        return session()->get('user_auth.login_as') == "superuser";
    
    }
}

if (!function_exists('say')) {
    function say($text)
    {
        return $text;    
    }
}

if (!function_exists('getkDarkMode')) {
    function getkDarkMode()
    {
        return  false;    
    }
}

if (!function_exists('renderFields')) {
    function renderFields()
    {
        return  [];    
    }
}

if (!function_exists('submit')) {
    function submit(array $attributes = [], string $label = 'Submit')
    {
        // Class default, bisa di-override/ditambah lewat $attributes['class']
        $defaultClass = 'btn btn-primary';
        $class = $attributes['class'] ?? $defaultClass;
        unset($attributes['class']);

        $attrString = collect($attributes)
            ->map(fn ($value, $key) => e($key) . '="' . e($value) . '"')
            ->implode(' ');

        return '<button type="submit" class="' . e($class) . '" ' . $attrString . '>' . e($label) . '</button>';
    }
}


if (!function_exists('cancel')) {
    /**
     * Render tombol/link "Batal".
     * - Kalau ada key 'url', render sebagai <a href="..."> (navigasi halaman).
     * - Kalau tidak ada 'url', render sebagai <button data-bs-dismiss="modal"> (tutup modal).
     */
    function cancel(array $attributes = [], string $label = 'Batal')
    {
        $defaultClass = 'btn btn-secondary';
        $class = $attributes['class'] ?? $defaultClass;
        unset($attributes['class']);

        $url = $attributes['url'] ?? '#';
        unset($attributes['url']);

        $attrString = collect($attributes)
            ->map(fn ($value, $key) => e($key) . '="' . e($value) . '"')
            ->implode(' ');

        if ($url) {
            return '<a href="' . e($url) . '" class="' . e($class) . '" ' . $attrString . '>' . e($label) . '</a>';
        }

        return '<button type="button" class="' . e($class) . '" data-bs-dismiss="modal" ' . $attrString . '>' . e($label) . '</button>';
    }
}