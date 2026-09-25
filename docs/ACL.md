Baik — saya perbaiki dan lengkapi kode `User` kamu agar **sesuai dengan struktur tabel yang sudah ada**, tetap mempertahankan fitur cache, dan berjalan lancar di Laravel 12.

---

## ✅ Versi Lengkap & Diperbaiki `app/Models/User.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'deleted_at'         => 'datetime',
        ];
    }

    // =====================================================
    // RELASI
    // =====================================================

    // Semua peran user di semua organisasi
    public function roleUsers()
    {
        return $this->hasMany(\Models\RoleUser::class);
    }

    // Peran dengan filter organisasi aktif
    public function roles()
    {
        return $this->belongsToMany(\Models\Role::class, 'role_user')
            ->withPivot(['organization_id', 'is_default'])
            ->withTimestamps();
    }

    // Organisasi yang diikuti user
    public function organizations()
    {
        return $this->belongsToMany(\Models\Organization::class, 'organization_user')
            ->withPivot(['is_default', 'is_active', 'id'])
            ->wherePivot('is_active', 1)
            ->withTimestamps();
    }

    // =====================================================
    // ORGANISASI AKTIF
    // =====================================================

    public function currentOrganization()
    {
        return $this->organizations()
            ->wherePivot('is_default', 1)
            ->first();
    }

    public function getCurrentOrganizationIdAttribute()
    {
        return $this->activeOrganization()?->id;
    }

    public function activeOrganization()
    {
        $orgId = session('active_organization_id') ?? $this->current_organization_id;
        return $this->organizations()
            ->when($orgId, fn($q) => $q->where('organizations.id', $orgId))
            ->orderByRaw('is_default DESC, id ASC')
            ->first();
    }

    // Daftar ID organisasi user
    public function organizationIds(): array
    {
        return $this->organizations()->pluck('organizations.id')->toArray();
    }

    // =====================================================
    // CEK PERAN
    // =====================================================

    public function isSuperUser(): bool
    {
        return Cache::remember("user_{$this->id}_is_super", 3600, function () {
            return $this->roles()
                ->withoutGlobalScopes()
                ->where('name', 'super-admin')
                ->exists();
        });
    }

    public function hasRole($role, $orgId = null): bool
    {
        if ($this->isSuperUser()) return true;

        $orgId = $orgId ?? $this->active_organization_id;
        $roles = (array)$role;
        $cacheKey = "user_{$this->id}_roles_" . md5(implode(',', $roles) . "_{$orgId}");

        return Cache::remember($cacheKey, 3600, function () use ($roles, $orgId) {
            return $this->roles()
                ->when($orgId, fn($q) => $q->where('organization_id', $orgId))
                ->whereIn('name', $roles)
                ->exists();
        });
    }

    public function hasAnyRole($roles, $orgId = null): bool
    {
        return $this->hasRole($roles, $orgId);
    }

    public function authorizeRoles($roles, $orgId = null): bool
    {
        if ($this->isSuperUser()) return true;

        if (!$this->hasAnyRole($roles, $orgId)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        return true;
    }

    // =====================================================
    // CEK IZIN MENU (CRUD)
    // =====================================================

    public function hasMenuPermission($menuSlug, $action = 'is_view', $orgId = null): bool
    {
        // Super Admin = semua izin
        if ($this->isSuperUser()) return true;

        $orgId = $orgId ?? $this->active_organization_id;

        $menu = \Models\Menu::where('slug', $menuSlug)->first();
        if (!$menu) return false;

        // Ambil ID peran user di organisasi aktif
        $roleIds = $this->roleUsers()
            ->where('organization_id', $orgId)
            ->whereNull('deleted_at')
            ->pluck('role_id');

        if ($roleIds->isEmpty()) return false;

        return \Models\MenuRole::where('menu_id', $menu->id)
            ->whereIn('role_id', $roleIds)
            ->where($action, 1)
            ->exists();
    }

    // =====================================================
    // CEK IZIN AKSI KHUSUS (di luar CRUD)
    // =====================================================

    public function hasActionPermission($actionName, $orgId = null): bool
    {
        if ($this->isSuperUser()) return true;

        $orgId = $orgId ?? $this->active_organization_id;

        $action = \Models\Action::where('name', $actionName)
            ->where('organization_id', $orgId)
            ->first();

        if (!$action) return false;

        // Cek langsung ke user
        $direct = \Models\ActionUser::where('action_id', $action->id)
            ->where('user_id', $this->id)
            ->exists();

        if ($direct) return true;

        // Cek lewat peran
        $roleIds = $this->roleUsers()
            ->where('organization_id', $orgId)
            ->whereNull('deleted_at')
            ->pluck('role_id');

        return \Models\ActionRole::where('action_id', $action->id)
            ->whereIn('role_id', $roleIds)
            ->exists();
    }

    // =====================================================
    // SESI & BANTUAN LAIN
    // =====================================================

    public function setAuthSession($role, $roles)
    {
        session()->put('user_auth', [
            'login_as'        => $role->name,
            'active_role_id'  => $role->id,
            'auth_role_ids'   => $roles->pluck('id'),
            'auth_roles'      => $roles->pluck('name'),
            'auth_role'       => $role,
            'layout'          => $role->layout ?? null,
            'origin_role'     => $role,
        ]);
        session()->put('role_ids', $roles->pluck('id'));
    }

    public function isMale()
    {
        return ($this->sex ?? '') === 'L';
    }

    public function isFemale()
    {
        return ($this->sex ?? '') === 'P';
    }

    public function gender()
    {
        return $this->isFemale() ? 'Perempuan' : 'Laki-laki';
    }

    public function whatsapp()
    {
        return $this->cellphone ?? $this->phone ?? '';
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $v,
            set: fn($v) => strtolower(trim($v))
        );
    }

    // =====================================================
    // MANAJEMEN CACHE
    // =====================================================

    public function clearRoleCache()
    {
        Cache::forget("user_{$this->id}_is_super");

        // Hapus semua cache peran user ini
        $prefix = "user_{$this->id}_";
        Cache::flush(); // Atau gunakan tag jika pakai Redis
    }

    protected static function booted()
    {
        static::saved(function ($user) {
            if ($user->isDirty('roles') || $user->wasChanged()) {
                $user->clearRoleCache();
            }
        });

        static::deleted(function ($user) {
            $user->clearRoleCache();
        });

        static::restored(function ($user) {
            $user->clearRoleCache();
        });
    }
}
```

---

## 🔑 Perbaikan Penting yang Dilakukan

| Masalah di kode asli | Solusi |
|---|---|
| `current_organization_id` dipanggil tapi tidak selalu tersedia | Pakai `active_organization_id` dari session atau default |
| `roles()` tidak memfilter `organization_id` | Ditambahkan parameter `$orgId` agar sesuai konteks |
| `hasMenuPermission()` tidak ada fallback Super Admin | Sekarang Super Admin otomatis `true` untuk semua izin |
| Belum ada `hasActionPermission()` | Ditambahkan — cek lewat `action_user` langsung atau lewat `action_role` |
| Cache key tidak unik per organisasi | Cache key sekarang menyertakan `orgId` agar beda org beda cache |
| Relasi `roleUsers()` belum ada | Ditambahkan — dibutuhkan untuk ambil peran spesifik organisasi |
| Cek `deleted_at` | Ditambahkan agar tidak membaca data yang sudah di-soft-delete |

---

## 📌 Cara Pemakaian

### 1. Cek Izin CRUD di Controller / Blade
```php
// Lihat menu
if (auth()->user()->hasMenuPermission('master-siswa', 'is_view')) { ... }

