Baik, saya pahami konteksnya sekarang:

- Saat ini role di sistem Anda **hanya `super-admin`**.
- `trial_registration_role` menyimpan role user di yayasan **mereka** (bukan role di sistem Anda).
- Anda ingin **seeder SQL** untuk mengisi `users`, `roles`, dan `role_user`.

Saya akan buatkan seeder yang:

1. **Insert roles** berdasarkan analisis domain tabel Anda (25 role) → tapi saya sesuaikan jadi **role sistem yang masuk akal** untuk SIAKAD + Pesantren + Keuangan.
2. **Insert 1 user super-admin** (dan beberapa user contoh untuk role lain).
3. **Insert role_user** untuk mapping user ↔ role ↔ organization.

---

## ⚠️ Asumsi yang Saya Pakai

Karena struktur Anda punya `organization_id`, saya asumsikan:

- Ada tabel `organizations` (sudah ada di daftar Anda).
- Saya pakai **1 organization default** dengan `id = 1` (yayasan pusat).
- Role `super-admin` punya `organization_id = NULL` (global, lintas yayasan) — ini praktik umum.
- Role lain punya `organization_id = 1` (spesifik yayasan).
- Kolom `is_system = 1` untuk role bawaan yang tidak boleh dihapus.
- `parent_id` saya isi `NULL` semua (bisa Anda kembangkan jadi hierarki).
- `created_by` / `updated_by` saya isi `1` (user super-admin).

Kalau `organizations` Anda belum ada isinya, insert dulu organization-nya (saya sertakan opsional).

---

## 1️⃣ Seeder: `organizations` (opsional, jika belum ada)

```sql
INSERT INTO organizations (id, name, code, is_active, created_at, updated_at)
VALUES 
(1, 'Yayasan Pusat', 'YYS-PUSAT', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name);
```

> Sesuaikan nama kolom dengan struktur `organizations` Anda.

---

## 2️⃣ Seeder: `users`

Saya buat 6 user contoh:

| id | name | email | Role |
|---|---|---|---|
| 1 | Super Admin | superadmin@example.com | super-admin |
| 2 | Admin Yayasan | admin@example.com | admin-yayasan |
| 3 | Kepala Sekolah | kepsek@example.com | kepala-sekolah |
| 4 | Guru Contoh | guru@example.com | guru |
| 5 | Bendahara | bendahara@example.com | bendahara |
| 6 | Siswa Contoh | siswa@example.com | siswa |

Password default: `password` (hash bcrypt — **ganti di produksi!**).

```sql
INSERT INTO users 
(id, name, email, email_verified_at, password, current_organization_id, isCheckingRole, created_at, updated_at, created_by, updated_by)
VALUES
(1, 'Super Admin',     'superadmin@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 0, NOW(), NOW(), 1, 1),
(2, 'Admin Yayasan',   'admin@example.com',      NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1,    0, NOW(), NOW(), 1, 1),
(3, 'Kepala Sekolah',  'kepsek@example.com',     NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1,    0, NOW(), NOW(), 1, 1),
(4, 'Guru Contoh',     'guru@example.com',       NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1,    0, NOW(), NOW(), 1, 1),
(5, 'Bendahara',       'bendahara@example.com',  NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1,    0, NOW(), NOW(), 1, 1),
(6, 'Siswa Contoh',    'siswa@example.com',      NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1,    0, NOW(), NOW(), 1, 1)
ON DUPLICATE KEY UPDATE name = VALUES(name);
```

> Hash di atas adalah bcrypt untuk password `password`. Kalau mau generate ulang, jalankan `php artisan tinker` → `bcrypt('password')`.

---

## 3️⃣ Seeder: `roles`

Saya susun **hierarki role** berdasarkan domain tabel Anda. Role `super-admin` dibuat global (`organization_id = NULL`), sisanya milik organization `1`.

