<?php
if (!function_exists('isSuperUser')) {
    function isSuperUser($user = null)
    {
        $resolvedUser = $user ?? (auth()->check() ? auth()->user() : null);
        if ($resolvedUser && method_exists($resolvedUser, 'isSuperUser') && $resolvedUser->isSuperUser()) {
            return true;
        }
        if ($user !== null && auth()->check() && method_exists($user, 'getAuthIdentifier') && $user->getAuthIdentifier() !== auth()->id()) {
            return false;
        }
        return request()->hasSession() && session()->get('user_auth.login_as') === "super-admin";
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
            ->map(fn($value, $key) => e($key) . '="' . e($value) . '"')
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
            ->map(fn($value, $key) => e($key) . '="' . e($value) . '"')
            ->implode(' ');

        if ($url) {
            return '<a href="' . e($url) . '" class="' . e($class) . '" ' . $attrString . '>' . e($label) . '</a>';
        }

        return '<button type="button" class="' . e($class) . '" data-bs-dismiss="modal" ' . $attrString . '>' . e($label) . '</button>';
    }
}

if (!function_exists('generateExpenseNumber')) {
    function generateExpenseNumber()
    {
        $year = date('Y');
        $month = date('m');

        $lastExpense = \Models\Expense::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastExpense) {
            $lastNumber = intval(substr($lastExpense->expense_number, -4));
            $sequence = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = '0001';
        }

        return "EXP-{$year}{$month}-{$sequence}";
    }
}

if (!function_exists('active_organization')) {
    function active_organization()
    {
        return auth()->user()?->activeOrganization();
    }
}

if (!function_exists('active_organization_id')) {
    function active_organization_id()
    {
        return session('active_organization_id');
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date)
    {
        return date('Y-m-d', strtotime($date));
    }
}

if (!function_exists('greetings')) {
    function greetings()
    {
        $now  = \Carbon\Carbon::now('Asia/Jakarta')->locale('id');
        $hour = $now->hour;

        $greeting = match (true) {
            $hour < 11 => 'Selamat Pagi',
            $hour < 15 => 'Selamat Siang',
            $hour < 18 => 'Selamat Sore',
            default    => 'Selamat Malam',
        };
        return $greeting;
    }
}