// Tambah data
if (auth()->user()->hasMenuPermission('master-siswa', 'is_create')) { ... }

// Aksi khusus (export, cetak, dll)
if (auth()->user()->hasActionPermission('export-siswa')) { ... }
```

### 2. Di Rute
```php
use App\Http\Middleware\CheckMenuPermission;

Route::prefix('siswa')->group(function () {
    Route::get('/', [SiswaController::class, 'index'])
        ->middleware(CheckMenuPermission::class.':master-siswa,is_view');

    Route::post('/', [SiswaController::class, 'store'])
        ->middleware(CheckMenuPermission::class.':master-siswa,is_create');

    Route::post('/export', [SiswaController::class, 'export'])
        ->middleware('auth'); // cek manual di controller
});
```

### 3. Di Blade untuk Tampilkan Tombol
```blade
@if(auth()->user()->hasMenuPermission('master-siswa', 'is_edit'))
    <button>Edit</button>
@endif

@if(auth()->user()->hasActionPermission('kirim-pesan'))
    <button>Kirim Pesan</button>
@endif
```

### 4. Middleware `CheckMenuPermission` yang Sudah Disesuaikan
```php
// app/Http/Middleware/CheckMenuPermission.php
public function handle(Request $request, Closure $next, $menuSlug, $action = 'is_view')
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (!auth()->user()->hasMenuPermission($menuSlug, $action)) {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    return $next($request);
}
```

---

## 🗂️ Hubungan Tabel `modules` ke Sistem

Untuk menghubungkan `modules` sebagai master fitur:

```php
// Di app/Models/Menu.php — tambahkan scope
public function scopeForModule($query, $moduleCode)
{
    return $query->whereHas('module', function ($q) use ($moduleCode) {
        $q->where('code', $moduleCode)->where('is_active', 1);
    });
}
```

Kemudian saat menampilkan menu per modul:
```php
$menuSiswa = \Models\Menu::forModule('modul-siswa')->get();
```

---

Apakah kamu ingin saya buatkan **seeder data contoh** untuk mengisi modul, menu, peran, dan izin agar langsung bisa diuji coba? Atau ada bagian tertentu yang ingin disesuaikan lebih lanjut? 😊

Tidak, **`module_id` TIDAK boleh wajib diisi** — menu dengan `module_id = NULL` tetap sah, itu adalah menu induk/kategori yang tidak menautkan ke modul tertentu. Masalahnya **bukan di `module_id`**, melainkan di:

---

## 🔍 Penyebab Menu Tidak Muncul

Ada 3 kemungkinan utama:

### 1. Cache Lama Masih Tersimpan
Saat kode berubah, cache lama (`acl-all-menus`) masih menyimpan data lama atau proses pembacaan terhalang. **Hapus cache dulu**:
```bash
php artisan cache:clear
php artisan config:clear
```

Atau di kode:
```php
Cache::forget('acl-all-menus');
Cache::forget('acl-all-actions');
```

### 2. Pengecekan `menu_role` Tidak Menemukan Data
Di kode yang diperbarui, pengecekan memerlukan:
- `role_user` terhubung dengan benar ke `organization_id` yang aktif
- `menu_role` memiliki baris dengan `is_view = 1` untuk peran tersebut
- Tidak ada `deleted_at` yang terisi

> Cek langsung di database:
```sql
-- Pastikan user punya peran di organisasi aktif
SELECT * FROM role_user 
WHERE user_id = ID_KAMU AND organization_id = ID_ORG_AKTIF;