| id | code | name | parent |
|---|---|---|---|
| 1 | super-admin | Super Admin | NULL |
| 2 | admin-yayasan | Admin Yayasan | NULL |
| 3 | kepala-sekolah | Kepala Sekolah | 2 |
| 4 | admin-akademik | Admin Akademik | 2 |
| 5 | guru | Guru | 4 |
| 6 | wali-kelas | Wali Kelas | 5 |
| 7 | guru-bk | Guru BK / Konselor | 4 |
| 8 | siswa | Siswa | NULL |
| 9 | orang-tua | Orang Tua | NULL |
| 10 | admin-keuangan | Admin Keuangan | 2 |
| 11 | bendahara | Bendahara / Kasir | 10 |
| 12 | staf-pajak | Staf Pajak | 10 |
| 13 | admin-hr | Admin HR | 2 |
| 14 | staf-payroll | Staf Payroll | 13 |
| 15 | pustakawan | Pustakawan | 2 |
| 16 | pengelola-asrama | Pengelola Asrama | 2 |
| 17 | pengelola-kantin | Pengelola Kantin | 2 |
| 18 | pengelola-inventory | Pengelola Inventory/Aset | 2 |
| 19 | admin-ppdb | Panitia PPDB | 2 |
| 20 | operator-cbt | Operator CBT | 4 |
| 21 | operator-dapodik | Operator Dapodik | 4 |
| 22 | klinik | Petugas Klinik/UKS | 2 |
| 23 | transport | Pengelola Transport | 2 |
| 24 | alumni | Alumni | NULL |
| 25 | vendor | Vendor | NULL |
| 26 | auditor | Auditor Internal | NULL |
| 27 | approver | Approver / Pejabat | NULL |
| 28 | pimpinan | Pimpinan / Direktur | NULL |

