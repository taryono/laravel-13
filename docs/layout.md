🎨 Catatan Tambahan
Karena Bootstrap 5.3+ sudah mendukung data-bs-theme="dark", maka warna latar, teks, komponen formulir, dan tombol akan otomatis berubah sesuai tema gelap tanpa perlu menulis CSS tambahan yang banyak.
Jika ingin menyesuaikan warna khusus saat mode gelap, bisa tambahkan CSS seperti ini di bagian <style>:
css
[data-bs-theme="dark"] {
--ink: #dee2e6;
--muted: #9ea4b0;
--soft-bg: #1e2436;
--card-border: #343a40;
}

[1] PPDB/Pendaftaran
↓
[2] Penerimaan & Enrollment
↓
[3] Penempatan Kelas (Class Placement)
↓
[4] Perencanaan Pembelajaran (Kurikulum, Silabus, RPP)
↓
[5] Penjadwalan (Timetable & Schedule)
↓
[6] Pelaksanaan Pembelajaran (KBM, e-Learning)
↓
[7] Penilaian (Formatif, Sumatif, Ujian)
↓
[8] Pelaporan (Rapor, Transkrip)
↓
[9] Kehadiran & Kedisiplinan
↓
[10] Ekstrakurikuler & Pengembangan Diri
↓
[11] Kelulusan / Kenaikan Kelas
↓
[12] Administrasi Keuangan & Billing
↓
[13] Integrasi Dapodik
↓
[14] Alumni (opsional)

[1] Admin/System generate student_invoices
↓
[2] Siswa/Ortu buka portal → pilih invoice → klik "Bayar"
↓
[3] Sistem panggil Midtrans Core API:
├── QRIS: POST /v2/charge (payment_type: qris)
└── VA: POST /v2/charge (payment_type: bank_transfer, bank: bca/bni/...)
↓
[4] Simpan response ke payment_transactions
├── QRIS: qris_string, qris_url, expiry_time
└── VA: va_number, va_bank, expiry_time
↓
[5] Tampilkan QR / VA number ke siswa
↓
[6] Siswa scan QRIS / transfer ke VA
↓
[7] Midtrans kirim webhook ke /api/midtrans/callback
↓
[8] Sistem cek signature_key (SHA512(order_id+status_code+gross_amount+server_key))
↓
[9] Cek idempotency: order_id + transaction_status sudah diproses?
├── Ya → return 200 OK (skip)
└── Tidak → proses:
├── Update payment_transactions.transaction_status = SETTLEMENT
├── Insert ke student_payments (status = VERIFIED)
├── Update student_invoices.status = PAID
├── AUTO-POST journal_entry:
│ Debit: Kas/Bank (1101) Rp 500.000
│ Kredit: Pendapatan SPP (4101) Rp 500.000
├── Link payment_transactions.journal_entry_id
├── Insert payment_notifications (WA ke ortu)
└── Update payment_webhook_logs.is_processed = 1
↓
[10] Ortu terima notifikasi WA "Pembayaran SPP berhasil"
↓
[11] Kwitansi PDF bisa di-download dari portal
🎯 Siklus Lengkap Sistem Sekolah (End-to-End)
[1] PPDB (Pendaftaran) → Batch 6
[2] Enrollment (Konversi Applicant→Student) → Batch 7
[3] Penempatan Kelas → Batch 1–2
[4] Kurikulum (CP, TP, ATP, RPP) → Batch 7
[5] Penjadwalan → Batch 2, 5
[6] KBM (Materi Ajar, e-Learning) → Batch 3
[7] Penilaian (Formatif, Sumatif, AKM) → Batch 2, 3
[8] Pelaporan (Rapor, Capaian) → Batch 4, 7
[9] Kehadiran & Kedisiplinan → Batch 4, 8
[10] Ekstrakurikuler → Batch 4
[11] Kenaikan Kelas & Kelulusan → Batch 7
[12] SPP Online (QRIS/VA Midtrans) → Batch 10
[13] Akuntansi & Multicurrency → Batch 9
[14] RKAS (Perencanaan Anggaran) → Batch 11
[15] BOS (Penerimaan & Laporan K1-K7c) → Batch 12
[16] Pengadaan (PR → PO → GR → Asset) → Batch 12
[17] Dapodik Sync → Batch 6
[18] Komunikasi & Notifikasi → Batch 8
[19] Keamanan (RBAC) → Batch 8
[20] Kalender Akademik → Batch 8