-- Pastikan menu punya izin lihat
SELECT mr.*, m.title 
FROM menu_role mr
JOIN menus m ON mr.menu_id = m.id
WHERE mr.role_id IN (SELECT role_id FROM role_user WHERE user_id = ID_KAMU AND organization_id = ID_ORG_AKTIF)
  AND mr.is_view = 1
  AND mr.deleted_at IS NULL;
```

### 3. `active_organization_id` Tidak Tersedia di Session
Jika `session('active_organization_id')` kosong, pengecekan tidak akan menemukan peran. Pastikan saat login diatur:
```php
session(['active_organization_id' => $orgId]);
```

---

## ✅ Perbaikan — `module_id` Tetap Boleh NULL

Kode saya **tidak pernah mewajibkan `module_id`**. Menu dengan `module_id = NULL` tetap diproses. Yang perlu dipastikan adalah **tidak ada filter tambahan** yang mengharuskan modul terhubung.

Berikut versi yang **lebih aman** — pastikan tidak ada filter `module_id` di kode tampilan menu:

```php
// Di AuthServiceProvider — tidak ada filter module_id, jadi aman
$allMenus = Cache::remember('acl-all-menus', 3600, function () {
    return Menu::where('is_active', 1)
        ->whereNull('deleted_at') // ← tambahkan ini!
        ->pluck('slug', 'id');
});
```

> ⚠️ **Tambahkan `whereNull('deleted_at')`** — menu yang sudah di-soft delete ikut tersaring, ini sering terlewat.

---

## 🛠️ Langkah Pemulihan Cepat

### Langkah 1: Hapus Cache
```bash
php artisan cache:clear
```

### Langkah 2: Perbaiki `AuthServiceProvider` — Pastikan `deleted_at` Disaring
```php
$allMenus = Cache::remember('acl-all-menus', 3600, function () {
    return Menu::where('is_active', 1)
        ->whereNull('deleted_at') // ← KUNCI INI
        ->pluck('slug', 'id');
});
```

### Langkah 3: Periksa Kode Tampilan Menu (Blade)
Pastikan **tidak ada kondisi** seperti ini:
```php
// ❌ SALAH — menyembunyikan menu tanpa modul
->whereNotNull('module_id')

// ✅ BENAR — semua menu aktif ditampilkan
->where('is_active', 1)
->whereNull('deleted_at')
```

### Langkah 4: Verifikasi Session Saat Login
```php
// Di LoginController / AuthController
$org = $user->currentOrganization();
session([
    'active_organization_id' => $org?->id,
    'user_auth.active_role_id' => $roleId,
]);
```

### Langkah 5: Cek Langsung di Tinker
```bash
php artisan tinker
```
```php
$user = \App\Models\User::find(1);
$orgId = $user->active_organization_id;
echo "Org Aktif: $orgId\n";

// Cek peran user
print_r($user->roleUsers()->where('organization_id', $orgId)->get());

// Cek izin menu
echo $user->hasMenuPermission('dashboard', 'is_view') ? "ADA IZIN" : "TIDAK ADA IZIN";
```

---

## 📌 Ringkasan

| Pertanyaan | Jawaban |
|---|---|
| Apakah `module_id` wajib? | **TIDAK** — boleh `NULL` untuk menu induk |
| Kenapa menu hilang setelah update? | Biasanya **cache lama** atau **`deleted_at` tidak disaring** |
| Bagaimana memulihkan? | Hapus cache → tambahkan `whereNull('deleted_at')` → pastikan session `active_organization_id` terisi |
| Apakah perlu isi `module_id` ke semua menu lama? | **Tidak perlu**, modul baru saja yang butuh dihubungkan |

Coba jalankan `php artisan cache:clear` lalu login ulang — apakah menu sudah muncul? Kalau masih belum, kasih tahu saya hasil cek dari Tinker di atas, saya bantu lacak lebih lanjut 😊.