```sql
INSERT INTO roles
(id, organization_id, code, name, display_name, description, is_system, is_active, parent_id, created_at, updated_at, created_by, updated_by)
VALUES
(1,  NULL, 'super-admin',          'super-admin',          'Super Admin',              'Akses penuh seluruh sistem lintas yayasan', 1, 1, NULL, NOW(), NOW(), 1, 1),
(2,  1,    'admin-yayasan',        'admin-yayasan',        'Admin Yayasan',            'Administrator tingkat yayasan',             1, 1, NULL, NOW(), NOW(), 1, 1),
(3,  1,    'kepala-sekolah',       'kepala-sekolah',       'Kepala Sekolah',           'Pimpinan unit sekolah',                     1, 1, 2,    NOW(), NOW(), 1, 1),
(4,  1,    'admin-akademik',       'admin-akademik',       'Admin Akademik',           'Kelola kurikulum, kelas, jadwal',           1, 1, 2,    NOW(), NOW(), 1, 1),
(5,  1,    'guru',                 'guru',                 'Guru',                     'Pengajar mata pelajaran',                   1, 1, 4,    NOW(), NOW(), 1, 1),
(6,  1,    'wali-kelas',           'wali-kelas',           'Wali Kelas',               'Guru dengan tanggung jawab kelas',          1, 1, 5,    NOW(), NOW(), 1, 1),
(7,  1,    'guru-bk',              'guru-bk',              'Guru BK / Konselor',       'Bimbingan konseling siswa',                 1, 1, 4,    NOW(), NOW(), 1, 1),
(8,  1,    'siswa',                'siswa',                'Siswa',                    'Peserta didik',                             1, 1, NULL, NOW(), NOW(), 1, 1),
(9,  1,    'orang-tua',            'orang-tua',            'Orang Tua',                'Wali murid',                                1, 1, NULL, NOW(), NOW(), 1, 1),
(10, 1,    'admin-keuangan',       'admin-keuangan',       'Admin Keuangan',           'Kelola akuntansi & invoice',                1, 1, 2,    NOW(), NOW(), 1, 1),
(11, 1,    'bendahara',            'bendahara',            'Bendahara / Kasir',        'Terima pembayaran & kuitansi',              1, 1, 10,   NOW(), NOW(), 1, 1),
(12, 1,    'staf-pajak',           'staf-pajak',           'Staf Pajak',               'Kelola pajak & pelaporan',                  1, 1, 10,   NOW(), NOW(), 1, 1),
(13, 1,    'admin-hr',             'admin-hr',             'Admin HR',                 'Kelola kepegawaian',                        1, 1, 2,    NOW(), NOW(), 1, 1),
(14, 1,    'staf-payroll',         'staf-payroll',         'Staf Payroll',             'Proses gaji & slip gaji',                   1, 1, 13,   NOW(), NOW(), 1, 1),
(15, 1,    'pustakawan',           'pustakawan',           'Pustakawan',               'Kelola perpustakaan',                       1, 1, 2,    NOW(), NOW(), 1, 1),
(16, 1,    'pengelola-asrama',     'pengelola-asrama',     'Pengelola Asrama',         'Musyrif/musyrifah asrama',                  1, 1, 2,    NOW(), NOW(), 1, 1),
(17, 1,    'pengelola-kantin',     'pengelola-kantin',     'Pengelola Kantin',         'Kelola kantin & tenant',                    1, 1, 2,    NOW(), NOW(), 1, 1),
(18, 1,    'pengelola-inventory',  'pengelola-inventory',  'Pengelola Inventory',      'Kelola stok, aset, pengadaan',              1, 1, 2,    NOW(), NOW(), 1, 1),
(19, 1,    'admin-ppdb',           'admin-ppdb',           'Panitia PPDB',             'Kelola penerimaan siswa baru',              1, 1, 2,    NOW(), NOW(), 1, 1),
(20, 1,    'operator-cbt',         'operator-cbt',         'Operator CBT',             'Monitoring ujian berbasis komputer',        1, 1, 4,    NOW(), NOW(), 1, 1),
(21, 1,    'operator-dapodik',     'operator-dapodik',     'Operator Dapodik',         'Sinkronisasi data Dapodik',                 1, 1, 4,    NOW(), NOW(), 1, 1),
(22, 1,    'klinik',               'klinik',               'Petugas Klinik/UKS',       'Kelola kesehatan siswa',                    1, 1, 2,    NOW(), NOW(), 1, 1),
(23, 1,    'transport',            'transport',            'Pengelola Transport',      'Kelola kendaraan & rute',                   1, 1, 2,    NOW(), NOW(), 1, 1),
(24, 1,    'alumni',               'alumni',               'Alumni',                   'Akses tracer study & donasi',               1, 1, NULL, NOW(), NOW(), 1, 1),
(25, 1,    'vendor',               'vendor',               'Vendor',                   'Mitra pengadaan',                           1, 1, NULL, NOW(), NOW(), 1, 1),
(26, 1,    'auditor',              'auditor',              'Auditor Internal',         'Read-only audit & log',                     1, 1, NULL, NOW(), NOW(), 1, 1),
(27, 1,    'approver',             'approver',             'Approver / Pejabat',       'Menyetujui pengajuan workflow',             1, 1, NULL, NOW(), NOW(), 1, 1),
(28, 1,    'pimpinan',             'pimpinan',             'Pimpinan / Direktur',      'Dashboard & laporan strategis',             1, 1, NULL, NOW(), NOW(), 1, 1)
ON DUPLICATE KEY UPDATE name = VALUES(name);
```

> **Catatan:** `role_user` Anda punya `is_default` dan `organization_id`. Saya isi `is_default = 1` untuk role utama user.

---

## 4️⃣ Seeder: `role_user`

Mapping user ↔ role:

