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
    └── VA:   POST /v2/charge (payment_type: bank_transfer, bank: bca/bni/...)
    ↓
[4] Simpan response ke payment_transactions
    ├── QRIS: qris_string, qris_url, expiry_time
    └── VA:   va_number, va_bank, expiry_time
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
    ├── Ya  → return 200 OK (skip)
    └── Tidak → proses:
        ├── Update payment_transactions.transaction_status = SETTLEMENT
        ├── Insert ke student_payments (status = VERIFIED)
        ├── Update student_invoices.status = PAID
        ├── AUTO-POST journal_entry:
        │     Debit:  Kas/Bank (1101)          Rp 500.000
        │     Kredit: Pendapatan SPP (4101)    Rp 500.000
        ├── Link payment_transactions.journal_entry_id
        ├── Insert payment_notifications (WA ke ortu)
        └── Update payment_webhook_logs.is_processed = 1
    ↓
[10] Ortu terima notifikasi WA "Pembayaran SPP berhasil"
    ↓
[11] Kwitansi PDF bisa di-download dari portal
🎯 Siklus Lengkap Sistem Sekolah (End-to-End)
[1]  PPDB (Pendaftaran)                    → Batch 6
[2]  Enrollment (Konversi Applicant→Student) → Batch 7
[3]  Penempatan Kelas                      → Batch 1–2
[4]  Kurikulum (CP, TP, ATP, RPP)          → Batch 7
[5]  Penjadwalan                           → Batch 2, 5
[6]  KBM (Materi Ajar, e-Learning)         → Batch 3
[7]  Penilaian (Formatif, Sumatif, AKM)    → Batch 2, 3
[8]  Pelaporan (Rapor, Capaian)            → Batch 4, 7
[9]  Kehadiran & Kedisiplinan              → Batch 4, 8
[10] Ekstrakurikuler                       → Batch 4
[11] Kenaikan Kelas & Kelulusan            → Batch 7
[12] SPP Online (QRIS/VA Midtrans)         → Batch 10
[13] Akuntansi & Multicurrency             → Batch 9
[14] RKAS (Perencanaan Anggaran)           → Batch 11
[15] BOS (Penerimaan & Laporan K1-K7c)     → Batch 12
[16] Pengadaan (PR → PO → GR → Asset)      → Batch 12
[17] Dapodik Sync                          → Batch 6
[18] Komunikasi & Notifikasi               → Batch 8
[19] Keamanan (RBAC)                       → Batch 8
[20] Kalender Akademik                     → Batch 8