<?php

namespace App\Core\Menu;

use App\Models\User;
use Models\TrialRegistration;
use Models\Menu;
use Illuminate\Support\Facades\DB;

class TrialMenuResolver
{
    /**
     * Pemetaan kode modul trial ke nama-nama root menu
     */
    public const MODULE_TO_ROOT_MENUS = [
        'akademik'          => ['academic', 'assessment', 'reference'],
        'kesiswaan'         => ['students', 'admission', 'alumni'],
        'keuangan'          => ['finance', 'accounting', 'bos'],
        'absensi'           => ['attendance'],
        'cbt'               => ['cbt'],
        'perpustakaan'      => ['library'],
        'pesantren'         => ['dormitory'],
        'kepegawaian'       => ['employment', 'payroll'],
        'sarpras'           => ['inventory', 'transport', 'canteen'],
        'layanan_kesehatan' => ['clinic'],
    ];

    /**
     * Pemetaan kode modul trial ke kode menu_categories (untuk card di dashboard)
     */
    public const MODULE_TO_CATEGORIES = [
        'akademik'          => ['data-akademik', 'assessment'],
        'kesiswaan'         => ['ppdb', 'student', 'alumi'],
        'keuangan'          => ['keuangan', 'akuntansi', 'budgeting'],
        'absensi'           => ['absensi'],
        'cbt'               => ['cbt'],
        'perpustakaan'      => ['library'],
        'pesantren'         => ['dormitory', 'pesantren'],
        'kepegawaian'       => ['karyawan', 'payroll'],
        'sarpras'           => ['fasilitas', 'antar-jemput', 'canteen'],
        'layanan_kesehatan' => ['fasilitas', 'clinic'],
    ];

    /**
     * Periksa apakah pengguna merupakan trial user.
     */
    public function isTrialUser(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if (method_exists($user, 'isTrialUser') && $user->isTrialUser()) {
            return true;
        }

        if (!empty($user->is_trial_user)) {
            return true;
        }

        return TrialRegistration::withoutGlobalScopes()->where('user_id', $user->id)->exists();
    }

    /**
     * Dapatkan daftar ID modul yang diizinkan untuk pengguna trial sesuai perannya.
     */
    public function getAllowedModuleIds(User $user, ?int $activeRoleId = null): array
    {
        $roleIds = $activeRoleId
            ? [$activeRoleId]
            : (session('user_auth.active_role_id')
                ? [session('user_auth.active_role_id')]
                : (session('active_role_id')
                    ? [session('active_role_id')]
                    : DB::table('role_user')
                        ->where('user_id', $user->id)
                        ->whereNull('deleted_at')
                        ->pluck('role_id')
                        ->toArray()));

        $registration = TrialRegistration::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$registration) {
            return [];
        }

        $allowedModuleIds = [];

        // 1. Cek dari school_role_modules jika sudah complete
        if ($registration->school_id) {
            $query = DB::table('school_role_modules')
                ->where('school_id', $registration->school_id);

            if (!empty($roleIds)) {
                $query->whereIn('role_id', $roleIds);
            }

            $allowedModuleIds = $query->pluck('module_id')->unique()->toArray();
        }

        // 2. Fallback ke trial_registration_role_modules
        if (empty($allowedModuleIds)) {
            $query = DB::table('trial_registration_role_modules')
                ->where('trial_registration_id', $registration->id);

            if (!empty($roleIds)) {
                $query->whereIn('role_id', $roleIds);
            }

            $allowedModuleIds = $query->pluck('module_id')->unique()->toArray();
        }

        // 3. Fallback ke semua modul yang dipilih registrasi trial jika tidak spesifik per-role
        if (empty($allowedModuleIds)) {
            $allowedModuleIds = DB::table('trial_registration_modules')
                ->where('trial_registration_id', $registration->id)
                ->pluck('module_id')
                ->toArray();
        }

        return array_values(array_unique($allowedModuleIds));
    }

    /**
     * Dapatkan daftar kode modul (string) yang diizinkan.
     */
    public function getAllowedModuleCodes(User $user, ?int $activeRoleId = null): array
    {
        $allowedModuleIds = $this->getAllowedModuleIds($user, $activeRoleId);
        if (empty($allowedModuleIds)) {
            return [];
        }

        return DB::table('modules')
            ->whereIn('id', $allowedModuleIds)
            ->pluck('code')
            ->map(fn($c) => strtolower(trim((string) $c)))
            ->toArray();
    }

    /**
     * Dapatkan daftar ID menu (termasuk root, child, sub-child, dan dashboard)
     * yang berhak dilihat oleh user trial ini.
     */
    public function getAllowedMenuIds(User $user, ?int $activeRoleId = null): array
    {
        $moduleCodes = $this->getAllowedModuleCodes($user, $activeRoleId);

        // Dashboard selalu diizinkan untuk semua pengguna
        $allowedRootNames = ['dashboard'];

        foreach ($moduleCodes as $code) {
            if (isset(self::MODULE_TO_ROOT_MENUS[$code])) {
                $allowedRootNames = array_merge($allowedRootNames, self::MODULE_TO_ROOT_MENUS[$code]);
            }
        }

        $allowedRootNames = array_unique($allowedRootNames);

        // Ambil root menus yang diizinkan
        $rootMenus = Menu::whereIn('name', $allowedRootNames)
            ->whereNull('parent_id')
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->get();

        $allMenuIds = $rootMenus->pluck('id')->toArray();

        // Rekursif ambil seluruh anak (children/descendants) dari root menu tersebut
        $currentParents = $allMenuIds;
        while (!empty($currentParents)) {
            $children = Menu::whereIn('parent_id', $currentParents)
                ->where('is_active', 1)
                ->whereNull('deleted_at')
                ->pluck('id')
                ->toArray();

            if (empty($children)) {
                break;
            }

            $allMenuIds = array_merge($allMenuIds, $children);
            $currentParents = $children;
        }

        return array_values(array_unique($allMenuIds));
    }

    /**
     * Dapatkan daftar kode menu_categories yang diizinkan untuk card menu dashboard.
     */
    public function getAllowedCategoryCodes(User $user, ?int $activeRoleId = null): array
    {
        $moduleCodes = $this->getAllowedModuleCodes($user, $activeRoleId);
        $allowedCategories = [];

        foreach ($moduleCodes as $code) {
            if (isset(self::MODULE_TO_CATEGORIES[$code])) {
                $allowedCategories = array_merge($allowedCategories, self::MODULE_TO_CATEGORIES[$code]);
            }
        }

        return array_values(array_unique($allowedCategories));
    }
}