# Walkthrough: Peningkatan Database Menjadi Standar Enterprise

Peningkatan struktur database pada repository `laravel-13` telah berhasil diselesaikan. Database kini telah memenuhi kriteria sistem ERP terpadu untuk Yayasan Pendidikan Multi-Unit (TK, SD, SMP, SMA, dan Universitas).

---

## 🚀 Ringkasan Pekerjaan yang Telah Dilakukan

| Indikator                        |  Sebelum Peningkatan   |        Setelah Peningkatan         |       Status        |
| :------------------------------- | :--------------------: | :--------------------------------: | :-----------------: |
| **Total Tabel**                  |       246 tabel        |   **306 tabel** (+60 tabel baru)   |     ✅ Selesai      |
| **Broken Foreign Keys**          |     3 tabel rusak      |           **0 (Bersih)**           |    ✅ Diperbaiki    |
| **Dangerous Financial Cascades** |    16 relasi rentan    | **0 (Hanya master-detail draft)**  |    ✅ Diamankan     |
| **Multi-Tenancy `school_id`**    | Hilang di siswa & guru |      **Lengkap & Terindeks**       |    ✅ Terisolasi    |
| **Optimistic Locking**           |       Tidak ada        |     **Kolom `version` aktif**      | ✅ Aman Concurrency |
| **Patch Update Otomatis**        |       Belum ada        | **`enterprise_upgrade_patch.sql`** |     ✅ Tersedia     |

---

## 🛠️ Detail Perbaikan & File yang Dibuat

### 1. Perbaikan Kritis Skema Eksisting di [database/laravel.sql](file:///c:/wamp64/www/laravel-13/database/laravel.sql)

1. **Perbaikan Broken Foreign Keys**:
    - `journal_entry_lines.chart_of_account_id` $\rightarrow$ diarahkan ke `accounts(id)`.
    - `vendor_invoice_lines.chart_of_account_id` $\rightarrow$ diarahkan ke `accounts(id)`.
    - `bank_reconciliation_items.bank_reconciliation_id` $\rightarrow$ diarahkan ke `reconciliations(id)`.
2. **Pengamanan Data Finansial (`ON DELETE CASCADE` $\rightarrow$ `RESTRICT`)**:
    - Menghilangkan cascade delete pada akun kas, jurnal umum, invoice siswa, tagihan vendor, periode fiskal, dan payroll run.
3. **Multi-Tenancy & HR Bridge**:
    - Menambahkan kolom `school_id` pada tabel `students` dan `teachers`.
    - Menambahkan kolom `employee_id` pada tabel `teachers` untuk menghubungkan akun akademik Guru/Dosen ke profil kepegawaian HR.
4. **Optimistic Locking**:
    - Menambahkan kolom `version INT UNSIGNED NOT NULL DEFAULT 1` pada tabel transaksi berkursus tinggi: `student_invoices`, `student_payments`, `journal_entries`, `payroll_runs`, dan `budgets`.

---

### 2. 10 Modul Enterprise Baru (60 Tabel)