| user_id | role_id | organization_id | is_default |
|---|---|---|---|
| 1 | 1 (super-admin) | NULL | 1 |
| 2 | 2 (admin-yayasan) | 1 | 1 |
| 3 | 3 (kepala-sekolah) | 1 | 1 |
| 4 | 5 (guru) | 1 | 1 |
| 4 | 6 (wali-kelas) | 1 | 0 |
| 5 | 11 (bendahara) | 1 | 1 |
| 5 | 10 (admin-keuangan) | 1 | 0 |
| 6 | 8 (siswa) | 1 | 1 |

```sql
INSERT INTO role_user
(id, user_id, role_id, organization_id, is_default, created_at, updated_at, created_by, updated_by)
VALUES
(1, 1, 1,  NULL, 1, NOW(), NOW(), 1, 1),  -- Super Admin
(2, 2, 2,  1,    1, NOW(), NOW(), 1, 1),  -- Admin Yayasan
(3, 3, 3,  1,    1, NOW(), NOW(), 1, 1),  -- Kepala Sekolah
(4, 4, 5,  1,    1, NOW(), NOW(), 1, 1),  -- Guru
(5, 4, 6,  1,    0, NOW(), NOW(), 1, 1),  -- Wali Kelas (tambahan)
(6, 5, 11, 1,    1, NOW(), NOW(), 1, 1),  -- Bendahara
(7, 5, 10, 1,    0, NOW(), NOW(), 1, 1),  -- Admin Keuangan (tambahan)
(8, 6, 8,  1,    1, NOW(), NOW(), 1, 1)   -- Siswa
ON DUPLICATE KEY UPDATE is_default = VALUES(is_default);
```

---

## 5️⃣ Urutan Eksekusi Seeder

Kalau Anda pakai MySQL/MariaDB dengan foreign key, jalankan berurutan:

```sql
-- 1. organizations (jika belum ada)
-- 2. users
-- 3. roles
-- 4. role_user
```

Kalau pakai Laravel Seeder, buat 3 class:

```bash
php artisan make:seeder RoleSeeder
php artisan make:seeder UserSeeder
php artisan make:seeder RoleUserSeeder
```

Lalu di `DatabaseSeeder.php`:

```php
$this->call([
    RoleSeeder::class,
    UserSeeder::class,
    RoleUserSeeder::class,
]);
```

---

## 6️⃣ Yang Perlu Anda Sesuaikan

1. **Hash password** — ganti dengan hasil `bcrypt()` Anda sendiri.
2. **`organization_id`** — pastikan organization `id = 1` sudah ada, atau ganti sesuai ID yayasan Anda.
3. **Kolom `organizations`** — nama kolom (`name`, `code`, `is_active`) mungkin beda dengan punya Anda.
4. **`is_system`** — kalau Anda ingin role tertentu tidak bisa dihapus, biarkan `1`. Kalau semua role bisa dikustom yayasan, ubah jadi `0`.
5. **`parent_id`** — kalau Anda belum pakai hierarki, boleh di-`NULL`-kan semua.
6. **Role `super-admin`** — saya set `organization_id = NULL` supaya global. Kalau sistem Anda mengharuskan semua role punya organization, ganti jadi `1`.

---

## 7️⃣ Setelah Seeder Jalan

Langkah berikutnya yang saya sarankan:

1. **Isi tabel `permissions`** — daftar permission granular (misal `student.view`, `invoice.create`, `payroll.run`).
2. **Isi `role_permissions`** — mapping role ↔ permission.
3. **Isi `menus` + `menu_role`** — supaya sidebar otomatis menyesuaikan role.
4. **Isi `modules` + `navigation`** — kalau Anda pakai struktur modular.

Kalau Anda mau, saya bisa lanjutkan buatkan:
- **Seeder `permissions`** (granular per domain),
- **Seeder `role_permissions`** (matriks role × permission),
- **Seeder `menus` + `menu_role`**.

Mau saya lanjutkan ke salah satu? Atau Anda mau saya sesuaikan dulu seeder di atas dengan struktur `organizations` Anda yang sebenarnya?