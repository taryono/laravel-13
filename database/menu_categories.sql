SET NAMES utf8mb4;

-- ----------------------------------------------------------
-- Seeder: menu_categories (10 modul Menu Utama)
-- Jalankan SETELAH tabel menu_categories dibuat dengan struktur baru
-- (kolom icon, color, sort_order sudah ada).
--
-- Ganti nilai @organization_id sesuai id organisasi tujuan.
-- Aman dijalankan berulang (ON DUPLICATE KEY UPDATE pada organization_id + code).
-- ----------------------------------------------------------
SET @organization_id = 1;
SET @now = NOW();

INSERT INTO `menu_categories`
  (`organization_id`, `parent_id`, `code`, `name`, `description`, `icon`, `color`, `sort_order`, `is_system`, `is_active`, `show_to_trial`, `created_at`, `updated_at`)
VALUES
  (@organization_id, NULL, 'keuangan',      'Keuangan',      'SPP & Bayar',    'wallet',          'emerald', 10,  1, 1, 0, @now, @now),
  (@organization_id, NULL, 'data-akademik', 'Data Akademik', 'Kurikulum',      'graduation-cap',  'blue',    20,  1, 1, 0, @now, @now),
  (@organization_id, NULL, 'fasilitas',     'Fasilitas',     'Inventaris Lab', 'building-2',      'amber',   30,  1, 1, 0, @now, @now),
  (@organization_id, NULL, 'antar-jemput',  'Antar Jemput',  'Rute Bus',       'bus',             'sky',     40,  1, 1, 0, @now, @now),
  (@organization_id, NULL, 'cbt',           'CBT',           'Ujian & Bank',   'laptop',          'purple',  50,  1, 1, 0, @now, @now),
  (@organization_id, NULL, 'ppdb',          'PPDB Baru',     'Pendaftaran',    'user-check',      'rose',    60,  1, 1, 0, @now, @now),
  (@organization_id, NULL, 'absensi',       'Absensi',       'Presensi QR',    'clipboard-check', 'indigo',  70,  1, 1, 0, @now, @now),
  (@organization_id, NULL, 'karyawan',      'Karyawan',      'SDM & Guru',     'id-card',         'cyan',    80,  1, 1, 0, @now, @now),
  (@organization_id, NULL, 'akuntansi',     'Akuntansi',     'Buku Besar',     'receipt-text',    'teal',    90,  1, 1, 0, @now, @now),
  (@organization_id, NULL, 'budgeting',     'Budgeting',     'RAPBS',          'chart-pie',       'orange',  100, 1, 1, 0, @now, @now)
ON DUPLICATE KEY UPDATE
  `name`        = VALUES(`name`),
  `description` = VALUES(`description`),
  `icon`        = VALUES(`icon`),
  `color`       = VALUES(`color`),
  `sort_order`  = VALUES(`sort_order`),
  `is_system`   = VALUES(`is_system`),
  `is_active`   = VALUES(`is_active`),
  `updated_at`  = VALUES(`updated_at`);