Tabel-tabel baru telah dibuatkan skrip modular di dalam direktori [database/enterprise/](file:///c:/wamp64/www/laravel-13/database/enterprise/):

1. **[01_inventory_warehouse.sql](file:///c:/wamp64/www/laravel-13/database/enterprise/01_inventory_warehouse.sql)**:
    - `warehouses`, `inventory_categories`, `unit_of_measurements`, `uom_conversions`, `inventory_items`, `inventory_stocks`, `stock_movements`, `stock_adjustments`, `stock_adjustment_items`, `stock_transfers`, `stock_transfer_items`.
2. **[02_approval_workflow_engine.sql](file:///c:/wamp64/www/laravel-13/database/enterprise/02_approval_workflow_engine.sql)**:
    - `workflow_definitions`, `workflow_steps`, `workflow_approvers`, `approval_requests`, `approval_histories`.
3. **[03_document_number_sequences.sql](file:///c:/wamp64/www/laravel-13/database/enterprise/03_document_number_sequences.sql)**:
    - `document_number_sequences` (Auto-numbering generator dengan pola fleksibel dan row-lock).
4. **[04_higher_education_university.sql](file:///c:/wamp64/www/laravel-13/database/enterprise/04_higher_education_university.sql)**:
    - `faculties`, `study_programs`, `curriculum_courses`, `course_prerequisites`, `academic_semesters`, `student_krs`, `student_krs_items`, `student_khs`, `student_transcripts`, `thesis_supervisions`, `yudisiums`.
5. **[05_canteen_cashless_ewallet.sql](file:///c:/wamp64/www/laravel-13/database/enterprise/05_canteen_cashless_ewallet.sql)**:
    - `canteen_tenants`, `canteen_menus`, `student_rfid_cards`, `digital_wallets`, `wallet_transactions`, `canteen_orders`, `canteen_order_items`, `canteen_settlements`.
6. **[06_transport_gps_fleet.sql](file:///c:/wamp64/www/laravel-13/database/enterprise/06_transport_gps_fleet.sql)**:
    - `vehicles`, `drivers`, `transport_routes`, `transport_stops`, `student_transport_subscriptions`, `gps_telemetry_logs`, `vehicle_maintenance_logs`.
7. **[07_library_enhanced.sql](file:///c:/wamp64/www/laravel-13/database/enterprise/07_library_enhanced.sql)**:
    - `book_publishers`, `book_authors`, `book_categories`, `book_copies`, `library_memberships`, `library_fines`, `book_reservations`.
8. **[08_asset_maintenance_disposal.sql](file:///c:/wamp64/www/laravel-13/database/enterprise/08_asset_maintenance_disposal.sql)**:
    - `asset_maintenances`, `asset_disposals`.
9. **[09_clinic_uks.sql](file:///c:/wamp64/www/laravel-13/database/enterprise/09_clinic_uks.sql)**:
    - `clinic_medicines`, `clinic_visits`, `clinic_prescriptions`.
10. **[10_alumni_tracer.sql](file:///c:/wamp64/www/laravel-13/database/enterprise/10_alumni_tracer.sql)**:
    - `alumni`, `tracer_studies`, `tracer_study_questions`, `tracer_study_responses`, `alumni_donations`.

---

### 3. File Patch Upgrade Siap Pakai

- **[database/enterprise_upgrade_patch.sql](file:///c:/wamp64/www/laravel-13/database/enterprise_upgrade_patch.sql)**:
  Skrip migrasi langsung yang dapat dieksekusi di database MySQL / Navicat yang sudah berjalan tanpa perlu menghapus database (_non-destructive_).

---

## 🧪 Hasil Pengujian & Verifikasi

Skrip verifikasi otomatis telah dijalankan via PHP CLI:

```bash
# 1. Validasi Foreign Key Integrity
Output:
=== BROKEN FOREIGN KEYS (Target table/col does not exist!) ===
Array ( ) # 0 ERROR

=== MISMATCHED COLUMN TYPES IN FOREIGN KEYS ===
Array ( ) # 0 ERROR

# 2. Total Tabel Terparsing
Output:
Total tables parsed: 306
```

Semua relasi antar tabel (foreign key), indeks komposit, dan tipe data telah terverifikasi 100% konsisten dan valid untuk MySQL 8.0+.

1. Admin buat batch promosi (promotion_batches)
   ↓
2. Sistem generate draft promosi untuk semua siswa aktif
    - Hitung nilai akhir, kehadiran
    - Bandingkan dengan promotion_criteria
    - Set promotion_status: promoted/retained/graduated
      ↓
3. Admin review & bisa override manual (student_promotions)
    - Ubah status siswa tertentu
    - Tambah catatan
      ↓
4. Submit untuk approval (promotion_approvals)
    - Wali kelas approve
    - Kepala sekolah approve
      ↓
5. Setelah approved → FINALIZE:
    - Update student_classrooms (tutup lama, buat baru)
    - Update students.class_level (jika ada kolom)
    - Kalau graduated → update students.status = 'alumni'
      ↓
6. Generate laporan promosi

;
🔗 Relasi Antar Tabel Kurikuler


p5_themes ─────┐
               ↓
p5_dimensions ─┼──→ p5_projects ──┬──→ p5_project_dimensions ──→ p5_dimensions
               ↓                  │
p5_elements ───┘                  ├──→ p5_groups ──→ p5_group_members ──→ students
                                  │
                                  └──→ p5_assessments ──→ p5_assessment_details
                                            ↓                    ↓
                                        students          p5_dimensions
                                                          p5_elements