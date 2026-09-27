📊 Dashboard

👥 Kesiswaan (Student Management)
├── Data Siswa
├── Pendaftaran (PPDB)
│   ├── Pendaftar
│   ├── Seleksi
│   ├── Timeline
│   └── Dokumen
├── Kelas & Rombel
│   ├── Tingkat Kelas
│   ├── Rombel
│   └── Promosi Siswa
├── Kehadiran
│   ├── Absensi Harian
│   ├── Rekap Kehadiran
│   └── Import Absensi
├── Kedisiplinan
├── Prestasi
├── Ekstrakurikuler
│   └── Kehadiran Ekskul
├── Kesehatan Siswa
├── Konseling (BK)
└── Orang Tua / Wali

👨‍🏫 Kepegawaian (HR / Staff)
├── Data Pegawai
├── Kontrak Kerja
├── Mutasi Pegawai
├── Cuti
│   ├── Pengajuan Cuti
│   ├── Saldo Cuti
│   └── Jenis Cuti
├── Shift Kerja
├── Absensi Pegawai
└── Izin / SPPD

📚 Akademik (Academic)
├── Kurikulum
│   ├── Fase Kurikulum
│   └── Struktur Kurikulum
├── Mata Pelajaran
├── Jadwal Pelajaran
│   ├── Jadwal
│   ├── Generate Jadwal
│   └── Constraint Ruangan
├── Beban Mengajar
├── RPP / Lesson Plan
├── Materi Pembelajaran
├── Penilaian
│   ├── Nilai
│   ├── Skala Nilai
│   ├── Capaian Pembelajaran
│   └── Rapor
│       └── Template Rapor
├── Kalender Akademik
└── Tahun Ajaran

📝 CBT / Ujian Online
├── Bank Soal
├── Paket Ujian
├── Sesi Ujian
├── Proctoring
└── Log Aktivitas CBT

💰 Keuangan (Finance)
├── Akuntansi
│   ├── Bagan Akun (COA)
│   ├── Jurnal
│   │   ├── Jurnal Umum
│   │   └── Detail Jurnal
│   ├── Buku Besar
│   ├── Saldo Awal
│   └── Tutup Buku (Fiscal Closing)
├── Tagihan Siswa (AR)
│   ├── Master Biaya
│   ├── Struktur Biaya
│   ├── Invoice Siswa
│   ├── Batch Invoice
│   ├── Pembayaran
│   └── Cicilan
├── Piutang (AR)
├── Hutang (AP)
│   ├── Vendor
│   ├── Invoice Vendor
│   ├── Pembayaran Vendor
│   └── Purchase Order
├── Kas & Bank
│   ├── Akun Bank
│   ├── Transaksi Bank
│   ├── Mutasi Bank
│   ├── Rekonsiliasi Bank
│   └── Virtual Account / QRIS
├── Pajak
│   ├── Jenis Pajak
│   ├── Pengaturan Pajak
│   └── Kewajiban Pajak
└── Anggaran (Budget)
    ├── Budget
    ├── Detail Budget
    └── Cost Center

💼 Payroll (Penggajian)
├── Komponen Gaji
├── Struktur Gaji
├── Run Payroll
├── Slip Gaji
├── Bonus
├── Potongan
├── Pajak PPh21 (PTKP)
├── Integrasi Payroll
└── Audit Payroll

🏛️ BOS / Dana Sekolah
├── Alokasi BOS
├── Komponen BOS
├── Periode BOS
├── Rencana Penggunaan
├── Realisasi
├── Penerimaan
└── Laporan BOS

📋 RKAS (Rencana Kegiatan & Anggaran Sekolah)
├── Program
├── Kegiatan
├── Sub-Kegiatan
├── Item / Komponen
├── Budget
├── Sumber Dana
├── Standar
├── Realisasi
├── Revisi
├── Approval
├── Dokumen
└── Laporan

🏢 Aset & Inventaris
├── Aset Tetap
├── Penyusutan
├── Kategori Aset
├── Gedung
├── Lantai
├── Ruangan
├── Fasilitas
└── Kategori Fasilitas

📖 Perpustakaan
├── Buku
└── Peminjaman

💬 Komunikasi
├── Pengumuman
├── Pesan
└── Diskusi
    └── Balasan

🔗 Integrasi
├── Dapodik
│   ├── Sync
│   ├── Mapping
│   └── Jadwal Sync
└── Referensi
    ├── Tipe Referensi
    └── Nilai Referensi

⚙️ Pengaturan (Settings)
├── Sekolah
├── Organisasi
│   ├── Tipe Organisasi
│   └── Organisasi
├── Departemen
├── Jabatan
├── Data Referensi
│   ├── Agama
│   ├── Golongan Darah
│   ├── Jenis Identitas
│   ├── Status Pernikahan
│   ├── Pendidikan
│   ├── Gender
│   └── Kelas
├── Mata Uang & Kurs
├── Periode & Tahun Fiskal
├── Modul
├── Menu & Navigasi
├── Pelanggan & Vendor
├── Kategori Akun
└── Mapping Akun

🔐 Akses & Keamanan (RBAC)
├── User
├── Role
├── Permission
├── Menu Role
├── Role Permission
├── Action
├── Action Role
└── Permission Request

📊 Laporan (Reports)
├── Laporan Akademik
├── Laporan Keuangan
├── Laporan Kepegawaian
├── Laporan BOS / RKAS
├── Laporan Aset
└── Generated Reports

🔍 Audit & Log
├── Audit Trail
├── Log Sistem
├── Log Akses (Rate Limit)
├── Failed Jobs
├── Jobs & Batches
└── Scheduled Jobs

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE menus;
-- TRUNCATE TABLE menu_role;
 -- ============================================================
-- SEEDER MENUS - RESTRUKTURISASI LENGKAP
-- Total: ± 180 menu (root + child)
-- ============================================================

-- Bersihkan dulu (HATI-HATI: backup dulu!)
-- TRUNCATE TABLE menus;
-- TRUNCATE TABLE menu_role;

-- ============================================================
-- 1. ROOT MENUS
-- ============================================================
INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(10,  1, NULL, NULL, 'dashboard',      'Dasbor',              'Dasbor',              'dashboard',      'fas fa-home',           'fa',       'dashboard',                    'dashboard',      'sidebar', 1,  1, 1, 'Dashboard',              'Dasbor',              NOW(), NOW(), 1, 1),
(20,  1, NULL, NULL, 'master',         'Data Utama',          'Data Utama',          'master',         'fas fa-database',       'fa',       NULL,                           NULL,             'sidebar', 2,  1, 1, 'Master Data',            'Data Utama',          NOW(), NOW(), 1, 1),
(30,  1, NULL, NULL, 'academic',       'Akademik',            'Akademik',            'academic',       'fas fa-book',           'fa',       NULL,                           NULL,             'sidebar', 3,  1, 1, 'Academic',               'Akademik',            NOW(), NOW(), 1, 1),
(40,  1, NULL, NULL, 'students',       'Kesiswaan',           'Kesiswaan',           'students',       'fas fa-user-graduate',  'fa',       NULL,                           NULL,             'sidebar', 4,  1, 1, 'Students',               'Kesiswaan',           NOW(), NOW(), 1, 1),
(50,  1, NULL, NULL, 'assessment',     'Penilaian & Rapor',   'Penilaian',           'assessment',     'fas fa-clipboard-check','fa',       NULL,                           NULL,             'sidebar', 5,  1, 1, 'Assessment & Report',    'Penilaian & Rapor',   NOW(), NOW(), 1, 1),
(60,  1, NULL, NULL, 'cbt',            'E-Learning & CBT',    'E-Learning',          'cbt',            'fas fa-laptop',         'fa',       NULL,                           NULL,             'sidebar', 6,  1, 1, 'E-Learning & CBT',       'E-Learning & CBT',    NOW(), NOW(), 1, 1),
(70,  1, NULL, NULL, 'attendance',     'Kehadiran',           'Kehadiran',           'attendance',     'fas fa-calendar-check', 'fa',       NULL,                           NULL,             'sidebar', 7,  1, 1, 'Attendance',             'Kehadiran',           NOW(), NOW(), 1, 1),
(80,  1, NULL, NULL, 'admission',      'PPDB',                'PPDB',                'ppdb',      'fas fa-edit',           'fa',       NULL,                           NULL,             'sidebar', 8,  1, 1, 'Admission',              'PPDB',                NOW(), NOW(), 1, 1),
(90,  1, NULL, NULL, 'finance',        'Keuangan',            'Keuangan',            'finance',        'fas fa-money-bill',     'fa',       NULL,                           NULL,             'sidebar', 9,  1, 1, 'Finance',                'Keuangan',            NOW(), NOW(), 1, 1),
(100, 1, NULL, NULL, 'accounting',     'Akuntansi',           'Akuntansi',           'accounting',     'fas fa-calculator',     'fa',       NULL,                           NULL,             'sidebar', 10, 1, 1, 'Accounting',             'Akuntansi',           NOW(), NOW(), 1, 1),
(110, 1, NULL, NULL, 'employment',     'Kepegawaian',         'Kepegawaian',         'employment',     'fas fa-users-cog',      'fa',       NULL,                           NULL,             'sidebar', 11, 1, 1, 'Employment',             'Kepegawaian',         NOW(), NOW(), 1, 1),
(120, 1, NULL, NULL, 'payroll',        'Payroll',             'Payroll',             'payroll',        'fas fa-wallet',         'fa',       NULL,                           NULL,             'sidebar', 12, 1, 1, 'Payroll',                'Payroll',             NOW(), NOW(), 1, 1),
(130, 1, NULL, NULL, 'library',        'Perpustakaan',        'Perpustakaan',        'library',        'fas fa-book-reader',    'fa',       NULL,                           NULL,             'sidebar', 13, 1, 1, 'Library',                'Perpustakaan',        NOW(), NOW(), 1, 1),
(140, 1, NULL, NULL, 'dormitory',      'Asrama & Pesantren',  'Asrama',              'dormitory',      'fas fa-hotel',          'fa',       NULL,                           NULL,             'sidebar', 14, 1, 1, 'Dormitory & Pesantren',  'Asrama & Pesantren',  NOW(), NOW(), 1, 1),
(150, 1, NULL, NULL, 'inventory',      'Sarana & Inventaris', 'Inventaris',          'inventory',      'fas fa-boxes',          'fa',       NULL,                           NULL,             'sidebar', 15, 1, 1, 'Inventory & Assets',     'Sarana & Inventaris', NOW(), NOW(), 1, 1),
(160, 1, NULL, NULL, 'transport',      'Transportasi',        'Transportasi',        'transport',      'fas fa-bus',            'fa',       NULL,                           NULL,             'sidebar', 16, 1, 1, 'Transportation',         'Transportasi',        NOW(), NOW(), 1, 1),
(170, 1, NULL, NULL, 'clinic',         'Klinik & Kesehatan',  'Klinik',              'clinic',         'fas fa-heartbeat',      'fa',       NULL,                           NULL,             'sidebar', 17, 1, 1, 'Clinic & Health',        'Klinik & Kesehatan',  NOW(), NOW(), 1, 1),
(180, 1, NULL, NULL, 'canteen',        'Kantin',              'Kantin',              'canteen',        'fas fa-utensils',       'fa',       NULL,                           NULL,             'sidebar', 18, 1, 1, 'Canteen',                'Kantin',              NOW(), NOW(), 1, 1),
(190, 1, NULL, NULL, 'alumni',         'Alumni',              'Alumni',              null,         'fas fa-graduation-cap', 'fa',       NULL,                           NULL,             'sidebar', 19, 1, 1, 'Alumni',                 'Alumni',              NOW(), NOW(), 1, 1),
(200, 1, NULL, NULL, 'reference',      'Referensi',           'Referensi',           'reference',      'fas fa-list',           'fa',       NULL,                           NULL,             'sidebar', 20, 1, 1, 'Reference',              'Referensi',           NOW(), NOW(), 1, 1),
(210, 1, NULL, NULL, 'settings',       'Pengaturan',          'Pengaturan',          'settings',       'fas fa-cog',            'fa',       NULL,                           NULL,             'sidebar', 21, 1, 1, 'Settings',               'Pengaturan',          NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(21, 1, NULL, 20, 'user',              'Pengguna',            'Pengguna',       'user',              'fas fa-users',        'fa', 'user.index',              'user',              'sidebar', 1, 1, 1, 'Users',            'Pengguna',          NOW(), NOW(), 1, 1),
(22, 1, NULL, 20, 'role',              'Peran & Izin',        'Peran',          'roles',             'fas fa-user-shield',  'fa', 'role.index',              'role',              'sidebar', 2, 1, 1, 'Roles & Permissions','Peran & Izin',      NOW(), NOW(), 1, 1),
(23, 1, NULL, 20, 'menu-management',   'Manajemen Menu',      'Menu',           'menu-management',   'fas fa-bars',         'fa', 'menu_management.index',   'menu-management',   'sidebar', 3, 1, 1, 'Menu Management',  'Manajemen Menu',    NOW(), NOW(), 1, 1),
(24, 1, NULL, 20, 'module-generator',  'Module Generator',    'Generator',      'module-generator',  'fas fa-cubes',        'fa', 'crud_generator.index',    'crud-generator',    'sidebar', 4, 1, 1, 'Module Generator', 'Module Generator',  NOW(), NOW(), 1, 1),
(25, 1, NULL, 20, 'organization',      'Organisasi',          'Organisasi',     'organization-2',      'fas fa-building',     'fa', 'organization.index',      'organization-2',      'sidebar', 5, 1, 1, 'Organization',     'Organisasi',        NOW(), NOW(), 1, 1);


INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(31, 1, NULL, 30, 'academic-year',     'Tahun Ajaran',        'Tahun Ajaran',   'academic-year',     'fas fa-calendar',     'fa', 'academic_year.index',           'academic-year',      'sidebar', 1, 1, 1, 'Academic Year',      'Tahun Ajaran',      NOW(), NOW(), 1, 1),
(32, 1, NULL, 30, 'academic-semester', 'Semester',            'Semester',       'academic-semester', 'fas fa-calendar-alt', 'fa', 'academic_semester.index',       'academic-semester',  'sidebar', 2, 1, 1, 'Semester',           'Semester',          NOW(), NOW(), 1, 1),
(33, 1, NULL, 30, 'academic-calendar', 'Kalender Akademik',   'Kalender',       'academic-calendar', 'fas fa-calendar-day', 'fa', 'academic_calendar_event.index', 'academic-calendar',  'sidebar', 3, 1, 1, 'Academic Calendar',  'Kalender Akademik', NOW(), NOW(), 1, 1),
(34, 1, NULL, 30, 'curriculum',        'Kurikulum',           'Kurikulum',      'curriculum',        'fas fa-book',         'fa', 'curriculum.index',              'curriculum',         'sidebar', 4, 1, 1, 'Curriculum',         'Kurikulum',         NOW(), NOW(), 1, 1),
(35, 1, NULL, 30, 'curriculum-course', 'Mata Pelajaran Kurikulum','Mapel',     'curriculum-course', 'fas fa-book-open',    'fa', 'curriculum_course.index',       'curriculum-course',  'sidebar', 5, 1, 1, 'Curriculum Course',  'Mapel Kurikulum',   NOW(), NOW(), 1, 1),
(36, 1, NULL, 30, 'subject',           'Mata Pelajaran',      'Mapel',          'subject',           'fas fa-bookmark',     'fa', 'subject.index',                 'subject',            'sidebar', 6, 1, 1, 'Subject',            'Mata Pelajaran',    NOW(), NOW(), 1, 1),
(37, 1, NULL, 30, 'subject-category',  'Kategori Mapel',      'Kategori',       'subject-category',  'fas fa-tags',         'fa', 'subject_category.index',        'subject-category',   'sidebar', 7, 1, 1, 'Subject Category',   'Kategori Mapel',    NOW(), NOW(), 1, 1),
(38, 1, NULL, 30, 'class-level',       'Tingkat Kelas',       'Tingkat',        'class-level',       'fas fa-layer-group',  'fa', 'class_level.index',             'class-level',        'sidebar', 8, 1, 1, 'Class Level',        'Tingkat Kelas',     NOW(), NOW(), 1, 1),
(39, 1, NULL, 30, 'classroom',         'Kelas',               'Kelas',          'classroom',         'fas fa-door-open',    'fa', 'classroom.index',               'classroom',          'sidebar', 9, 1, 1, 'Classroom',          'Kelas',             NOW(), NOW(), 1, 1),
(310,1, NULL, 30, 'schedule',          'Jadwal Pelajaran',    'Jadwal',         'schedule',          'fas fa-clock',        'fa', 'schedule.index',                'schedule',           'sidebar', 10,1, 1, 'Schedule',           'Jadwal',            NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(41, 1, NULL, 40, 'student',               'Data Siswa',          'Siswa',       'student',               'fas fa-user',         'fa', 'student.index',                 'student',               'sidebar', 1, 1, 1, 'Students',              'Data Siswa',          NOW(), NOW(), 1, 1),
(42, 1, NULL, 40, 'student-classroom',     'Penempatan Kelas',    'Penempatan',  'student-classroom',     'fas fa-users',        'fa', 'student_classroom.index',       'student-classroom',     'sidebar', 2, 1, 1, 'Student Classroom',     'Penempatan Kelas',    NOW(), NOW(), 1, 1),
(43, 1, NULL, 40, 'student-parent',        'Orang Tua Siswa',     'Orang Tua',   'student-parent',        'fas fa-user-friends', 'fa', 'student_parent.index',          'student-parent',        'sidebar', 3, 1, 1, 'Student Parent',        'Orang Tua Siswa',     NOW(), NOW(), 1, 1),
(44, 1, NULL, 40, 'parent',                'Data Orang Tua',      'Orang Tua',   'parent',                'fas fa-user-friends', 'fa', 'parent.index',                  'parent',                'sidebar', 4, 1, 1, 'Parents',               'Data Orang Tua',      NOW(), NOW(), 1, 1),
(45, 1, NULL, 40, 'student-discipline',    'Kedisiplinan',        'Disiplin',    'student-discipline',    'fas fa-gavel',        'fa', 'student_discipline.index',      'student-discipline',    'sidebar', 5, 1, 1, 'Student Discipline',    'Kedisiplinan',        NOW(), NOW(), 1, 1),
(46, 1, NULL, 40, 'student-health',        'Kesehatan Siswa',     'Kesehatan',   'student-health-record', 'fas fa-heartbeat',    'fa', 'student_health_record.index',   'student-health-record', 'sidebar', 6, 1, 1, 'Student Health',        'Kesehatan Siswa',     NOW(), NOW(), 1, 1),
(47, 1, NULL, 40, 'student-promotion',     'Kenaikan Kelas',      'Kenaikan',    'student-promotion',     'fas fa-arrow-up',     'fa', 'student_promotion.index',       'student-promotion',     'sidebar', 7, 1, 1, 'Student Promotion',     'Kenaikan Kelas',      NOW(), NOW(), 1, 1),
(48, 1, NULL, 40, 'promotion-batch',       'Batch Kenaikan',      'Batch',       'promotion-batch',       'fas fa-layer-group',  'fa', 'promotion_batch.index',         'promotion-batch',       'sidebar', 8, 1, 1, 'Promotion Batch',       'Batch Kenaikan',      NOW(), NOW(), 1, 1),
(49, 1, NULL, 40, 'promotion-criterion',   'Kriteria Kenaikan',   'Kriteria',    'promotion-criterion',   'fas fa-check-double', 'fa', 'promotion_criterion.index',     'promotion-criterion',   'sidebar', 9, 1, 1, 'Promotion Criteria',    'Kriteria Kenaikan',   NOW(), NOW(), 1, 1),
(410,1, NULL, 40, 'promotion-approval',    'Approval Kenaikan',   'Approval',    'promotion-approval',    'fas fa-check-circle', 'fa', 'promotion_approval.index',      'promotion-approval',    'sidebar', 10,1, 1, 'Promotion Approval',    'Approval Kenaikan',   NOW(), NOW(), 1, 1),
(411,1, NULL, 40, 'student-extracurricular','Ekstrakurikuler Siswa','Ekskul',     'student-extracurricular','fas fa-futbol',      'fa', 'student_extracurricular.index', 'student-extracurricular','sidebar',11,1, 1, 'Student Extracurricular','Ekskul Siswa',       NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(51, 1, NULL, 50, 'assessment-component', 'Komponen Penilaian', 'Komponen',   'assessment-component', 'fas fa-list',         'fa', 'assessment_component.index', 'assessment-component', 'sidebar', 1, 1, 1, 'Assessment Component', 'Komponen Penilaian', NOW(), NOW(), 1, 1),
(52, 1, NULL, 50, 'assessment-type',      'Jenis Penilaian',    'Jenis',      'assessment-type',      'fas fa-tag',          'fa', 'assessment_type.index',      'assessment-type',      'sidebar', 2, 1, 1, 'Assessment Type',      'Jenis Penilaian',    NOW(), NOW(), 1, 1),
(53, 1, NULL, 50, 'assessment-method',    'Metode Penilaian',   'Metode',     'assessment-method',    'fas fa-tasks',        'fa', 'assessment_method.index',    'assessment-method',    'sidebar', 3, 1, 1, 'Assessment Method',    'Metode Penilaian',   NOW(), NOW(), 1, 1),
(54, 1, NULL, 50, 'assessment-rubric',    'Rubrik Penilaian',   'Rubrik',     'assessment-rubric',    'fas fa-clipboard',    'fa', 'assessment_rubric.index',    'assessment-rubric',    'sidebar', 4, 1, 1, 'Assessment Rubric',    'Rubrik Penilaian',   NOW(), NOW(), 1, 1),
(55, 1, NULL, 50, 'grade-scale',          'Skala Nilai',        'Skala',      'grade-scale',          'fas fa-sort-numeric-up','fa','grade_scale.index',          'grade-scale',          'sidebar', 5, 1, 1, 'Grade Scale',          'Skala Nilai',        NOW(), NOW(), 1, 1),
(56, 1, NULL, 50, 'grade-boundary',       'Batas Nilai',        'Batas',      'grade-boundary',       'fas fa-ruler',        'fa', 'grade_boundary.index',       'grade-boundary',       'sidebar', 6, 1, 1, 'Grade Boundary',       'Batas Nilai',        NOW(), NOW(), 1, 1),
(57, 1, NULL, 50, 'grade-weight-config',  'Konfigurasi Bobot',  'Bobot',      'grade-weight-config',  'fas fa-balance-scale','fa', 'grade_weight_config.index',  'grade-weight-config',  'sidebar', 7, 1, 1, 'Grade Weight Config',  'Konfigurasi Bobot',  NOW(), NOW(), 1, 1),
(58, 1, NULL, 50, 'attitude-assessment',  'Penilaian Sikap',    'Sikap',      'attitude-assessment',  'fas fa-smile',        'fa', 'attitude_assessment.index',  'attitude-assessment',  'sidebar', 8, 1, 1, 'Attitude Assessment',  'Penilaian Sikap',    NOW(), NOW(), 1, 1),
(59, 1, NULL, 50, 'report-card',          'Rapor',              'Rapor',      'report-card',          'fas fa-file-alt',     'fa', 'report_card.index',          'report-card',          'sidebar', 9, 1, 1, 'Report Card',          'Rapor',              NOW(), NOW(), 1, 1),
(510,1, NULL, 50, 'report-card-template', 'Template Rapor',     'Template',   'report-card-template', 'fas fa-file-code',    'fa', 'report_card_template.index', 'report-card-template', 'sidebar', 10,1, 1, 'Report Card Template', 'Template Rapor',     NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(61, 1, NULL, 60, 'question-bank',       'Bank Soal',          'Bank Soal',  'question-bank',       'fas fa-database',     'fa', 'question_bank.index',       'question-bank',       'sidebar', 1, 1, 1, 'Question Bank',       'Bank Soal',       NOW(), NOW(), 1, 1),
(62, 1, NULL, 60, 'question-type',       'Jenis Soal',         'Jenis',      'question-type',       'fas fa-tag',          'fa', 'question_type.index',       'question-type',       'sidebar', 2, 1, 1, 'Question Type',       'Jenis Soal',      NOW(), NOW(), 1, 1),
(63, 1, NULL, 60, 'difficulty-level',    'Tingkat Kesulitan',  'Kesulitan',  'difficulty-level',    'fas fa-signal',       'fa', 'difficulty_level.index',    'difficulty-level',    'sidebar', 3, 1, 1, 'Difficulty Level',    'Tingkat Kesulitan',NOW(), NOW(), 1, 1),
(64, 1, NULL, 60, 'cognitive-level',     'Level Kognitif',     'Kognitif',   'cognitive-level',     'fas fa-brain',        'fa', 'cognitive_level.index',     'cognitive-level',     'sidebar', 4, 1, 1, 'Cognitive Level',     'Level Kognitif',  NOW(), NOW(), 1, 1),
(65, 1, NULL, 60, 'question-stimulus',   'Stimulus Soal',      'Stimulus',   'question-stimulus',   'fas fa-image',        'fa', 'question_stimulus.index',   'question-stimulus',   'sidebar', 5, 1, 1, 'Question Stimulus',   'Stimulus Soal',   NOW(), NOW(), 1, 1),
(66, 1, NULL, 60, 'exam-package',        'Paket Ujian',        'Paket',      'exam-package',        'fas fa-box',          'fa', 'exam_package.index',        'exam-package',        'sidebar', 6, 1, 1, 'Exam Package',        'Paket Ujian',     NOW(), NOW(), 1, 1),
(67, 1, NULL, 60, 'test-schedule',       'Jadwal Ujian',       'Jadwal',     'test-schedule',       'fas fa-calendar',     'fa', 'test_schedule.index',       'test-schedule',       'sidebar', 7, 1, 1, 'Test Schedule',       'Jadwal Ujian',    NOW(), NOW(), 1, 1),
(68, 1, NULL, 60, 'cbt-session',         'Sesi CBT',           'Sesi',       'cbt-session',         'fas fa-desktop',      'fa', 'cbt_session.index',         'cbt-session',         'sidebar', 8, 1, 1, 'CBT Session',         'Sesi CBT',        NOW(), NOW(), 1, 1),
(69, 1, NULL, 60, 'cbt-proctoring',      'Proctoring',         'Proctoring', 'cbt-proctoring',      'fas fa-eye',          'fa', 'cbt_proctoring.index',      'cbt-proctoring',      'sidebar', 9, 1, 1, 'CBT Proctoring',      'Proctoring',      NOW(), NOW(), 1, 1),
(610,1, NULL, 60, 'cbt-activity-log',    'Log Aktivitas CBT',  'Log',        'cbt-activity-log',    'fas fa-history',      'fa', 'cbt_activity_log.index',    'cbt-activity-log',    'sidebar', 10,1, 1, 'CBT Activity Log',    'Log CBT',         NOW(), NOW(), 1, 1),
(611,1, NULL, 60, 'cbt-session-answer',  'Jawaban Sesi',       'Jawaban',    'cbt-session-answer',  'fas fa-check',        'fa', 'cbt_session_answer.index',  'cbt-session-answer',  'sidebar', 11,1, 1, 'CBT Session Answer',  'Jawaban Sesi',    NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(71, 1, NULL, 70, 'student-attendance',       'Absensi Siswa',        'Siswa',      'student-attendance',       'fas fa-user-check',    'fa', 'student_attendance.index',       'student-attendance',       'sidebar', 1, 1, 1, 'Student Attendance',       'Absensi Siswa',        NOW(), NOW(), 1, 1),
(72, 1, NULL, 70, 'employee-attendance',      'Absensi Pegawai',      'Pegawai',    'employee-attendance',      'fas fa-user-clock',    'fa', 'employee_attendance.index',      'employee-attendance',      'sidebar', 2, 1, 1, 'Employee Attendance',      'Absensi Pegawai',      NOW(), NOW(), 1, 1),
(73, 1, NULL, 70, 'extracurricular-attendance','Absensi Ekstrakurikuler','Ekskul',  'extracurricular-attendance','fas fa-futbol',       'fa', 'extracurricular_attendance.index','extracurricular-attendance','sidebar', 3, 1, 1, 'Extracurricular Attendance','Absensi Ekskul',       NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(81, 1, NULL, 80, 'applicant',              'Pendaftar',           'Pendaftar',   'applicant',              'fas fa-user-plus',    'fa', 'applicant.index',              'applicant',              'sidebar', 1, 1, 1, 'Applicant',              'Pendaftar',           NOW(), NOW(), 1, 1),
(82, 1, NULL, 80, 'admission',              'Penerimaan',          'Penerimaan',  'admission',              'fas fa-file-signature','fa','admission.index',              'admission',              'sidebar', 2, 1, 1, 'Admission',              'Penerimaan',          NOW(), NOW(), 1, 1),
(83, 1, NULL, 80, 'admission-path',         'Jalur Pendaftaran',   'Jalur',       'admission-path',         'fas fa-route',        'fa', 'admission_path.index',         'admission-path',         'sidebar', 3, 1, 1, 'Admission Path',         'Jalur Pendaftaran',   NOW(), NOW(), 1, 1),
(84, 1, NULL, 80, 'admission-timeline',     'Timeline PPDB',       'Timeline',    'admission-timeline',     'fas fa-calendar-alt', 'fa', 'admission_timeline.index',     'admission-timeline',     'sidebar', 4, 1, 1, 'Admission Timeline',     'Timeline PPDB',       NOW(), NOW(), 1, 1),
(85, 1, NULL, 80, 'admission-selection',    'Seleksi',             'Seleksi',     'admission-selection',    'fas fa-filter',       'fa', 'admission_selection.index',    'admission-selection',    'sidebar', 5, 1, 1, 'Admission Selection',    'Seleksi',             NOW(), NOW(), 1, 1),
(86, 1, NULL, 80, 'admission-test-schedule','Jadwal Tes',          'Jadwal Tes',  'admission-test-schedule','fas fa-calendar-day', 'fa', 'admission_test_schedule.index','admission-test-schedule','sidebar', 6, 1, 1, 'Admission Test Schedule','Jadwal Tes',          NOW(), NOW(), 1, 1),
(87, 1, NULL, 80, 'admission-interview',    'Wawancara',           'Wawancara',   'admission-interview',    'fas fa-comments',     'fa', 'admission_interview.index',    'admission-interview',    'sidebar', 7, 1, 1, 'Admission Interview',    'Wawancara',           NOW(), NOW(), 1, 1),
(88, 1, NULL, 80, 'admission-document',     'Verifikasi Dokumen',  'Dokumen',     'admission-document',     'fas fa-file-check',   'fa', 'admission_document.index',     'admission-document',     'sidebar', 8, 1, 1, 'Document Verification',  'Verifikasi Dokumen',  NOW(), NOW(), 1, 1),
(89, 1, NULL, 80, 'admission-announcement', 'Pengumuman',          'Pengumuman',  'admission-announcement', 'fas fa-bullhorn',     'fa', 'admission_announcement.index', 'admission-announcement', 'sidebar', 9, 1, 1, 'Announcement',           'Pengumuman',          NOW(), NOW(), 1, 1),
(810,1, NULL, 80, 'admission-quota',        'Kuota',               'Kuota',       'admission-quota',        'fas fa-users',        'fa', 'admission_quota.index',        'admission-quota',        'sidebar', 10,1, 1, 'Admission Quota',        'Kuota',               NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(91, 1, NULL, 90, 'fee-master',          'Master Biaya',        'Biaya',       'fee-master',          'fas fa-money-bill',   'fa', 'fee_master.index',          'fee-master',          'sidebar', 1, 1, 1, 'Fee Master',          'Master Biaya',     NOW(), NOW(), 1, 1),
(92, 1, NULL, 90, 'fee-structure',       'Struktur Biaya',      'Struktur',    'fee-structure',       'fas fa-sitemap',      'fa', 'fee_structure.index',       'fee-structure',       'sidebar', 2, 1, 1, 'Fee Structure',       'Struktur Biaya',   NOW(), NOW(), 1, 1),
(93, 1, NULL, 90, 'student-invoice',     'Tagihan Siswa',       'Tagihan',     'student-invoice',     'fas fa-file-invoice', 'fa', 'student_invoice.index',     'student-invoice',     'sidebar', 3, 1, 1, 'Student Invoice',     'Tagihan Siswa',    NOW(), NOW(), 1, 1),
(94, 1, NULL, 90, 'student-payment',     'Pembayaran Siswa',    'Pembayaran',  'student-payment',     'fas fa-money-check',  'fa', 'student_payment.index',     'student-payment',     'sidebar', 4, 1, 1, 'Student Payment',     'Pembayaran Siswa', NOW(), NOW(), 1, 1),
(95, 1, NULL, 90, 'payment-channel',     'Kanal Pembayaran',    'Kanal',       'payment-channel',     'fas fa-credit-card',  'fa', 'payment_channel.index',     'payment-channel',     'sidebar', 5, 1, 1, 'Payment Channel',     'Kanal Pembayaran', NOW(), NOW(), 1, 1),
(96, 1, NULL, 90, 'virtual-account',     'Virtual Account',     'VA',          'virtual-account',     'fas fa-university',   'fa', 'virtual_account.index',     'virtual-account',     'sidebar', 6, 1, 1, 'Virtual Account',     'Virtual Account',  NOW(), NOW(), 1, 1),
(97, 1, NULL, 90, 'scholarship',         'Beasiswa',            'Beasiswa',    'scholarship',         'fas fa-award',        'fa', 'scholarship.index',         'scholarship',         'sidebar', 7, 1, 1, 'Scholarship',         'Beasiswa',         NOW(), NOW(), 1, 1),
(98, 1, NULL, 90, 'financial-aid',       'Bantuan Biaya',       'Bantuan',     'financial-aid',       'fas fa-hand-holding-usd','fa','financial_aid.index',      'financial-aid',       'sidebar', 8, 1, 1, 'Financial Aid',       'Bantuan Biaya',    NOW(), NOW(), 1, 1),
(99, 1, NULL, 90, 'expense',             'Pengeluaran',         'Pengeluaran', 'expense',             'fas fa-shopping-cart','fa', 'expense.index',             'expense',             'sidebar', 9, 1, 1, 'Expense',             'Pengeluaran',      NOW(), NOW(), 1, 1),
(910,1, NULL, 90, 'expense-category',    'Kategori Pengeluaran','Kategori',    'expense-category',    'fas fa-tags',         'fa', 'expense_category.index',    'expense-category',    'sidebar', 10,1, 1, 'Expense Category',    'Kategori Pengeluaran',NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(101, 1, NULL, 100, 'chart-of-account',    'Chart of Account',    'COA',         'chart-of-account',    'fas fa-list-ol',      'fa', 'account.index',              'account',               'sidebar', 1, 1, 1, 'Chart of Account',    'Chart of Account',   NOW(), NOW(), 1, 1),
(102, 1, NULL, 100, 'account-category',    'Kategori Akun',       'Kategori',    'account-category',    'fas fa-tags',         'fa', 'account_category.index',     'account-category',      'sidebar', 2, 1, 1, 'Account Category',    'Kategori Akun',      NOW(), NOW(), 1, 1),
(103, 1, NULL, 100, 'account-mapping',     'Mapping Akun',        'Mapping',     'account-mapping',     'fas fa-project-diagram','fa','account_mapping.index',      'account-mapping',       'sidebar', 3, 1, 1, 'Account Mapping',     'Mapping Akun',       NOW(), NOW(), 1, 1),
(104, 1, NULL, 100, 'journal-entry',       'Jurnal Umum',         'Jurnal',      'journal-entry',       'fas fa-book',         'fa', 'journal_entry.index',        'journal-entry',         'sidebar', 4, 1, 1, 'Journal Entry',       'Jurnal Umum',        NOW(), NOW(), 1, 1),
(105, 1, NULL, 100, 'accounting-period',   'Periode Akuntansi',   'Periode',     'accounting-period',   'fas fa-calendar',     'fa', 'accounting_period.index',    'accounting-period',     'sidebar', 5, 1, 1, 'Accounting Period',   'Periode Akuntansi',  NOW(), NOW(), 1, 1),
(106, 1, NULL, 100, 'fiscal-year',         'Tahun Fiskal',        'Fiskal',      'fiscal-year',         'fas fa-calendar-alt', 'fa', 'fiscal_year.index',          'fiscal-year',           'sidebar', 6, 1, 1, 'Fiscal Year',         'Tahun Fiskal',       NOW(), NOW(), 1, 1),
(107, 1, NULL, 100, 'opening-balance',     'Saldo Awal',          'Saldo Awal',  'opening-balance',     'fas fa-balance-scale','fa', 'opening_balance.index',      'opening-balance',       'sidebar', 7, 1, 1, 'Opening Balance',     'Saldo Awal',         NOW(), NOW(), 1, 1),
(108, 1, NULL, 100, 'bank-account',        'Akun Bank',           'Bank',        'bank-account',        'fas fa-university',   'fa', 'bank_account.index',         'bank-account',          'sidebar', 8, 1, 1, 'Bank Account',        'Akun Bank',          NOW(), NOW(), 1, 1),
(109, 1, NULL, 100, 'bank-transaction',    'Transaksi Bank',      'Transaksi',   'bank-transaction',    'fas fa-exchange-alt', 'fa', 'bank_transaction.index',     'bank-transaction',      'sidebar', 9, 1, 1, 'Bank Transaction',    'Transaksi Bank',     NOW(), NOW(), 1, 1),
(1010,1, NULL, 100, 'bank-reconciliation', 'Rekonsiliasi Bank',   'Rekonsiliasi','bank-reconciliation', 'fas fa-check-double', 'fa', 'bank_reconciliation.index',  'bank-reconciliation',   'sidebar', 10,1, 1, 'Bank Reconciliation', 'Rekonsiliasi Bank',  NOW(), NOW(), 1, 1),
(1011,1, NULL, 100, 'tax-type',            'Jenis Pajak',         'Pajak',       'tax-type',            'fas fa-percent',      'fa', 'tax_type.index',             'tax-type',              'sidebar', 11,1, 1, 'Tax Type',            'Jenis Pajak',        NOW(), NOW(), 1, 1),
(1012,1, NULL, 100, 'tax-setting',         'Pengaturan Pajak',    'Pajak',       'tax-setting',         'fas fa-cog',          'fa', 'tax_setting.index',          'tax-setting',           'sidebar', 12,1, 1, 'Tax Setting',         'Pengaturan Pajak',   NOW(), NOW(), 1, 1),
(1013,1, NULL, 100, 'budget',              'Anggaran',            'Anggaran',    'budget',              'fas fa-chart-pie',    'fa', 'budget.index',               'budget',                'sidebar', 13,1, 1, 'Budget',              'Anggaran',           NOW(), NOW(), 1, 1),
(1014,1, NULL, 100, 'rkas',                'RKAS',                'RKAS',        'rkas',                'fas fa-file-invoice-dollar','fa','rkas.index',             'rkas',                  'sidebar', 14,1, 1, 'RKAS',                'RKAS',               NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(111, 1, NULL, 110, 'employee',          'Data Pegawai',       'Pegawai',    'employee',          'fas fa-user-tie',     'fa', 'employee.index',          'employee',          'sidebar', 1, 1, 1, 'Employee',          'Data Pegawai',     NOW(), NOW(), 1, 1),
(112, 1, NULL, 110, 'teacher',           'Data Guru',          'Guru',       'teacher',           'fas fa-chalkboard-teacher','fa','teacher.index',          'teacher',           'sidebar', 2, 1, 1, 'Teacher',           'Data Guru',        NOW(), NOW(), 1, 1),
(113, 1, NULL, 110, 'employee-type',     'Jenis Pegawai',      'Jenis',      'employee-type',     'fas fa-tags',         'fa', 'employee_type.index',     'employee-type',     'sidebar', 3, 1, 1, 'Employee Type',     'Jenis Pegawai',    NOW(), NOW(), 1, 1),
(114, 1, NULL, 110, 'position',          'Jabatan',            'Jabatan',    'position',          'fas fa-briefcase',    'fa', 'position.index',          'position',          'sidebar', 4, 1, 1, 'Position',          'Jabatan',          NOW(), NOW(), 1, 1),
(115, 1, NULL, 110, 'department',        'Departemen',         'Departemen', 'department',        'fas fa-building',     'fa', 'department.index',        'department',        'sidebar', 5, 1, 1, 'Department',        'Departemen',       NOW(), NOW(), 1, 1),
(116, 1, NULL, 110, 'employee-contract', 'Kontrak Pegawai',    'Kontrak',    'employee-contract', 'fas fa-file-contract','fa', 'employee_contract.index', 'employee-contract', 'sidebar', 6, 1, 1, 'Employee Contract', 'Kontrak Pegawai',  NOW(), NOW(), 1, 1),
(117, 1, NULL, 110, 'employee-shift',    'Shift Pegawai',      'Shift',      'employee-shift',    'fas fa-clock',        'fa', 'shift.index',             'shift',             'sidebar', 7, 1, 1, 'Employee Shift',    'Shift Pegawai',    NOW(), NOW(), 1, 1),
(118, 1, NULL, 110, 'shift-swap',        'Tukar Shift',        'Tukar Shift','shift-swap',        'fas fa-exchange-alt', 'fa', 'shift_swap.index',        'shift-swap',        'sidebar', 8, 1, 1, 'Shift Swap',        'Tukar Shift',      NOW(), NOW(), 1, 1),
(119, 1, NULL, 110, 'work-schedule',     'Jadwal Kerja',       'Jadwal',     'work-schedule',     'fas fa-calendar',     'fa', 'work_schedule.index',     'work-schedule',     'sidebar', 9, 1, 1, 'Work Schedule',     'Jadwal Kerja',     NOW(), NOW(), 1, 1),
(1110,1, NULL, 110, 'leave-type',        'Jenis Cuti',         'Cuti',       'leave-type',        'fas fa-tags',         'fa', 'leave_type.index',        'leave-type',        'sidebar', 10,1, 1, 'Leave Type',        'Jenis Cuti',       NOW(), NOW(), 1, 1),
(1111,1, NULL, 110, 'employee-leave',    'Cuti Pegawai',       'Cuti',       'employee-leave',    'fas fa-plane',        'fa', 'employee_leave.index',    'employee-leave',    'sidebar', 11,1, 1, 'Employee Leave',    'Cuti Pegawai',     NOW(), NOW(), 1, 1),
(1112,1, NULL, 110, 'overtime',          'Lembur',             'Lembur',     'overtime',          'fas fa-clock',        'fa', 'overtime.index',          'overtime',          'sidebar', 12,1, 1, 'Overtime',          'Lembur',           NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(121, 1, NULL, 120, 'salary-component',   'Komponen Gaji',      'Komponen',    'salary-component',   'fas fa-list',         'fa', 'salary_component.index',   'salary-component',   'sidebar', 1, 1, 1, 'Salary Component',   'Komponen Gaji',      NOW(), NOW(), 1, 1),
(122, 1, NULL, 120, 'salary-structure',   'Struktur Gaji',      'Struktur',    'salary-structure',   'fas fa-sitemap',      'fa', 'salary_structure.index',   'salary-structure',   'sidebar', 2, 1, 1, 'Salary Structure',   'Struktur Gaji',      NOW(), NOW(), 1, 1),
(123, 1, NULL, 120, 'payroll-period',     'Periode Payroll',    'Periode',     'payroll-period',     'fas fa-calendar',     'fa', 'payroll_period.index',     'payroll-period',     'sidebar', 3, 1, 1, 'Payroll Period',     'Periode Payroll',    NOW(), NOW(), 1, 1),
(124, 1, NULL, 120, 'payroll-run',        'Proses Payroll',     'Proses',      'payroll-run',        'fas fa-play',         'fa', 'payroll_run.index',        'payroll-run',        'sidebar', 4, 1, 1, 'Payroll Run',        'Proses Payroll',     NOW(), NOW(), 1, 1),
(125, 1, NULL, 120, 'payslip',            'Slip Gaji',          'Slip',        'payslip',            'fas fa-file-invoice', 'fa', 'payslip.index',            'payslip',            'sidebar', 5, 1, 1, 'Payslip',            'Slip Gaji',          NOW(), NOW(), 1, 1),
(126, 1, NULL, 120, 'bonus',              'Bonus',              'Bonus',       'bonus',              'fas fa-gift',         'fa', 'bonus.index',              'bonus',              'sidebar', 6, 1, 1, 'Bonus',              'Bonus',              NOW(), NOW(), 1, 1),
(127, 1, NULL, 120, 'deduction',          'Potongan',           'Potongan',    'deduction',          'fas fa-minus-circle', 'fa', 'deduction.index',          'deduction',          'sidebar', 7, 1, 1, 'Deduction',          'Potongan',           NOW(), NOW(), 1, 1),
(128, 1, NULL, 120, 'payroll-setting',    'Pengaturan Payroll', 'Pengaturan',  'payroll-setting',    'fas fa-cog',          'fa', 'payroll_setting.index',    'payroll-setting',    'sidebar', 8, 1, 1, 'Payroll Setting',    'Pengaturan Payroll', NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(131, 1, NULL, 130, 'book',              'Buku',              'Buku',       'book',              'fas fa-book',         'fa', 'library_book.index',       'library-book',       'sidebar', 1, 1, 1, 'Book',              'Buku',              NOW(), NOW(), 1, 1),
(132, 1, NULL, 130, 'book-category',     'Kategori Buku',     'Kategori',   'book-category',     'fas fa-tags',         'fa', 'book_category.index',      'book-category',      'sidebar', 2, 1, 1, 'Book Category',     'Kategori Buku',     NOW(), NOW(), 1, 1),
(133, 1, NULL, 130, 'book-author',       'Penulis',           'Penulis',    'book-author',       'fas fa-user-edit',    'fa', 'book_author.index',        'book-author',        'sidebar', 3, 1, 1, 'Book Author',       'Penulis',           NOW(), NOW(), 1, 1),
(134, 1, NULL, 130, 'book-publisher',    'Penerbit',          'Penerbit',   'book-publisher',    'fas fa-building',     'fa', 'book_publisher.index',     'book-publisher',     'sidebar', 4, 1, 1, 'Book Publisher',    'Penerbit',          NOW(), NOW(), 1, 1),
(135, 1, NULL, 130, 'book-copy',         'Eksemplar',         'Eksemplar',  'book-copy',         'fas fa-copy',         'fa', 'book_copy.index',          'book-copy',          'sidebar', 5, 1, 1, 'Book Copy',         'Eksemplar',         NOW(), NOW(), 1, 1),
(136, 1, NULL, 130, 'library-loan',      'Peminjaman',        'Pinjam',     'library-loan',      'fas fa-hand-holding', 'fa', 'library_loan.index',       'library-loan',       'sidebar', 6, 1, 1, 'Library Loan',      'Peminjaman',        NOW(), NOW(), 1, 1),
(137, 1, NULL, 130, 'library-reservation','Reservasi',        'Reservasi',  'library-reservation','fas fa-bookmark',    'fa', 'book_reservation.index',   'library-reservation','sidebar', 7, 1, 1, 'Reservation',       'Reservasi',         NOW(), NOW(), 1, 1),
(138, 1, NULL, 130, 'library-fine',      'Denda',             'Denda',      'library-fine',      'fas fa-money-bill',   'fa', 'library_fine.index',       'library-fine',       'sidebar', 8, 1, 1, 'Library Fine',      'Denda',             NOW(), NOW(), 1, 1),
(139, 1, NULL, 130, 'library-visit',     'Kunjungan',         'Kunjungan',  'library-visit',     'fas fa-door-open',    'fa', 'library_visit.index',      'library-visit',      'sidebar', 9, 1, 1, 'Library Visit',     'Kunjungan',         NOW(), NOW(), 1, 1),
(1310,1, NULL, 130, 'digital-library',   'Perpustakaan Digital','Digital',  'digital-library',   'fas fa-tablet-alt',   'fa', 'digital_library.index',    'digital-library',    'sidebar', 10,1, 1, 'Digital Library',   'Perpustakaan Digital',NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(141, 1, NULL, 140, 'dormitory-room',        'Kamar Asrama',       'Kamar',      'dormitory-room',        'fas fa-bed',          'fa', 'dormitory_room.index',        'dormitory-room',        'sidebar', 1, 1, 1, 'Dormitory Room',        'Kamar Asrama',       NOW(), NOW(), 1, 1),
(142, 1, NULL, 140, 'dormitory-assignment',  'Penempatan Asrama',  'Penempatan', 'dormitory-assignment',  'fas fa-user-plus',    'fa', 'dormitory_assignment.index',  'dormitory-assignment',  'sidebar', 2, 1, 1, 'Dormitory Assignment',  'Penempatan Asrama',  NOW(), NOW(), 1, 1),
(143, 1, NULL, 140, 'dormitory-movement',    'Mutasi Asrama',      'Mutasi',     'dormitory-movement',    'fas fa-exchange-alt', 'fa', 'dormitory_movement.index',    'dormitory-movement',    'sidebar', 3, 1, 1, 'Dormitory Movement',    'Mutasi Asrama',      NOW(), NOW(), 1, 1),
(144, 1, NULL, 140, 'dormitory-permit',      'Izin Asrama',        'Izin',       'dormitory-permit',      'fas fa-file-signature','fa','dormitory_permit.index',      'dormitory-permit',      'sidebar', 4, 1, 1, 'Dormitory Permit',      'Izin Asrama',        NOW(), NOW(), 1, 1),
(145, 1, NULL, 140, 'pesantren-activity',    'Kegiatan Pesantren', 'Kegiatan',   'pesantren-activity',    'fas fa-mosque',       'fa', 'pesantren_activity.index',    'pesantren-activity',    'sidebar', 5, 1, 1, 'Pesantren Activity',    'Kegiatan Pesantren', NOW(), NOW(), 1, 1),
(146, 1, NULL, 140, 'pesantren-rule',        'Aturan Pesantren',   'Aturan',     'pesantren-rule',        'fas fa-gavel',        'fa', 'pesantren_rule.index',        'pesantren-rule',        'sidebar', 6, 1, 1, 'Pesantren Rule',        'Aturan Pesantren',   NOW(), NOW(), 1, 1),
(147, 1, NULL, 140, 'sorogan-schedule',      'Jadwal Sorogan',     'Sorogan',    'sorogan-schedule',      'fas fa-book-quran',   'fa', 'sorogan_schedule.index',      'sorogan-schedule',      'sidebar', 7, 1, 1, 'Sorogan Schedule',      'Jadwal Sorogan',     NOW(), NOW(), 1, 1),
(148, 1, NULL, 140, 'sorogan-evaluation',    'Evaluasi Sorogan',   'Evaluasi',   'sorogan-evaluation',    'fas fa-check-circle', 'fa', 'sorogan_evaluation.index',    'sorogan-evaluation',    'sidebar', 8, 1, 1, 'Sorogan Evaluation',    'Evaluasi Sorogan',   NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(151, 1, NULL, 150, 'inventory-item',      'Item Inventaris',    'Item',       'inventory-item',      'fas fa-box',          'fa', 'inventory_item.index',      'inventory-item',      'sidebar', 1, 1, 1, 'Inventory Item',      'Item Inventaris',    NOW(), NOW(), 1, 1),
(152, 1, NULL, 150, 'inventory-category',  'Kategori Inventaris','Kategori',   'inventory-category',  'fas fa-tags',         'fa', 'inventory_category.index',  'inventory-category',  'sidebar', 2, 1, 1, 'Inventory Category',  'Kategori Inventaris',NOW(), NOW(), 1, 1),
(153, 1, NULL, 150, 'warehouse',           'Gudang',             'Gudang',     'warehouse',           'fas fa-warehouse',    'fa', 'warehouse.index',           'warehouse',           'sidebar', 3, 1, 1, 'Warehouse',           'Gudang',             NOW(), NOW(), 1, 1),
(154, 1, NULL, 150, 'stock-movement',      'Mutasi Stok',        'Mutasi',     'stock-movement',      'fas fa-exchange-alt', 'fa', 'stock_movement.index',      'stock-movement',      'sidebar', 4, 1, 1, 'Stock Movement',      'Mutasi Stok',        NOW(), NOW(), 1, 1),
(155, 1, NULL, 150, 'stock-adjustment',    'Penyesuaian Stok',   'Penyesuaian','stock-adjustment',    'fas fa-sliders-h',    'fa', 'stock_adjustment.index',    'stock-adjustment',    'sidebar', 5, 1, 1, 'Stock Adjustment',    'Penyesuaian Stok',   NOW(), NOW(), 1, 1),
(156, 1, NULL, 150, 'asset',               'Aset',               'Aset',       'asset',               'fas fa-building',     'fa', 'asset.index',               'asset',               'sidebar', 6, 1, 1, 'Asset',               'Aset',               NOW(), NOW(), 1, 1),
(157, 1, NULL, 150, 'asset-category',      'Kategori Aset',      'Kategori',   'asset-category',      'fas fa-tags',         'fa', 'asset_category.index',      'asset-category',      'sidebar', 7, 1, 1, 'Asset Category',      'Kategori Aset',      NOW(), NOW(), 1, 1),
(158, 1, NULL, 150, 'asset-maintenance',   'Pemeliharaan Aset',  'Pemeliharaan','asset-maintenance',  'fas fa-tools',        'fa', 'asset_maintenance.index',   'asset-maintenance',   'sidebar', 8, 1, 1, 'Asset Maintenance',   'Pemeliharaan Aset',  NOW(), NOW(), 1, 1),
(159, 1, NULL, 150, 'asset-disposal',      'Penghapusan Aset',   'Disposal',   'asset-disposal',      'fas fa-trash-alt',    'fa', 'asset_disposal.index',      'asset-disposal',      'sidebar', 9, 1, 1, 'Asset Disposal',      'Penghapusan Aset',   NOW(), NOW(), 1, 1),
(1510,1, NULL, 150, 'purchase-request',    'Permintaan Pembelian','PR',        'purchase-request',    'fas fa-file-alt',     'fa', 'purchase_request.index',    'purchase-request',    'sidebar', 10,1, 1, 'Purchase Request',    'Permintaan Pembelian',NOW(), NOW(), 1, 1),
(1511,1, NULL, 150, 'purchase-order',      'Pesanan Pembelian',  'PO',         'purchase-order',      'fas fa-file-invoice', 'fa', 'purchase_order.index',      'purchase-order',      'sidebar', 11,1, 1, 'Purchase Order',      'Pesanan Pembelian',  NOW(), NOW(), 1, 1),
(1512,1, NULL, 150, 'vendor',              'Vendor',             'Vendor',     'vendor',              'fas fa-truck',        'fa', 'vendor.index',              'vendor',              'sidebar', 12,1, 1, 'Vendor',              'Vendor',             NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(161, 1, NULL, 160, 'vehicle',              'Kendaraan',           'Kendaraan',  'vehicle',              'fas fa-bus',          'fa', 'vehicle.index',              'vehicle',              'sidebar', 1, 1, 1, 'Vehicle',              'Kendaraan',           NOW(), NOW(), 1, 1),
(162, 1, NULL, 160, 'driver',               'Driver',              'Driver',     'driver',               'fas fa-user-tie',     'fa', 'driver.index',               'driver',               'sidebar', 2, 1, 1, 'Driver',               'Driver',              NOW(), NOW(), 1, 1),
(163, 1, NULL, 160, 'transport-route',      'Rute',                'Rute',       'transport-route',      'fas fa-route',        'fa', 'transport_route.index',      'transport-route',      'sidebar', 3, 1, 1, 'Transport Route',      'Rute',                NOW(), NOW(), 1, 1),
(164, 1, NULL, 160, 'transport-stop',       'Halte',               'Halte',      'transport-stop',       'fas fa-map-marker-alt','fa','transport_stop.index',       'transport-stop',       'sidebar', 4, 1, 1, 'Transport Stop',       'Halte',               NOW(), NOW(), 1, 1),
(165, 1, NULL, 160, 'transport-subscription','Langganan Transport','Langganan',  'transport-subscription','fas fa-id-card',     'fa', 'student_transport_subscription.index','transport-subscription','sidebar',5,1, 1, 'Transport Subscription','Langganan Transport',NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(171, 1, NULL, 170, 'clinic-visit',      'Kunjungan Klinik',  'Kunjungan',  'clinic-visit',      'fas fa-stethoscope',  'fa', 'clinic_visit.index',      'clinic-visit',      'sidebar', 1, 1, 1, 'Clinic Visit',      'Kunjungan Klinik',  NOW(), NOW(), 1, 1),
(172, 1, NULL, 170, 'clinic-medicine',   'Obat',              'Obat',       'clinic-medicine',   'fas fa-pills',        'fa', 'clinic_medicine.index',   'clinic-medicine',   'sidebar', 2, 1, 1, 'Medicine',          'Obat',              NOW(), NOW(), 1, 1),
(173, 1, NULL, 170, 'clinic-prescription','Resep',            'Resep',      'clinic-prescription','fas fa-file-prescription','fa','clinic_prescription.index','clinic-prescription','sidebar',3, 1, 1, 'Prescription',      'Resep',             NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(181, 1, NULL, 180, 'canteen-tenant',   'Tenant Kantin',   'Tenant',   'canteen-tenant',   'fas fa-store',     'fa', 'canteen_tenant.index',   'canteen-tenant',   'sidebar', 1, 1, 1, 'Canteen Tenant',   'Tenant Kantin',   NOW(), NOW(), 1, 1),
(182, 1, NULL, 180, 'canteen-menu',     'Menu Kantin',     'Menu',     'canteen-menu',     'fas fa-utensils',  'fa', 'canteen_menu.index',     'canteen-menu',     'sidebar', 2, 1, 1, 'Canteen Menu',     'Menu Kantin',     NOW(), NOW(), 1, 1),
(183, 1, NULL, 180, 'canteen-order',    'Pesanan Kantin',  'Pesanan',  'canteen-order',    'fas fa-shopping-bag','fa','canteen_order.index',    'canteen-order',    'sidebar', 3, 1, 1, 'Canteen Order',    'Pesanan Kantin',  NOW(), NOW(), 1, 1),
(184, 1, NULL, 180, 'canteen-settlement','Settlement',     'Settlement','canteen-settlement','fas fa-money-check','fa','canteen_settlement.index','canteen-settlement','sidebar',4, 1, 1, 'Canteen Settlement','Settlement',     NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(191, 1, NULL, 190, 'alumni',              'Data Alumni',        'Alumni',     'alumni',              'fas fa-user-graduate','fa', 'alumni.index',              'alumni',              'sidebar', 1, 1, 1, 'Alumni',              'Data Alumni',        NOW(), NOW(), 1, 1),
(192, 1, NULL, 190, 'alumni-donation',     'Donasi Alumni',      'Donasi',     'alumni-donation',     'fas fa-donate',      'fa', 'alumni_donation.index',     'alumni-donation',     'sidebar', 2, 1, 1, 'Alumni Donation',     'Donasi Alumni',      NOW(), NOW(), 1, 1),
(193, 1, NULL, 190, 'tracer-study',        'Tracer Study',       'Tracer',     'tracer-study',        'fas fa-chart-line',  'fa', 'tracer_study.index',        'tracer-study',        'sidebar', 3, 1, 1, 'Tracer Study',        'Tracer Study',       NOW(), NOW(), 1, 1),
(194, 1, NULL, 190, 'graduation',          'Kelulusan',          'Lulus',      'graduation',          'fas fa-graduation-cap','fa','graduation.index',          'graduation',          'sidebar', 4, 1, 1, 'Graduation',          'Kelulusan',          NOW(), NOW(), 1, 1),
(195, 1, NULL, 190, 'yudisium',            'Yudisium',           'Yudisium',   'yudisium',            'fas fa-scroll',      'fa', 'yudisium.index',            'yudisium',            'sidebar', 5, 1, 1, 'Yudisium',            'Yudisium',           NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(201, 1, NULL, 200, 'reference-type',      'Tipe Referensi',     'Tipe',       'reference-type',      'fas fa-list',         'fa', 'reference_type.index',      'reference-type',      'sidebar', 1, 1, 1, 'Reference Type',      'Tipe Referensi',     NOW(), NOW(), 1, 1),
(202, 1, NULL, 200, 'reference-value',     'Nilai Referensi',    'Nilai',      'reference-value',     'fas fa-list-alt',     'fa', 'reference_value.index',     'reference-value',     'sidebar', 2, 1, 1, 'Reference Value',     'Nilai Referensi',    NOW(), NOW(), 1, 1),
(203, 1, NULL, 200, 'religion',            'Agama',              'Agama',      'religion',            'fas fa-pray',         'fa', 'religion.index',            'religion',            'sidebar', 3, 1, 1, 'Religion',            'Agama',              NOW(), NOW(), 1, 1),
(204, 1, NULL, 200, 'gender',              'Jenis Kelamin',      'Gender',     'gender',              'fas fa-venus-mars',   'fa', 'gender.index',              'gender',              'sidebar', 4, 1, 1, 'Gender',              'Jenis Kelamin',      NOW(), NOW(), 1, 1),
(205, 1, NULL, 200, 'education-level',     'Jenjang Pendidikan', 'Jenjang',    'education-level',     'fas fa-graduation-cap','fa','education_level.index',     'education-level',     'sidebar', 5, 1, 1, 'Education Level',     'Jenjang Pendidikan', NOW(), NOW(), 1, 1),
(206, 1, NULL, 200, 'blood-type',          'Golongan Darah',     'Darah',      'blood-type',          'fas fa-tint',         'fa', 'blood_type.index',          'blood-type',          'sidebar', 6, 1, 1, 'Blood Type',          'Golongan Darah',     NOW(), NOW(), 1, 1),
(207, 1, NULL, 200, 'currency',            'Mata Uang',          'Mata Uang',  'currency',            'fas fa-money-bill',   'fa', 'currency.index',            'currency',            'sidebar', 7, 1, 1, 'Currency',            'Mata Uang',          NOW(), NOW(), 1, 1),
(208, 1, NULL, 200, 'uom',                 'Satuan',             'Satuan',     'uom',                 'fas fa-ruler',        'fa', 'unit_of_measurement.index', 'uom',                 'sidebar', 8, 1, 1, 'Unit of Measurement','Satuan',             NOW(), NOW(), 1, 1);

INSERT INTO menus (id, organization_id, module_id, parent_id, name, title, flyout_title, slug, icon, icon_type, route, url, type, sort_order, is_active, is_publish, en_text, id_text, created_at, updated_at, created_by, updated_by) VALUES
(211, 1, NULL, 210, 'organization',        'Organisasi',          'Organisasi',  'organization',        'fas fa-building',     'fa', 'organization.index',        'organization',        'sidebar', 1, 1, 1, 'Organization',        'Organisasi',         NOW(), NOW(), 1, 1),
(212, 1, NULL, 210, 'school',              'Sekolah',             'Sekolah',     'school',              'fas fa-school',       'fa', 'school.index',              'school',              'sidebar', 2, 1, 1, 'School',              'Sekolah',            NOW(), NOW(), 1, 1),
(213, 1, NULL, 210, 'building',            'Gedung',              'Gedung',      'building',            'fas fa-building',     'fa', 'building.index',            'building',            'sidebar', 3, 1, 1, 'Building',            'Gedung',             NOW(), NOW(), 1, 1),
(214, 1, NULL, 210, 'floor',               'Lantai',              'Lantai',      'floor',               'fas fa-layer-group',  'fa', 'floor.index',               'floor',               'sidebar', 4, 1, 1, 'Floor',               'Lantai',             NOW(), NOW(), 1, 1),
(215, 1, NULL, 210, 'room',                'Ruang',               'Ruang',       'room',                'fas fa-door-open',    'fa', 'room.index',                'room',                'sidebar', 5, 1, 1, 'Room',                'Ruang',              NOW(), NOW(), 1, 1),
(216, 1, NULL, 210, 'facility',            'Fasilitas',           'Fasilitas',   'facility',            'fas fa-swimming-pool','fa', 'facility.index',            'facility',            'sidebar', 6, 1, 1, 'Facility',            'Fasilitas',          NOW(), NOW(), 1, 1),
(217, 1, NULL, 210, 'announcement',        'Pengumuman',          'Pengumuman',  'announcement',        'fas fa-bullhorn',     'fa', 'announcement.index',        'announcement',        'sidebar', 7, 1, 1, 'Announcement',        'Pengumuman',         NOW(), NOW(), 1, 1),
(218, 1, NULL, 210, 'notification-template','Template Notifikasi','Notifikasi',  'notification-template','fas fa-bell',        'fa', 'notification_template.index','notification-template','sidebar', 8, 1, 1, 'Notification Template','Template Notifikasi',NOW(), NOW(), 1, 1),
(219, 1, NULL, 210, 'document-type',       'Jenis Dokumen',       'Dokumen',     'document-type',       'fas fa-file-alt',     'fa', 'document_type.index',       'document-type',       'sidebar', 9, 1, 1, 'Document Type',       'Jenis Dokumen',      NOW(), NOW(), 1, 1),
(2110,1, NULL, 210, 'workflow',            'Workflow',            'Workflow',    'workflow',            'fas fa-project-diagram','fa','workflow_definition.index','workflow',            'sidebar', 10,1, 1, 'Workflow',            'Workflow',           NOW(), NOW(), 1, 1),
(2111,1, NULL, 210, 'audit-trail',         'Audit Trail',         'Audit',       'audit-trail',         'fas fa-history',      'fa', 'audit_trail.index',         'audit-trail',         'sidebar', 11,1, 1, 'Audit Trail',         'Audit Trail',        NOW(), NOW(), 1, 1),
(2112,1, NULL, 210, 'system-log',          'Log Sistem',          'Log',         'system-log',          'fas fa-file-code',    'fa', 'system_log.index',          'system-log',          'sidebar', 12,1, 1, 'System Log',          'Log Sistem',         NOW(), NOW(), 1, 1),
(2113,1, NULL, 210, 'api-rate-limit',      'API Rate Limit',      'API',         'api-rate-limit',      'fas fa-tachometer-alt','fa','api_rate_limit.index',      'api-rate-limit',      'sidebar', 13,1, 1, 'API Rate Limit',      'API Rate Limit',     NOW(), NOW(), 1, 1),
(2114,1, NULL, 210, 'module',              'Modul',               'Modul',       'module',              'fas fa-cubes',        'fa', 'module.index',              'module',              'sidebar', 14,1, 1, 'Module',              'Modul',              NOW(), NOW(), 1, 1);

SET FOREIGN_KEY_CHECKS = 1;


SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE menu_role;

-- ============================================================
-- SUPER ADMIN (role_id = 1) → SEMUA MENU
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 1, NOW(), NOW() FROM menus;

-- ============================================================
-- ADMIN YAYASAN (role_id = 2) → hampir semua kecuali settings sensitif
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 2, NOW(), NOW() FROM menus
WHERE id NOT IN (2113); -- kecuali API Rate Limit

-- ============================================================
-- KEPALA SEKOLAH (role_id = 3) → dashboard, akademik, kesiswaan, penilaian, laporan
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at) VALUES
(10, 3, NOW(), NOW()), (30, 3, NOW(), NOW()), (40, 3, NOW(), NOW()),
(50, 3, NOW(), NOW()), (70, 3, NOW(), NOW()), (80, 3, NOW(), NOW()),
(190, 3, NOW(), NOW()),
(31, 3, NOW(), NOW()), (32, 3, NOW(), NOW()), (33, 3, NOW(), NOW()),
(41, 3, NOW(), NOW()), (47, 3, NOW(), NOW()), (48, 3, NOW(), NOW()),
(49, 3, NOW(), NOW()), (410, 3, NOW(), NOW()),
(59, 3, NOW(), NOW()), (510, 3, NOW(), NOW());

-- ============================================================
-- ADMIN AKADEMIK (role_id = 4) → semua akademik & penilaian
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 4, NOW(), NOW() FROM menus
WHERE id IN (10, 30, 40, 50, 60, 70, 200) 
   OR parent_id IN (30, 40, 50, 60, 70);

-- ============================================================
-- GURU (role_id = 5) → pengajaran, penilaian, absensi
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at) VALUES
(10, 5, NOW(), NOW()), (50, 5, NOW(), NOW()), (60, 5, NOW(), NOW()),
(70, 5, NOW(), NOW()),
(51, 5, NOW(), NOW()), (54, 5, NOW(), NOW()), (59, 5, NOW(), NOW()),
(61, 5, NOW(), NOW()), (66, 5, NOW(), NOW()), (67, 5, NOW(), NOW()),
(71, 5, NOW(), NOW());

-- ============================================================
-- WALI KELAS (role_id = 6) → tambahan dari guru
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at) VALUES
(10, 6, NOW(), NOW()), (40, 6, NOW(), NOW()), (50, 6, NOW(), NOW()),
(70, 6, NOW(), NOW()),
(41, 6, NOW(), NOW()), (47, 6, NOW(), NOW()), (59, 6, NOW(), NOW()),
(71, 6, NOW(), NOW());

-- ============================================================
-- GURU BK (role_id = 7) → konseling & kedisiplinan
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at) VALUES
(10, 7, NOW(), NOW()), (40, 7, NOW(), NOW()),
(41, 7, NOW(), NOW()), (45, 7, NOW(), NOW()), (46, 7, NOW(), NOW());

-- ============================================================
-- SISWA (role_id = 8) → dashboard, KRS, nilai, perpustakaan
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at) VALUES
(10, 8, NOW(), NOW()), (50, 8, NOW(), NOW()), (60, 8, NOW(), NOW()),
(70, 8, NOW(), NOW()), (130, 8, NOW(), NOW()),
(59, 8, NOW(), NOW()), (61, 8, NOW(), NOW()), (66, 8, NOW(), NOW()),
(71, 8, NOW(), NOW()), (131, 8, NOW(), NOW()), (136, 8, NOW(), NOW());

-- ============================================================
-- ORANG TUA (role_id = 9) → lihat anak
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at) VALUES
(10, 9, NOW(), NOW()), (40, 9, NOW(), NOW()), (50, 9, NOW(), NOW()),
(70, 9, NOW(), NOW()), (90, 9, NOW(), NOW()),
(41, 9, NOW(), NOW()), (59, 9, NOW(), NOW()), (71, 9, NOW(), NOW()),
(93, 9, NOW(), NOW()), (94, 9, NOW(), NOW());

-- ============================================================
-- ADMIN KEUANGAN (role_id = 10)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 10, NOW(), NOW() FROM menus
WHERE id IN (10, 90, 100) OR parent_id IN (90, 100);

-- ============================================================
-- BENDAHARA (role_id = 11)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at) VALUES
(10, 11, NOW(), NOW()), (90, 11, NOW(), NOW()),
(91, 11, NOW(), NOW()), (92, 11, NOW(), NOW()), (93, 11, NOW(), NOW()),
(94, 11, NOW(), NOW()), (95, 11, NOW(), NOW()), (96, 11, NOW(), NOW()),
(99, 11, NOW(), NOW()), (910, 11, NOW(), NOW());

-- ============================================================
-- ADMIN HR (role_id = 13)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 13, NOW(), NOW() FROM menus
WHERE id IN (10, 110, 120) OR parent_id IN (110, 120);

-- ============================================================
-- PUSTAKAWAN (role_id = 15)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 15, NOW(), NOW() FROM menus
WHERE id = 10 OR id = 130 OR parent_id = 130;

-- ============================================================
-- PENGELOLA ASRAMA (role_id = 16)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 16, NOW(), NOW() FROM menus
WHERE id = 10 OR id = 140 OR parent_id = 140;

-- ============================================================
-- PENGELOLA INVENTORY (role_id = 18)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 18, NOW(), NOW() FROM menus
WHERE id = 10 OR id = 150 OR parent_id = 150;

-- ============================================================
-- ADMIN PPDB (role_id = 19)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 19, NOW(), NOW() FROM menus
WHERE id = 10 OR id = 80 OR parent_id = 80;

-- ============================================================
-- OPERATOR CBT (role_id = 20)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 20, NOW(), NOW() FROM menus
WHERE id = 10 OR id = 60 OR parent_id = 60;

-- ============================================================
-- OPERATOR DAPODIK (role_id = 21)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at) VALUES
(10, 21, NOW(), NOW()), (40, 21, NOW(), NOW()), (110, 21, NOW(), NOW());

-- ============================================================
-- KLINIK (role_id = 22)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 22, NOW(), NOW() FROM menus
WHERE id = 10 OR id = 170 OR parent_id = 170;

-- ============================================================
-- TRANSPORT (role_id = 23)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at)
SELECT id, 23, NOW(), NOW() FROM menus
WHERE id = 10 OR id = 160 OR parent_id = 160;

-- ============================================================
-- PIMPINAN (role_id = 28)
-- ============================================================
INSERT INTO menu_role (menu_id, role_id, created_at, updated_at) VALUES
(10, 28, NOW(), NOW()), (30, 28, NOW(), NOW()), (40, 28, NOW(), NOW()),
(50, 28, NOW(), NOW()), (90, 28, NOW(), NOW()), (100, 28, NOW(), NOW()),
(110, 28, NOW(), NOW()), (120, 28, NOW(), NOW()), (190, 28, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;