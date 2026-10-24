# Dokumentasi Modul Aplikasi (Berdasarkan Skema Database)

> Dokumen ini memetakan seluruh tabel yang terdapat dalam skema basis data [`database/laravel-29-09-2026-2.sql`](file:///c:/wamp64/www/laravel-13/database/laravel-29-09-2026-2.sql) ke arsitektur modul modular [`app/Modules`](file:///c:/wamp64/www/laravel-13/app/Modules).

## 📊 Ringkasan Statistik Modul & Tabel

| Indikator | Jumlah | Persentase | Keterangan |
|---|---|---|---|
| **Total Tabel Database** | **419 tabel** | 100% | Tabel terdaftar dalam file SQL |
| **✅ Modul Sudah Ada** | **400 modul** | 95.5% | Sudah memiliki folder dan controller di `app/Modules/` |
| **⏳ Modul Belum Ada** | **0 tabel** | 0% | Tabel bisnis yang dapat digenerate modul CRUD-nya |
| **🔗 Tabel Pivot / Relasi** | **10 tabel** | 2.4% | Tabel penghubung *many-to-many* (tanpa modul mandiri) |
| **⚙️ Tabel Sistem Laravel** | **8 tabel** | 1.9% | Dikelola otomatis oleh engine core Laravel |
| **💾 Tabel Backup** | **1 tabel** | 0.2% | Tabel arsip / cadangan data |

> [!TIP]
> Untuk men-generate modul yang berstatus **⏳ Belum Ada**, Anda dapat menggunakan antarmuka **CRUD Generator** di modul [CrudGenerator](file:///c:/wamp64/www/laravel-13/app/Modules/CrudGenerator) atau menjalankan perintah artisan:
> ```bash
> php artisan make:crud-yajra {NamaModul} --columns={daftar_kolom}
> ```

---

## 1. Keuangan, Akuntansi, RKAS, BOS & Pembayaran (101 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `academic_registration_fees` | [`AcademicRegistrationFee`](file:///c:/wamp64/www/laravel-13/app/Modules/AcademicRegistrationFee) | ✅ Sudah Ada | Pengaturan biaya formulir registrasi tahun ajaran |
| 2 | `account_categories` | [`AccountCategory`](file:///c:/wamp64/www/laravel-13/app/Modules/AccountCategory) | ✅ Sudah Ada | Kategori klasifikasi bagan akun (COA) |
| 3 | `account_mappings` | [`AccountMapping`](file:///c:/wamp64/www/laravel-13/app/Modules/AccountMapping) | ✅ Sudah Ada | Pemetaan akun otomatis untuk transaksi jurnal |
| 4 | `accounting_periods` | [`AccountingPeriod`](file:///c:/wamp64/www/laravel-13/app/Modules/AccountingPeriod) | ✅ Sudah Ada | Periode akuntansi pembukuan dan tutup buku |
| 5 | `accounts` | [`Account`](file:///c:/wamp64/www/laravel-13/app/Modules/Account) | ✅ Sudah Ada | Bagan akun perkiraan (Chart of Accounts) |
| 6 | `admission_registration_fees` | [`AdmissionRegistrationFee`](file:///c:/wamp64/www/laravel-13/app/Modules/AdmissionRegistrationFee) | ✅ Sudah Ada | Tarif biaya pendaftaran PPDB / PMB per gelombang |
| 7 | `bank_accounts` | [`BankAccount`](file:///c:/wamp64/www/laravel-13/app/Modules/BankAccount) | ✅ Sudah Ada | Rekening kas & bank institusi |
| 8 | `bank_mutation_imports` | [`BankMutationImport`](file:///c:/wamp64/www/laravel-13/app/Modules/BankMutationImport) | ✅ Sudah Ada | Riwayat impor mutasi file CSV/Excel bank |
| 9 | `bank_mutations` | [`BankMutation`](file:///c:/wamp64/www/laravel-13/app/Modules/BankMutation) | ✅ Sudah Ada | Data mutasi rekening koran perbankan |
| 10 | `bank_reconciliation_items` | [`BankReconciliationItem`](file:///c:/wamp64/www/laravel-13/app/Modules/BankReconciliationItem) | ✅ Sudah Ada | Item pencocokan rekonsiliasi saldo bank |
| 11 | `bank_transactions` | [`BankTransaction`](file:///c:/wamp64/www/laravel-13/app/Modules/BankTransaction) | ✅ Sudah Ada | Transaksi keluar/masuk buku bank |
| 12 | `bos_allocations` | [`BosAllocation`](file:///c:/wamp64/www/laravel-13/app/Modules/BosAllocation) | ✅ Sudah Ada | Alokasi penetapan dana BOS per siswa/tahap |
| 13 | `bos_components` | [`BosComponent`](file:///c:/wamp64/www/laravel-13/app/Modules/BosComponent) | ✅ Sudah Ada | Komponen pembiayaan pos penggunaan BOS |
| 14 | `bos_periods` | [`BosPeriod`](file:///c:/wamp64/www/laravel-13/app/Modules/BosPeriod) | ✅ Sudah Ada | Periode triwulan/semester pencairan BOS |
| 15 | `bos_realizations` | [`BosRealization`](file:///c:/wamp64/www/laravel-13/app/Modules/BosRealization) | ✅ Sudah Ada | Realisasi pembelanjaan penggunaan dana BOS |
| 16 | `bos_receipts` | [`BosReceipt`](file:///c:/wamp64/www/laravel-13/app/Modules/BosReceipt) | ✅ Sudah Ada | Kuitansi & bukti penerimaan transfer dana BOS |
| 17 | `bos_report_items` | [`BosReportItem`](file:///c:/wamp64/www/laravel-13/app/Modules/BosReportItem) | ✅ Sudah Ada | Rincian nota transaksi pada laporan BOS |
| 18 | `bos_reports` | [`BosReport`](file:///c:/wamp64/www/laravel-13/app/Modules/BosReport) | ✅ Sudah Ada | Laporan pertanggungjawaban (LPJ) BOS |
| 19 | `bos_usage_plans` | [`BosUsagePlan`](file:///c:/wamp64/www/laravel-13/app/Modules/BosUsagePlan) | ✅ Sudah Ada | Rencana Kerja Anggaran Sekolah (RKAS) BOS |
| 20 | `budget_details` | [`BudgetDetail`](file:///c:/wamp64/www/laravel-13/app/Modules/BudgetDetail) | ✅ Sudah Ada | Rincian alokasi anggaran mata anggaran |
| 21 | `budgets` | [`Budget`](file:///c:/wamp64/www/laravel-13/app/Modules/Budget) | ✅ Sudah Ada | Master plafon anggaran pendapatan dan belanja |
| 22 | `chart_of_accounts` | [`ChartOfAccount`](file:///c:/wamp64/www/laravel-13/app/Modules/ChartOfAccount) | ✅ Sudah Ada | Daftar master akun buku besar akuntansi |
| 23 | `cost_centers` | [`CostCenter`](file:///c:/wamp64/www/laravel-13/app/Modules/CostCenter) | ✅ Sudah Ada | Pusat biaya (Cost Center) per unit/divisi |
| 24 | `currencies` | [`Currency`](file:///c:/wamp64/www/laravel-13/app/Modules/Currency) | ✅ Sudah Ada | Master mata uang dan simbol moneter |
| 25 | `deduction_categories` | [`DeductionCategory`](file:///c:/wamp64/www/laravel-13/app/Modules/DeductionCategory) | ✅ Sudah Ada | Kategori jenis potongan honor/gaji |
| 26 | `deductions` | [`Deduction`](file:///c:/wamp64/www/laravel-13/app/Modules/Deduction) | ✅ Sudah Ada | Master jenis potongan keuangan pegawai |
| 27 | `digital_wallets` | [`DigitalWallet`](file:///c:/wamp64/www/laravel-13/app/Modules/DigitalWallet) | ✅ Sudah Ada | Dompet digital / saldo rekening virtual santri |
| 28 | `exchange_rates` | [`ExchangeRate`](file:///c:/wamp64/www/laravel-13/app/Modules/ExchangeRate) | ✅ Sudah Ada | Tabel kurs nilai tukar mata uang asing harian |
| 29 | `expense_categories` | [`ExpenseCategory`](file:///c:/wamp64/www/laravel-13/app/Modules/ExpenseCategory) | ✅ Sudah Ada | Kategori beban biaya operasional |
| 30 | `expense_items` | [`ExpenseItem`](file:///c:/wamp64/www/laravel-13/app/Modules/ExpenseItem) | ✅ Sudah Ada | Rincian item belanja pengeluaran kas |
| 31 | `expense_payments` | [`ExpensePayment`](file:///c:/wamp64/www/laravel-13/app/Modules/ExpensePayment) | ✅ Sudah Ada | Realisasi pengeluaran voucher kas/bank |
| 32 | `expenses` | [`Expense`](file:///c:/wamp64/www/laravel-13/app/Modules/Expense) | ✅ Sudah Ada | Pengajuan & pencatatan beban pengeluaran |
| 33 | `fee_masters` | [`FeeMaster`](file:///c:/wamp64/www/laravel-13/app/Modules/FeeMaster) | ✅ Sudah Ada | Master tarif tagihan pendidikan & iuran |
| 34 | `fee_structures` | [`FeeStructure`](file:///c:/wamp64/www/laravel-13/app/Modules/FeeStructure) | ✅ Sudah Ada | Struktur komponen tagihan per angkatan/kelas |
| 35 | `finance_audit_logs` | [`FinanceAuditLog`](file:///c:/wamp64/www/laravel-13/app/Modules/FinanceAuditLog) | ✅ Sudah Ada | Audit jejak transaksi jurnal & kasir keuangan |
| 36 | `financial_aid_requests` | [`FinancialAidRequest`](file:///c:/wamp64/www/laravel-13/app/Modules/FinancialAidRequest) | ✅ Sudah Ada | Pengajuan keringanan / beasiswa SPP santri |
| 37 | `financial_ratios` | [`FinancialRatio`](file:///c:/wamp64/www/laravel-13/app/Modules/FinancialRatio) | ✅ Sudah Ada | Indikator rasio kesehatan keuangan institusi |
| 38 | `fiscal_period_closings` | [`FiscalPeriodClosing`](file:///c:/wamp64/www/laravel-13/app/Modules/FiscalPeriodClosing) | ✅ Sudah Ada | Riwayat proses tutup buku akhir tahun/bulan |
| 39 | `fiscal_periods` | [`FiscalPeriod`](file:///c:/wamp64/www/laravel-13/app/Modules/FiscalPeriod) | ✅ Sudah Ada | Tahun buku dan periode laporan keuangan |
| 40 | `fiscal_years` | [`FiscalYear`](file:///c:/wamp64/www/laravel-13/app/Modules/FiscalYear) | ✅ Sudah Ada | Tahun fiskal anggaran institusi |
| 41 | `invoice_items` | [`InvoiceItem`](file:///c:/wamp64/www/laravel-13/app/Modules/InvoiceItem) | ✅ Sudah Ada | Rincian item tagihan faktur |
| 42 | `invoice_payments` | [`InvoicePayment`](file:///c:/wamp64/www/laravel-13/app/Modules/InvoicePayment) | ✅ Sudah Ada | Penerimaan pembayaran faktur/invoice |
| 43 | `invoices` | [`Invoice`](file:///c:/wamp64/www/laravel-13/app/Modules/Invoice) | ✅ Sudah Ada | Dokumen invoice / faktur penagihan |
| 44 | `journal_details` | [`JournalDetail`](file:///c:/wamp64/www/laravel-13/app/Modules/JournalDetail) | ✅ Sudah Ada | Baris debit-kredit jurnal umum akuntansi |
| 45 | `journal_entries` | [`JournalEntry`](file:///c:/wamp64/www/laravel-13/app/Modules/JournalEntry) | ✅ Sudah Ada | Header bukti transaksi jurnal akuntansi |
| 46 | `journal_entry_lines` | [`JournalEntryLine`](file:///c:/wamp64/www/laravel-13/app/Modules/JournalEntryLine) | ✅ Sudah Ada | Rincian baris ayat jurnal akuntansi |
| 47 | `opening_balances` | [`OpeningBalance`](file:///c:/wamp64/www/laravel-13/app/Modules/OpeningBalance) | ✅ Sudah Ada | Saldo awal buku besar awal periode pembukuan |
| 48 | `payment_channels` | [`PaymentChannel`](file:///c:/wamp64/www/laravel-13/app/Modules/PaymentChannel) | ✅ Sudah Ada | Saluran pembayaran (VA, QRIS, Retail, Transfer) |
| 49 | `payment_gateways` | [`PaymentGateway`](file:///c:/wamp64/www/laravel-13/app/Modules/PaymentGateway) | ✅ Sudah Ada | Konfigurasi kredensial gateway pembayaran |
| 50 | `payment_notifications` | [`PaymentNotification`](file:///c:/wamp64/www/laravel-13/app/Modules/PaymentNotification) | ✅ Sudah Ada | Riwayat pengiriman notifikasi tagihan/lunas |
| 51 | `payment_requests` | [`PaymentRequest`](file:///c:/wamp64/www/laravel-13/app/Modules/PaymentRequest) | ✅ Sudah Ada | Permintaan pembayaran / klaim reimbursement |
| 52 | `payment_transactions` | [`PaymentTransaction`](file:///c:/wamp64/www/laravel-13/app/Modules/PaymentTransaction) | ✅ Sudah Ada | Pencatatan riwayat transaksi kasir & gateway |
| 53 | `payment_webhook_logs` | [`PaymentWebhookLog`](file:///c:/wamp64/www/laravel-13/app/Modules/PaymentWebhookLog) | ✅ Sudah Ada | Log callback notifikasi webhook dari gateway |
| 54 | `payroll_audit_logs` | [`PayrollAuditLog`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollAuditLog) | ✅ Sudah Ada | Audit log perhitungan & perubahan data gaji |
| 55 | `payroll_detail_components` | [`PayrollDetailComponent`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollDetailComponent) | ✅ Sudah Ada | Komponen penambah/pengurang slip gaji pegawai |
| 56 | `payroll_details` | [`PayrollDetail`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollDetail) | ✅ Sudah Ada | Rincian komponen slip gaji per pegawai |
| 57 | `payroll_finance_integrations` | [`PayrollFinanceIntegration`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollFinanceIntegration) | ✅ Sudah Ada | Integrasi jurnal otomatis payroll ke akuntansi |
| 58 | `payroll_integrations` | [`PayrollIntegration`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollIntegration) | ✅ Sudah Ada | Konfigurasi integrasi penggajian perbankan |
| 59 | `payroll_notifications` | [`PayrollNotification`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollNotification) | ✅ Sudah Ada | Notifikasi pengiriman slip gaji pegawai |
| 60 | `payroll_payment_batches` | [`PayrollPaymentBatch`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollPaymentBatch) | ✅ Sudah Ada | Batch ekspor payroll transfer massal bank |
| 61 | `payroll_payments` | [`PayrollPayment`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollPayment) | ✅ Sudah Ada | Eksekusi pembayaran gaji & transfer bank |
| 62 | `payroll_periods` | [`PayrollPeriod`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollPeriod) | ✅ Sudah Ada | Periode siklus penggajian bulanan |
| 63 | `payroll_permissions` | [`PayrollPermission`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollPermission) | ✅ Sudah Ada | Hak akses khusus menu payroll |
| 64 | `payroll_runs` | [`PayrollRun`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollRun) | ✅ Sudah Ada | Proses generate / kalkulasi penggajian |
| 65 | `payroll_settings` | [`PayrollSetting`](file:///c:/wamp64/www/laravel-13/app/Modules/PayrollSetting) | ✅ Sudah Ada | Parameter perhitungan gaji pokok, tunjangan & PPh21 |
| 66 | `payslips` | [`Payslip`](file:///c:/wamp64/www/laravel-13/app/Modules/Payslip) | ✅ Sudah Ada | Slip gaji elektronik pegawai |
| 67 | `ptkp_statuses` | [`PtkpStatus`](file:///c:/wamp64/www/laravel-13/app/Modules/PtkpStatus) | ✅ Sudah Ada | Tabel status PTKP pajak penghasilan PPh 21 |
| 68 | `qris_transactions` | [`QrisTransaction`](file:///c:/wamp64/www/laravel-13/app/Modules/QrisTransaction) | ✅ Sudah Ada | Riwayat transaksi pembayaran QRIS dinamis |
| 69 | `reconciliation_details` | [`ReconciliationDetail`](file:///c:/wamp64/www/laravel-13/app/Modules/ReconciliationDetail) | ✅ Sudah Ada | Rincian butir rekonsiliasi mutasi kas/bank |
| 70 | `reconciliations` | [`Reconciliation`](file:///c:/wamp64/www/laravel-13/app/Modules/Reconciliation) | ✅ Sudah Ada | Dokumen berita acara rekonsiliasi bank |
| 71 | `rkas_activities` | [`RkasActivity`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasActivity) | ✅ Sudah Ada | Daftar kegiatan program kerja RKAS |
| 72 | `rkas_approvals` | [`RkasApproval`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasApproval) | ✅ Sudah Ada | Riwayat verifikasi & persetujuan dokumen RKAS |
| 73 | `rkas_budgets` | [`RkasBudget`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasBudget) | ✅ Sudah Ada | Alokasi plafon rencana anggaran RKAS |
| 74 | `rkas_components` | [`RkasComponent`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasComponent) | ✅ Sudah Ada | Komponen pembiayaan RKAS per standar |
| 75 | `rkas_documents` | [`RkasDocument`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasDocument) | ✅ Sudah Ada | Berkas lampiran dokumen pendukung RKAS |
| 76 | `rkas_items` | [`RkasItem`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasItem) | ✅ Sudah Ada | Rincian belanja barang/jasa dalam RKAS |
| 77 | `rkas_programs` | [`RkasProgram`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasProgram) | ✅ Sudah Ada | Program kerja strategis institusi di RKAS |
| 78 | `rkas_realizations` | [`RkasRealization`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasRealization) | ✅ Sudah Ada | Realisasi penyerapan anggaran belanja RKAS |
| 79 | `rkas_report_items` | [`RkasReportItem`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasReportItem) | ✅ Sudah Ada | Item detail laporan capaian realisasi RKAS |
| 80 | `rkas_reports` | [`RkasReport`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasReport) | ✅ Sudah Ada | Laporan evaluasi serapan RKAS semester/tahunan |
| 81 | `rkas_revisions` | [`RkasRevision`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasRevision) | ✅ Sudah Ada | Riwayat revisi perubahan anggaran RKAS |
| 82 | `rkas_sources` | [`RkasSource`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasSource) | ✅ Sudah Ada | Sumber pendanaan anggaran RKAS (BOS, Yayasan, SPP) |
| 83 | `rkas_standards` | [`RkasStandard`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasStandard) | ✅ Sudah Ada | Standar Nasional Pendidikan (SNP) dalam RKAS |
| 84 | `rkas_sub_activities` | [`RkasSubActivity`](file:///c:/wamp64/www/laravel-13/app/Modules/RkasSubActivity) | ✅ Sudah Ada | Sub-kegiatan rincian implementasi RKAS |
| 85 | `salary_components` | [`SalaryComponent`](file:///c:/wamp64/www/laravel-13/app/Modules/SalaryComponent) | ✅ Sudah Ada | Master komponen honor, tunjangan & potongan |
| 86 | `salary_structure_components` | [`SalaryStructureComponent`](file:///c:/wamp64/www/laravel-13/app/Modules/SalaryStructureComponent) | ✅ Sudah Ada | Komponen penyusun struktur skala upah |
| 87 | `salary_structures` | [`SalaryStructure`](file:///c:/wamp64/www/laravel-13/app/Modules/SalaryStructure) | ✅ Sudah Ada | Struktur skala gaji berdasar golongan/masa kerja |
| 88 | `student_installments` | [`StudentInstallment`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentInstallment) | ✅ Sudah Ada | Rencana & realisasi cicilan tagihan santri |
| 89 | `student_invoice_batches` | [`StudentInvoiceBatch`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentInvoiceBatch) | ✅ Sudah Ada | Generate massal tagihan bulanan siswa |
| 90 | `student_invoices` | [`StudentInvoice`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentInvoice) | ✅ Sudah Ada | Faktur tagihan biaya pendidikan santri |
| 91 | `student_payment_batches` | [`StudentPaymentBatch`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentPaymentBatch) | ✅ Sudah Ada | Batch rekonsiliasi pembayaran santri |
| 92 | `student_payments` | [`StudentPayment`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentPayment) | ✅ Sudah Ada | Pencatatan pembayaran SPP/biaya santri |
| 93 | `tax_liabilities` | [`TaxLiability`](file:///c:/wamp64/www/laravel-13/app/Modules/TaxLiability) | ✅ Sudah Ada | Kewajiban setor pajak institusi |
| 94 | `tax_payments` | [`TaxPayment`](file:///c:/wamp64/www/laravel-13/app/Modules/TaxPayment) | ✅ Sudah Ada | Bukti pembayaran / NTPN setoran pajak |
| 95 | `tax_settings` | [`TaxSetting`](file:///c:/wamp64/www/laravel-13/app/Modules/TaxSetting) | ✅ Sudah Ada | Pengaturan tarif pajak PPh 21, 23 & PPN |
| 96 | `tax_types` | [`TaxType`](file:///c:/wamp64/www/laravel-13/app/Modules/TaxType) | ✅ Sudah Ada | Master jenis pajak penghasilan & pertambahan nilai |
| 97 | `vendor_invoice_lines` | [`VendorInvoiceLine`](file:///c:/wamp64/www/laravel-13/app/Modules/VendorInvoiceLine) | ✅ Sudah Ada | Item tagihan faktur belanja vendor |
| 98 | `vendor_invoices` | [`VendorInvoice`](file:///c:/wamp64/www/laravel-13/app/Modules/VendorInvoice) | ✅ Sudah Ada | Faktur tagihan pembelian dari rekanan/vendor |
| 99 | `vendor_payments` | [`VendorPayment`](file:///c:/wamp64/www/laravel-13/app/Modules/VendorPayment) | ✅ Sudah Ada | Pencatatan pengeluaran bayar tagihan vendor |
| 100 | `virtual_accounts` | [`VirtualAccount`](file:///c:/wamp64/www/laravel-13/app/Modules/VirtualAccount) | ✅ Sudah Ada | Nomor VA perbankan siswa untuk pembayaran SPP |
| 101 | `wallet_transactions` | [`WalletTransaction`](file:///c:/wamp64/www/laravel-13/app/Modules/WalletTransaction) | ✅ Sudah Ada | Mutasi topup & belanja kartu pintar/e-wallet |

---

## 2. Akademik, Kurikulum, Pembelajaran & Penilaian Rapor (77 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `academic_calendar_events` | [`AcademicCalendarEvent`](file:///c:/wamp64/www/laravel-13/app/Modules/AcademicCalendarEvent) | ✅ Sudah Ada | Agenda kalender pendidikan institusi |
| 2 | `academic_semesters` | [`AcademicSemester`](file:///c:/wamp64/www/laravel-13/app/Modules/AcademicSemester) | ✅ Sudah Ada | Data semester akademik berjalan (Ganjil/Genap) |
| 3 | `academic_years` | [`AcademicYear`](file:///c:/wamp64/www/laravel-13/app/Modules/AcademicYear) | ✅ Sudah Ada | Tahun ajaran akademik (misal: 2026/2027) |
| 4 | `assessment_components` | [`AssessmentComponent`](file:///c:/wamp64/www/laravel-13/app/Modules/AssessmentComponent) | ✅ Sudah Ada | Komponen pembobotan nilai (Tugas, UTS, UAS) |
| 5 | `assessment_methods` | [`AssessmentMethod`](file:///c:/wamp64/www/laravel-13/app/Modules/AssessmentMethod) | ✅ Sudah Ada | Metode asesmen (Tertulis, Praktik, Portofolio) |
| 6 | `assessment_rubrics` | [`AssessmentRubric`](file:///c:/wamp64/www/laravel-13/app/Modules/AssessmentRubric) | ✅ Sudah Ada | Rubrik penilaian kriteria capaian kompetensi |
| 7 | `assessment_types` | [`AssessmentType`](file:///c:/wamp64/www/laravel-13/app/Modules/AssessmentType) | ✅ Sudah Ada | Jenis asesmen (Formatif, Sumatif, Diagnostik) |
| 8 | `assessments` | [`Assessment`](file:///c:/wamp64/www/laravel-13/app/Modules/Assessment) | ✅ Sudah Ada | Data pelaksanaan asesmen pembelajaran |
| 9 | `attitude_assessments` | [`AttitudeAssessment`](file:///c:/wamp64/www/laravel-13/app/Modules/AttitudeAssessment) | ✅ Sudah Ada | Penilaian sikap spiritual & sosial (P5/Karakter) |
| 10 | `class_levels` | [`ClassLevel`](file:///c:/wamp64/www/laravel-13/app/Modules/ClassLevel) | ✅ Sudah Ada | Tingkat kelas / jenjang (Kelas 7, 8, 9, X, XI, XII) |
| 11 | `classrooms` | [`Classroom`](file:///c:/wamp64/www/laravel-13/app/Modules/Classroom) | ✅ Sudah Ada | Rombongan belajar (Rombel) kelas |
| 12 | `cognitive_levels` | [`CognitiveLevel`](file:///c:/wamp64/www/laravel-13/app/Modules/CognitiveLevel) | ✅ Sudah Ada | Tingkat kognitif taksonomi Bloom (C1-C6) |
| 13 | `course_prerequisites` | [`CoursePrerequisite`](file:///c:/wamp64/www/laravel-13/app/Modules/CoursePrerequisite) | ✅ Sudah Ada | Syarat kelulusan modul pembelajaran |
| 14 | `courses` | [`Course`](file:///c:/wamp64/www/laravel-13/app/Modules/Course) | ✅ Sudah Ada | Modul mata pelatihan kursus e-Learning |
| 15 | `curriculum_courses` | [`CurriculumCourse`](file:///c:/wamp64/www/laravel-13/app/Modules/CurriculumCourse) | ✅ Sudah Ada | Mata kuliah/pelajaran yang terikat kurikulum |
| 16 | `curriculum_learning_outcomes` | [`CurriculumLearningOutcome`](file:///c:/wamp64/www/laravel-13/app/Modules/CurriculumLearningOutcome) | ✅ Sudah Ada | Capaian Pembelajaran (CP) per kurikulum |
| 17 | `curriculum_objectives` | [`CurriculumObjective`](file:///c:/wamp64/www/laravel-13/app/Modules/CurriculumObjective) | ✅ Sudah Ada | Tujuan Pembelajaran (TP) pada kurikulum |
| 18 | `curriculum_phases` | [`CurriculumPhase`](file:///c:/wamp64/www/laravel-13/app/Modules/CurriculumPhase) | ✅ Sudah Ada | Fase pembelajaran Kurikulum Merdeka (Fase A - F) |
| 19 | `curriculum_structures` | [`CurriculumStructure`](file:///c:/wamp64/www/laravel-13/app/Modules/CurriculumStructure) | ✅ Sudah Ada | Struktur alokasi jam intrakurikuler dan P5 |
| 20 | `curriculum_subjects` | [`CurriculumSubject`](file:///c:/wamp64/www/laravel-13/app/Modules/CurriculumSubject) | ✅ Sudah Ada | Daftar mata pelajaran wajib/pilihan dalam kurikulum |
| 21 | `curriculums` | [`Curriculum`](file:///c:/wamp64/www/laravel-13/app/Modules/Curriculum) | ✅ Sudah Ada | Kurikulum pendidikan (Merdeka, K13, Pesantren, Cambridge) |
| 22 | `difficulty_levels` | [`DifficultyLevel`](file:///c:/wamp64/www/laravel-13/app/Modules/DifficultyLevel) | ✅ Sudah Ada | Tingkat kesukaran materi/soal (Mudah, Sedang, Sulit) |
| 23 | `education_levels` | [`EducationLevel`](file:///c:/wamp64/www/laravel-13/app/Modules/EducationLevel) | ✅ Sudah Ada | Jenjang pendidikan (KB, TK, SD, SMP, SMA, SMK, Univ) |
| 24 | `faculties` | [`Faculty`](file:///c:/wamp64/www/laravel-13/app/Modules/Faculty) | ✅ Sudah Ada | Fakultas perguruan tinggi |
| 25 | `grade_boundaries` | [`GradeBoundary`](file:///c:/wamp64/www/laravel-13/app/Modules/GradeBoundary) | ✅ Sudah Ada | Rentang interval batas konversi nilai (A, B, C, D) |
| 26 | `grade_scales` | [`GradeScale`](file:///c:/wamp64/www/laravel-13/app/Modules/GradeScale) | ✅ Sudah Ada | Skala sistem penilaian huruf dan indeks IPK |
| 27 | `grade_weight_configs` | [`GradeWeightConfig`](file:///c:/wamp64/www/laravel-13/app/Modules/GradeWeightConfig) | ✅ Sudah Ada | Bobot persentase nilai akhir per mata pelajaran |
| 28 | `grades` | [`Grade`](file:///c:/wamp64/www/laravel-13/app/Modules/Grade) | ✅ Sudah Ada | Rekapitulasi nilai mata pelajaran siswa |
| 29 | `graduations` | [`Graduation`](file:///c:/wamp64/www/laravel-13/app/Modules/Graduation) | ✅ Sudah Ada | Penetapan & nomor ijazah kelulusan siswa |
| 30 | `homeroom_teachers` | [`HomeroomTeacher`](file:///c:/wamp64/www/laravel-13/app/Modules/HomeroomTeacher) | ✅ Sudah Ada | Penugasan guru sebagai wali kelas |
| 31 | `learning_materials` | [`LearningMaterial`](file:///c:/wamp64/www/laravel-13/app/Modules/LearningMaterial) | ✅ Sudah Ada | Katalog modul ajar, slide & video pembelajaran |
| 32 | `learning_outcomes` | [`LearningOutcome`](file:///c:/wamp64/www/laravel-13/app/Modules/LearningOutcome) | ✅ Sudah Ada | Capaian Pembelajaran (CP) dan TP/ATP |
| 33 | `lesson_plans` | [`LessonPlan`](file:///c:/wamp64/www/laravel-13/app/Modules/LessonPlan) | ✅ Sudah Ada | Rencana Pelaksanaan Pembelajaran (RPP / Modul Ajar) |
| 34 | `lesson_progress` | [`LessonProgress`](file:///c:/wamp64/www/laravel-13/app/Modules/LessonProgress) | ✅ Sudah Ada | Monitoring ketuntasan materi pertemuan tatap muka |
| 35 | `lessons` | [`Lesson`](file:///c:/wamp64/www/laravel-13/app/Modules/Lesson) | ✅ Sudah Ada | Agenda sesi jam tatap muka pembelajaran |
| 36 | `mastery_tracking` | [`MasteryTracking`](file:///c:/wamp64/www/laravel-13/app/Modules/MasteryTracking) | ✅ Sudah Ada | Pemantauan ketuntasan belajar siswa (Kriteria Ketuntasan) |
| 37 | `periods` | [`Period`](file:///c:/wamp64/www/laravel-13/app/Modules/Period) | ✅ Sudah Ada | Master periode sesi akademik / triwulan |
| 38 | `promotion_batches` | [`PromotionBatch`](file:///c:/wamp64/www/laravel-13/app/Modules/PromotionBatch) | ✅ Sudah Ada | Batch eksekusi kelulusan dan kenaikan kelas siswa |
| 39 | `promotion_criteria` | [`PromotionCriterion`](file:///c:/wamp64/www/laravel-13/app/Modules/PromotionCriterion) | ✅ Sudah Ada | Kriteria syarat minimum kenaikan kelas (Nilai, Absensi) |
| 40 | `report_builder` | [`ReportBuilder`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportBuilder) | ✅ Sudah Ada | Studio perancang tata letak rapor visual |
| 41 | `report_builder_components` | [`ReportBuilderComponent`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportBuilderComponent) | ✅ Sudah Ada | Komponen widget drag-drop perancang rapor |
| 42 | `report_card_components` | [`ReportCardComponent`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportCardComponent) | ✅ Sudah Ada | Sub-komponen pengisi rapor siswa |
| 43 | `report_card_grades` | [`ReportCardGrade`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportCardGrade) | ✅ Sudah Ada | Nilai akhir capaian kompetensi dalam rapor |
| 44 | `report_card_notes` | [`ReportCardNote`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportCardNote) | ✅ Sudah Ada | Catatan deskripsi perkembangan dari wali kelas |
| 45 | `report_card_signatures` | [`ReportCardSignature`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportCardSignature) | ✅ Sudah Ada | Konfigurasi tanda tangan kepala sekolah & wali kelas |
| 46 | `report_card_template_curriculums` | [`ReportCardTemplateCurriculum`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportCardTemplateCurriculum) | ✅ Sudah Ada | Relasi kurikulum ke template cetak rapor |
| 47 | `report_card_template_sections` | [`ReportCardTemplateSection`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportCardTemplateSection) | ✅ Sudah Ada | Bagian layout (Halaman Judul, Nilai, Ekstrakurikuler) |
| 48 | `report_card_templates` | [`ReportCardTemplate`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportCardTemplate) | ✅ Sudah Ada | Template format cetak rapor fisik/PDF |
| 49 | `report_cards` | [`ReportCard`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportCard) | ✅ Sudah Ada | Dokumen hasil belajar rapor siswa per semester |
| 50 | `report_periods` | [`ReportPeriod`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportPeriod) | ✅ Sudah Ada | Penetapan tanggal pembagian rapor dan titimangsa |
| 51 | `report_templates` | [`ReportTemplate`](file:///c:/wamp64/www/laravel-13/app/Modules/ReportTemplate) | ✅ Sudah Ada | Master template cetak laporan akademik |
| 52 | `room_constraints` | [`RoomConstraint`](file:///c:/wamp64/www/laravel-13/app/Modules/RoomConstraint) | ✅ Sudah Ada | Aturan pembatasan jadwal penggunaan ruang kelas |
| 53 | `scheduled_reports` | [`ScheduledReport`](file:///c:/wamp64/www/laravel-13/app/Modules/ScheduledReport) | ✅ Sudah Ada | Otomasi generate laporan berkala terjadwal |
| 54 | `schedules` | [`Schedule`](file:///c:/wamp64/www/laravel-13/app/Modules/Schedule) | ✅ Sudah Ada | Jadwal pelajaran harian kelas |
| 55 | `student_assessments` | [`StudentAssessment`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentAssessment) | ✅ Sudah Ada | Rincian nilai asesmen individu siswa |
| 56 | `student_classrooms` | [`StudentClassroom`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentClassroom) | ✅ Sudah Ada | Riwayat penempatan rombel kelas siswa |
| 57 | `student_curriculums` | [`StudentCurriculum`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentCurriculum) | ✅ Sudah Ada | Kurikulum yang ditempuh oleh rombel siswa |
| 58 | `student_khs` | [`StudentKhs`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentKhs) | ✅ Sudah Ada | Kartu Hasil Studi (KHS) semesteran mahasiswa/santri |
| 59 | `student_krs` | [`StudentKrs`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentKrs) | ✅ Sudah Ada | Kartu Rencana Studi (KRS) registrasi matakuliah |
| 60 | `student_krs_items` | [`StudentKrsItem`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentKrsItem) | ✅ Sudah Ada | Mata pelajaran/kuliah yang diambil di formulir KRS |
| 61 | `student_learning_plans` | [`StudentLearningPlan`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentLearningPlan) | ✅ Sudah Ada | Rencana studi individual siswa |
| 62 | `student_outcome_achievements` | [`StudentOutcomeAchievement`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentOutcomeAchievement) | ✅ Sudah Ada | Pencapaian profil pelajar / luaran belajar |
| 63 | `student_promotions` | [`StudentPromotion`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentPromotion) | ✅ Sudah Ada | Data riwayat kenaikan / tinggal kelas santri |
| 64 | `student_rubric_scores` | [`StudentRubricScore`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentRubricScore) | ✅ Sudah Ada | Penilaian rubrik unjuk kerja / proyek santri |
| 65 | `student_transcripts` | [`StudentTranscript`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentTranscript) | ✅ Sudah Ada | Transkrip nilai komprehensif akademik |
| 66 | `study_programs` | [`StudyProgram`](file:///c:/wamp64/www/laravel-13/app/Modules/StudyProgram) | ✅ Sudah Ada | Program studi / jurusan kejuruan |
| 67 | `subject_categories` | [`SubjectCategory`](file:///c:/wamp64/www/laravel-13/app/Modules/SubjectCategory) | ✅ Sudah Ada | Kelompok mata pelajaran (Umum, Kejuruan, Agama) |
| 68 | `subjects` | [`Subject`](file:///c:/wamp64/www/laravel-13/app/Modules/Subject) | ✅ Sudah Ada | Master mata pelajaran / bidang studi |
| 69 | `teacher_comments` | [`TeacherComment`](file:///c:/wamp64/www/laravel-13/app/Modules/TeacherComment) | ✅ Sudah Ada | Catatan bimbingan & evaluasi guru pengampu |
| 70 | `teachers` | [`Teacher`](file:///c:/wamp64/www/laravel-13/app/Modules/Teacher) | ✅ Sudah Ada | Data guru / pendidik institusi |
| 71 | `teaching_loads` | [`TeachingLoad`](file:///c:/wamp64/www/laravel-13/app/Modules/TeachingLoad) | ✅ Sudah Ada | Beban jam mengajar ekuivalen (JTM) guru |
| 72 | `thesis_supervisions` | [`ThesisSupervision`](file:///c:/wamp64/www/laravel-13/app/Modules/ThesisSupervision) | ✅ Sudah Ada | Bimbingan karya tulis / skripsi tugas akhir |
| 73 | `timetable_constraints` | [`TimetableConstraint`](file:///c:/wamp64/www/laravel-13/app/Modules/TimetableConstraint) | ✅ Sudah Ada | Batasan otomatisasi jadwal pelajaran |
| 74 | `timetable_generations` | [`TimetableGeneration`](file:///c:/wamp64/www/laravel-13/app/Modules/TimetableGeneration) | ✅ Sudah Ada | Eksekusi mesin algoritma pembuat jadwal otomatis |
| 75 | `timetable_rooms` | [`TimetableRoom`](file:///c:/wamp64/www/laravel-13/app/Modules/TimetableRoom) | ✅ Sudah Ada | Alokasi ruang untuk jadwal pelajaran |
| 76 | `timetable_routes` | [`TimetableRoute`](file:///c:/wamp64/www/laravel-13/app/Modules/TimetableRoute) | ✅ Sudah Ada | Rute urutan mata pelajaran per hari |
| 77 | `yudisiums` | [`Yudisium`](file:///c:/wamp64/www/laravel-13/app/Modules/Yudisium) | ✅ Sudah Ada | Sidang penetapan kelulusan yudisium santri/mahasiswa |

---

## 3. Kesiswaan, Mahasiswa, Beasiswa, Konseling & Alumni (62 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `achievements` | [`Achievement`](file:///c:/wamp64/www/laravel-13/app/Modules/Achievement) | ✅ Sudah Ada | Data prestasi akademik & non-akademik siswa/guru |
| 2 | `admission_announcements` | [`AdmissionAnnouncement`](file:///c:/wamp64/www/laravel-13/app/Modules/AdmissionAnnouncement) | ✅ Sudah Ada | Pengumuman hasil kelulusan seleksi PPDB |
| 3 | `admission_document_verifications` | [`AdmissionDocumentVerification`](file:///c:/wamp64/www/laravel-13/app/Modules/AdmissionDocumentVerification) | ✅ Sudah Ada | Verifikasi keabsahan berkas pendaftar baru |
| 4 | `admission_interviews` | [`AdmissionInterview`](file:///c:/wamp64/www/laravel-13/app/Modules/AdmissionInterview) | ✅ Sudah Ada | Penilaian wawancara calon siswa & wali murid |
| 5 | `admission_paths` | [`AdmissionPath`](file:///c:/wamp64/www/laravel-13/app/Modules/AdmissionPath) | ✅ Sudah Ada | Jalur penerimaan (Prestasi, Zonasi, Afirmasi, Tes) |
| 6 | `admission_quota_settings` | [`AdmissionQuotaSetting`](file:///c:/wamp64/www/laravel-13/app/Modules/AdmissionQuotaSetting) | ✅ Sudah Ada | Kapasitas daya tampung pendaftar per jenjang |
| 7 | `admission_selections` | [`AdmissionSelection`](file:///c:/wamp64/www/laravel-13/app/Modules/AdmissionSelection) | ✅ Sudah Ada | Keputusan seleksi penerimaan santri baru |
| 8 | `admission_test_results` | [`AdmissionTestResult`](file:///c:/wamp64/www/laravel-13/app/Modules/AdmissionTestResult) | ✅ Sudah Ada | Hasil skor tes seleksi masuk PPDB |
| 9 | `admission_test_schedules` | [`AdmissionTestSchedule`](file:///c:/wamp64/www/laravel-13/app/Modules/AdmissionTestSchedule) | ✅ Sudah Ada | Jadwal tes tertulis, lisan & wawancara masuk |
| 10 | `admission_timelines` | [`AdmissionTimeline`](file:///c:/wamp64/www/laravel-13/app/Modules/AdmissionTimeline) | ✅ Sudah Ada | Gelombang pendaftaran dan linimasa PPDB |
| 11 | `admissions` | [`Admission`](file:///c:/wamp64/www/laravel-13/app/Modules/Admission) | ✅ Sudah Ada | Master modul penerimaan peserta didik baru |
| 12 | `alumni` | [`Alumnus`](file:///c:/wamp64/www/laravel-13/app/Modules/Alumnus) | ✅ Sudah Ada | Direktori data alumni institusi |
| 13 | `alumni_donations` | [`AlumniDonation`](file:///c:/wamp64/www/laravel-13/app/Modules/AlumniDonation) | ✅ Sudah Ada | Pencatatan sumbangan & wakaf dari alumni |
| 14 | `applicant_achievements` | [`ApplicantAchievement`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantAchievement) | ✅ Sudah Ada | Prestasi yang dilampirkan pendaftar PPDB |
| 15 | `applicant_categories` | [`ApplicantCategory`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantCategory) | ✅ Sudah Ada | Kategori pendaftar (Baru, Pindahan, Beasiswa) |
| 16 | `applicant_choices` | [`ApplicantChoice`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantChoice) | ✅ Sudah Ada | Pilihan jurusan / program studi pendaftar |
| 17 | `applicant_documents` | [`ApplicantDocument`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantDocument) | ✅ Sudah Ada | Unggahan berkas pendaftaran (Akta, KK, Ijazah) |
| 18 | `applicant_domiciles` | [`ApplicantDomicile`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantDomicile) | ✅ Sudah Ada | Alamat dan koordinat zonasi calon santri |
| 19 | `applicant_invoices` | [`ApplicantInvoice`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantInvoice) | ✅ Sudah Ada | Tagihan biaya pendaftaran & formulir PPDB |
| 20 | `applicant_notifications` | [`ApplicantNotification`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantNotification) | ✅ Sudah Ada | Pemberitahuan status seleksi via WA/Email |
| 21 | `applicant_parents` | [`ApplicantParent`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantParent) | ✅ Sudah Ada | Profil orang tua / wali pendaftar baru |
| 22 | `applicant_payments` | [`ApplicantPayment`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantPayment) | ✅ Sudah Ada | Pembayaran biaya formulir & daftar ulang |
| 23 | `applicant_registrations` | [`ApplicantRegistration`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantRegistration) | ✅ Sudah Ada | Data formulir registrasi online calon santri |
| 24 | `applicant_status_histories` | [`ApplicantStatusHistory`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantStatusHistory) | ✅ Sudah Ada | Riwayat audit pergantian status pendaftar |
| 25 | `applicant_statuses` | [`ApplicantStatus`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantStatus) | ✅ Sudah Ada | Status pendaftaran (Draf, Submit, Lolos, Ditolak) |
| 26 | `applicant_support_programs` | [`ApplicantSupportProgram`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicantSupportProgram) | ✅ Sudah Ada | Program bantuan beasiswa penerimaan santri |
| 27 | `applicants` | [`Applicant`](file:///c:/wamp64/www/laravel-13/app/Modules/Applicant) | ✅ Sudah Ada | Data master calon siswa pendaftar |
| 28 | `application_documents` | [`ApplicationDocument`](file:///c:/wamp64/www/laravel-13/app/Modules/ApplicationDocument) | ✅ Sudah Ada | Dokumen formulir administrasi calon siswa |
| 29 | `blood_types` | [`BloodType`](file:///c:/wamp64/www/laravel-13/app/Modules/BloodType) | ✅ Sudah Ada | Master golongan darah siswa & pegawai |
| 30 | `counseling_sessions` | [`CounselingSession`](file:///c:/wamp64/www/laravel-13/app/Modules/CounselingSession) | ✅ Sudah Ada | Layanan konseling privat BK siswa |
| 31 | `customers` | [`Customer`](file:///c:/wamp64/www/laravel-13/app/Modules/Customer) | ✅ Sudah Ada | Data pelanggan umum / mitra eksternal |
| 32 | `enrollment_conversions` | [`EnrollmentConversion`](file:///c:/wamp64/www/laravel-13/app/Modules/EnrollmentConversion) | ✅ Sudah Ada | Konversi status pendaftar menjadi siswa aktif |
| 33 | `extracurricular_achievements` | [`ExtracurricularAchievement`](file:///c:/wamp64/www/laravel-13/app/Modules/ExtracurricularAchievement) | ✅ Sudah Ada | Prestasi yang diraih dalam kegiatan ekskul |
| 34 | `extracurricular_attendance` | [`ExtracurricularAttendance`](file:///c:/wamp64/www/laravel-13/app/Modules/ExtracurricularAttendance) | ✅ Sudah Ada | Presensi kehadiran kegiatan ekstrakurikuler |
| 35 | `extracurricular_attendances` | [`ExtracurricularAttendance`](file:///c:/wamp64/www/laravel-13/app/Modules/ExtracurricularAttendance) | ✅ Sudah Ada | Presensi kehadiran kegiatan ekstrakurikuler |
| 36 | `extracurricular_coaches` | [`ExtracurricularCoach`](file:///c:/wamp64/www/laravel-13/app/Modules/ExtracurricularCoach) | ✅ Sudah Ada | Data pelatih / pembina ekstrakurikuler |
| 37 | `extracurricular_fees` | [`ExtracurricularFee`](file:///c:/wamp64/www/laravel-13/app/Modules/ExtracurricularFee) | ✅ Sudah Ada | Iuran pembiayaan kegiatan ekstrakurikuler |
| 38 | `extracurricular_schedules` | [`ExtracurricularSchedule`](file:///c:/wamp64/www/laravel-13/app/Modules/ExtracurricularSchedule) | ✅ Sudah Ada | Jadwal rutin latihan ekstrakurikuler |
| 39 | `extracurriculars` | [`Extracurricular`](file:///c:/wamp64/www/laravel-13/app/Modules/Extracurricular) | ✅ Sudah Ada | Master kegiatan ekstrakurikuler sekolah |
| 40 | `genders` | [`Gender`](file:///c:/wamp64/www/laravel-13/app/Modules/Gender) | ✅ Sudah Ada | Master referensi jenis kelamin |
| 41 | `identity_types` | [`IdentityType`](file:///c:/wamp64/www/laravel-13/app/Modules/IdentityType) | ✅ Sudah Ada | Master jenis identitas (NISN, NIK, Paspor, KITAS) |
| 42 | `parents` | [`Parent`](file:///c:/wamp64/www/laravel-13/app/Modules/Parent) | ✅ Sudah Ada | Data orang tua / wali murid siswa aktif |
| 43 | `religions` | [`Religion`](file:///c:/wamp64/www/laravel-13/app/Modules/Religion) | ✅ Sudah Ada | Master data agama |
| 44 | `scholarship_applications` | [`ScholarshipApplication`](file:///c:/wamp64/www/laravel-13/app/Modules/ScholarshipApplication) | ✅ Sudah Ada | Pendaftaran pengajuan beasiswa santri |
| 45 | `scholarship_disbursements` | [`ScholarshipDisbursement`](file:///c:/wamp64/www/laravel-13/app/Modules/ScholarshipDisbursement) | ✅ Sudah Ada | Pencairan beasiswa ke rekening siswa/SPP |
| 46 | `scholarship_recipients` | [`ScholarshipRecipient`](file:///c:/wamp64/www/laravel-13/app/Modules/ScholarshipRecipient) | ✅ Sudah Ada | Daftar penerima beasiswa aktif |
| 47 | `scholarship_types` | [`ScholarshipType`](file:///c:/wamp64/www/laravel-13/app/Modules/ScholarshipType) | ✅ Sudah Ada | Kategori jenis beasiswa (Prestasi, Yatim, Dhuafa) |
| 48 | `scholarships` | [`Scholarship`](file:///c:/wamp64/www/laravel-13/app/Modules/Scholarship) | ✅ Sudah Ada | Master program beasiswa pendidikan |
| 49 | `student_attendances` | [`StudentAttendance`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentAttendance) | ✅ Sudah Ada | Presensi harian dan rekap kehadiran siswa/santri |
| 50 | `student_disciplines` | [`StudentDiscipline`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentDiscipline) | ✅ Sudah Ada | Pencatatan poin pelanggaran & prestasi kedisiplinan |
| 51 | `student_extracurriculars` | [`StudentExtracurricular`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentExtracurricular) | ✅ Sudah Ada | Keikutsertaan siswa dalam ekskul |
| 52 | `student_health_records` | [`StudentHealthRecord`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentHealthRecord) | ✅ Sudah Ada | Rekam medis riwayat kesehatan & alergi santri |
| 53 | `student_parents` | [`StudentParent`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentParent) | ✅ Sudah Ada | Hubungan kekerabatan siswa dengan orang tua |
| 54 | `student_ranges` | [`StudentRange`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentRange) | ✅ Sudah Ada | Klasifikasi angkatan / ranking kemampuan siswa |
| 55 | `student_rfid_cards` | [`StudentRfidCard`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentRfidCard) | ✅ Sudah Ada | Penerbitan kartu pelajar pintar berchip RFID |
| 56 | `student_transport_subscriptions` | [`StudentTransportSubscription`](file:///c:/wamp64/www/laravel-13/app/Modules/StudentTransportSubscription) | ✅ Sudah Ada | Langganan armada antar-jemput siswa |
| 57 | `students` | [`Student`](file:///c:/wamp64/www/laravel-13/app/Modules/Student) | ✅ Sudah Ada | Master data profil induk siswa/santri |
| 58 | `submissions` | [`Submission`](file:///c:/wamp64/www/laravel-13/app/Modules/Submission) | ✅ Sudah Ada | Pengumpulan tugas berkas oleh siswa |
| 59 | `tracer_studies` | [`TracerStudy`](file:///c:/wamp64/www/laravel-13/app/Modules/TracerStudy) | ✅ Sudah Ada | Kuisioner survei pelacakan karier alumni |
| 60 | `tracer_study_questions` | [`TracerStudyQuestion`](file:///c:/wamp64/www/laravel-13/app/Modules/TracerStudyQuestion) | ✅ Sudah Ada | Pertanyaan survei tracer study |
| 61 | `tracer_study_responses` | [`TracerStudyResponse`](file:///c:/wamp64/www/laravel-13/app/Modules/TracerStudyResponse) | ✅ Sudah Ada | Jawaban tanggapan alumni pada kuesioner tracer |
| 62 | `trial_registrations` | [`TrialRegistration`](file:///c:/wamp64/www/laravel-13/app/Modules/TrialRegistration) | ✅ Sudah Ada | Pendaftaran akun uji coba / demo sistem |

---

## 4. Kepegawaian, HRD, Presensi & Hak Akses Organisasi (38 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `action_role` | [`ActionRole`](file:///c:/wamp64/www/laravel-13/app/Modules/ActionRole) | ✅ Sudah Ada | Pemetaan aksi tombol ke role hak akses pengguna |
| 2 | `attendance_corrections` | [`AttendanceCorrection`](file:///c:/wamp64/www/laravel-13/app/Modules/AttendanceCorrection) | ✅ Sudah Ada | Pengajuan koreksi presensi yang keliru/lupa tap |
| 3 | `attendance_dailies` | [`AttendanceDaily`](file:///c:/wamp64/www/laravel-13/app/Modules/AttendanceDaily) | ✅ Sudah Ada | Rekapitulasi kehadiran harian pegawai |
| 4 | `attendance_import_batches` | [`AttendanceImportBatch`](file:///c:/wamp64/www/laravel-13/app/Modules/AttendanceImportBatch) | ✅ Sudah Ada | Batch unggah data presensi mesin sidik jari |
| 5 | `attendance_imports` | [`AttendanceImport`](file:///c:/wamp64/www/laravel-13/app/Modules/AttendanceImport) | ✅ Sudah Ada | Impor log presensi dari mesin biometric |
| 6 | `attendance_logs` | [`AttendanceLog`](file:///c:/wamp64/www/laravel-13/app/Modules/AttendanceLog) | ✅ Sudah Ada | Log realtime tap kehadiran RFID/biometrik |
| 7 | `attendances` | [`Attendance`](file:///c:/wamp64/www/laravel-13/app/Modules/Attendance) | ✅ Sudah Ada | Transaksi presensi masuk dan pulang |
| 8 | `bonuses` | [`Bonus`](file:///c:/wamp64/www/laravel-13/app/Modules/Bonus) | ✅ Sudah Ada | Data bonus & insentif kinerja pegawai |
| 9 | `departments` | [`Department`](file:///c:/wamp64/www/laravel-13/app/Modules/Department) | ✅ Sudah Ada | Departemen / divisi struktural organisasi |
| 10 | `employee_attendance_logs` | [`EmployeeAttendanceLog`](file:///c:/wamp64/www/laravel-13/app/Modules/EmployeeAttendanceLog) | ✅ Sudah Ada | Log mentah mesin absensi karyawan |
| 11 | `employee_attendances` | [`EmployeeAttendance`](file:///c:/wamp64/www/laravel-13/app/Modules/EmployeeAttendance) | ✅ Sudah Ada | Rekapitulasi presensi bulanan pegawai |
| 12 | `employee_bonuses` | [`EmployeeBonus`](file:///c:/wamp64/www/laravel-13/app/Modules/EmployeeBonus) | ✅ Sudah Ada | Daftar penerima bonus prestasi kerja |
| 13 | `employee_contracts` | [`EmployeeContract`](file:///c:/wamp64/www/laravel-13/app/Modules/EmployeeContract) | ✅ Sudah Ada | Arsip surat perjanjian kontrak kerja pegawai |
| 14 | `employee_deductions` | [`EmployeeDeduction`](file:///c:/wamp64/www/laravel-13/app/Modules/EmployeeDeduction) | ✅ Sudah Ada | Potongan gaji pegawai perorangan |
| 15 | `employee_leaves` | [`EmployeeLeave`](file:///c:/wamp64/www/laravel-13/app/Modules/EmployeeLeave) | ✅ Sudah Ada | Pengajuan & persetujuan cuti karyawan |
| 16 | `employee_mutations` | [`EmployeeMutation`](file:///c:/wamp64/www/laravel-13/app/Modules/EmployeeMutation) | ✅ Sudah Ada | Mutasi penugasan & promosi jabatan pegawai |
| 17 | `employee_salary_components` | [`EmployeeSalaryComponent`](file:///c:/wamp64/www/laravel-13/app/Modules/EmployeeSalaryComponent) | ✅ Sudah Ada | Struktur komponen gaji per individu karyawan |
| 18 | `employee_shifts` | [`EmployeeShift`](file:///c:/wamp64/www/laravel-13/app/Modules/EmployeeShift) | ✅ Sudah Ada | Plotting jadwal shift kerja staf/guru |
| 19 | `employee_types` | [`EmployeeType`](file:///c:/wamp64/www/laravel-13/app/Modules/EmployeeType) | ✅ Sudah Ada | Jenis kepegawaian (PNS, Tetap Yayasan, Honorer) |
| 20 | `employees` | [`Employee`](file:///c:/wamp64/www/laravel-13/app/Modules/Employee) | ✅ Sudah Ada | Master biodata & profil kepegawaian |
| 21 | `holidays` | [`Holiday`](file:///c:/wamp64/www/laravel-13/app/Modules/Holiday) | ✅ Sudah Ada | Master tanggal libur nasional & cuti bersama |
| 22 | `leave_balances` | [`LeaveBalance`](file:///c:/wamp64/www/laravel-13/app/Modules/LeaveBalance) | ✅ Sudah Ada | Sisa kuota hak cuti tahunan pegawai |
| 23 | `leave_types` | [`LeaveType`](file:///c:/wamp64/www/laravel-13/app/Modules/LeaveType) | ✅ Sudah Ada | Master jenis cuti (Tahunan, Melahirkan, Sakit, Umrah) |
| 24 | `loan_installments` | [`LoanInstallment`](file:///c:/wamp64/www/laravel-13/app/Modules/LoanInstallment) | ✅ Sudah Ada | Cicilan pemotongan kasbon dari gaji pegawai |
| 25 | `loans` | [`Loan`](file:///c:/wamp64/www/laravel-13/app/Modules/Loan) | ✅ Sudah Ada | Pinjaman / kasbon karyawan ke koperasi/yayasan |
| 26 | `marital_statuses` | [`MaritalStatus`](file:///c:/wamp64/www/laravel-13/app/Modules/MaritalStatus) | ✅ Sudah Ada | Master status perkawinan |
| 27 | `organization_user` | [`OrganizationUser`](file:///c:/wamp64/www/laravel-13/app/Modules/OrganizationUser) | ✅ Sudah Ada | Pemetaan akses user ke unit organisasi kerja |
| 28 | `overtimes` | [`Overtime`](file:///c:/wamp64/www/laravel-13/app/Modules/Overtime) | ✅ Sudah Ada | Pengajuan & persetujuan lembur kerja (SPKL) |
| 29 | `permission_requests` | [`PermissionRequest`](file:///c:/wamp64/www/laravel-13/app/Modules/PermissionRequest) | ✅ Sudah Ada | Surat permohonan izin meninggalkan tugas / dispensasi |
| 30 | `position_user` | [`PositionUser`](file:///c:/wamp64/www/laravel-13/app/Modules/PositionUser) | ✅ Sudah Ada | Penugasan jabatan spesifik kepada pengguna sistem |
| 31 | `position_users` | [`PositionUser`](file:///c:/wamp64/www/laravel-13/app/Modules/PositionUser) | ✅ Sudah Ada | Penugasan jabatan kepada pengguna akun |
| 32 | `positions` | [`Position`](file:///c:/wamp64/www/laravel-13/app/Modules/Position) | ✅ Sudah Ada | Master jabatan struktural & fungsional |
| 33 | `shift_rates` | [`ShiftRate`](file:///c:/wamp64/www/laravel-13/app/Modules/ShiftRate) | ✅ Sudah Ada | Tarif insentif uang makan/kehadiran per shift |
| 34 | `shift_swaps` | [`ShiftSwap`](file:///c:/wamp64/www/laravel-13/app/Modules/ShiftSwap) | ✅ Sudah Ada | Pengajuan tukar jadwal shift antar staf |
| 35 | `shifts` | [`Shift`](file:///c:/wamp64/www/laravel-13/app/Modules/Shift) | ✅ Sudah Ada | Master pola jam kerja shift (Pagi, Siang, Malam) |
| 36 | `work_calendar_holidays` | [`WorkCalendarHoliday`](file:///c:/wamp64/www/laravel-13/app/Modules/WorkCalendarHoliday) | ✅ Sudah Ada | Daftar tanggal libur kalender kerja institusi |
| 37 | `work_calendars` | [`WorkCalendar`](file:///c:/wamp64/www/laravel-13/app/Modules/WorkCalendar) | ✅ Sudah Ada | Master kalender jam kerja institusi |
| 38 | `work_schedules` | [`WorkSchedule`](file:///c:/wamp64/www/laravel-13/app/Modules/WorkSchedule) | ✅ Sudah Ada | Pengaturan skema hari kerja mingguan |

---

## 5. Perpustakaan & Koleksi Buku (Library) (13 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `book_authors` | [`BookAuthor`](file:///c:/wamp64/www/laravel-13/app/Modules/BookAuthor) | ✅ Sudah Ada | Master biodata penulis / pengarang buku |
| 2 | `book_categories` | [`BookCategory`](file:///c:/wamp64/www/laravel-13/app/Modules/BookCategory) | ✅ Sudah Ada | Klasifikasi kategori buku (DDC / Tajuk Subjek) |
| 3 | `book_copies` | [`BookCopy`](file:///c:/wamp64/www/laravel-13/app/Modules/BookCopy) | ✅ Sudah Ada | Nomor barcode & eksemplar fisik buku perpustakaan |
| 4 | `book_publishers` | [`BookPublisher`](file:///c:/wamp64/www/laravel-13/app/Modules/BookPublisher) | ✅ Sudah Ada | Master penerbit buku & percetakan |
| 5 | `book_reservations` | [`BookReservation`](file:///c:/wamp64/www/laravel-13/app/Modules/BookReservation) | ✅ Sudah Ada | Booking / pemesanan pinjam buku perpustakaan |
| 6 | `digital_library_access_logs` | [`DigitalLibraryAccessLog`](file:///c:/wamp64/www/laravel-13/app/Modules/DigitalLibraryAccessLog) | ✅ Sudah Ada | Riwayat unduh & baca buku elektronik (e-Book) |
| 7 | `digital_library_items` | [`DigitalLibraryItem`](file:///c:/wamp64/www/laravel-13/app/Modules/DigitalLibraryItem) | ✅ Sudah Ada | Katalog e-Book, jurnal & publikasi PDF digital |
| 8 | `library_books` | [`LibraryBook`](file:///c:/wamp64/www/laravel-13/app/Modules/LibraryBook) | ✅ Sudah Ada | Master katalog judul buku perpustakaan |
| 9 | `library_fines` | [`LibraryFine`](file:///c:/wamp64/www/laravel-13/app/Modules/LibraryFine) | ✅ Sudah Ada | Pencatatan denda keterlambatan/rusak buku |
| 10 | `library_loans` | [`LibraryLoan`](file:///c:/wamp64/www/laravel-13/app/Modules/LibraryLoan) | ✅ Sudah Ada | Peminjaman dan pengembalian buku pustaka |
| 11 | `library_memberships` | [`LibraryMembership`](file:///c:/wamp64/www/laravel-13/app/Modules/LibraryMembership) | ✅ Sudah Ada | Master keanggotaan perpustakaan santri & guru |
| 12 | `library_stock_opname` | [`LibraryStockOpname`](file:///c:/wamp64/www/laravel-13/app/Modules/LibraryStockOpname) | ✅ Sudah Ada | Pemeriksaan fisik stok buku berkala perpustakaan |
| 13 | `library_visits` | [`LibraryVisit`](file:///c:/wamp64/www/laravel-13/app/Modules/LibraryVisit) | ✅ Sudah Ada | Buku tamu kunjungan ke perpustakaan |

---

## 6. Pesantren, Keagamaan & Keasramaan (10 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `dormitory_assignments` | [`DormitoryAssignment`](file:///c:/wamp64/www/laravel-13/app/Modules/DormitoryAssignment) | ✅ Sudah Ada | Penempatan kamar asrama santri |
| 2 | `dormitory_movements` | [`DormitoryMovement`](file:///c:/wamp64/www/laravel-13/app/Modules/DormitoryMovement) | ✅ Sudah Ada | Riwayat mutasi perpindahan kamar santri |
| 3 | `dormitory_permits` | [`DormitoryPermit`](file:///c:/wamp64/www/laravel-13/app/Modules/DormitoryPermit) | ✅ Sudah Ada | Surat izin pulang / pesiar keluar pesantren |
| 4 | `dormitory_rooms` | [`DormitoryRoom`](file:///c:/wamp64/www/laravel-13/app/Modules/DormitoryRoom) | ✅ Sudah Ada | Master kamar asrama dan kapasitas ranjang santri |
| 5 | `kitab_references` | [`KitabReference`](file:///c:/wamp64/www/laravel-13/app/Modules/KitabReference) | ✅ Sudah Ada | Daftar pustaka kitab kuning & silabus kepesantrenan |
| 6 | `pesantren_activities` | [`PesantrenActivity`](file:///c:/wamp64/www/laravel-13/app/Modules/PesantrenActivity) | ✅ Sudah Ada | Jadwal kegiatan ubudiyah dan mengaji pondok |
| 7 | `pesantren_rule_violations` | [`PesantrenRuleViolation`](file:///c:/wamp64/www/laravel-13/app/Modules/PesantrenRuleViolation) | ✅ Sudah Ada | Pelanggaran tata tertib pondok pesantren |
| 8 | `pesantren_rules` | [`PesantrenRule`](file:///c:/wamp64/www/laravel-13/app/Modules/PesantrenRule) | ✅ Sudah Ada | Buku pedoman tata tertib & pasal santri |
| 9 | `sorogan_evaluations` | [`SoroganEvaluation`](file:///c:/wamp64/www/laravel-13/app/Modules/SoroganEvaluation) | ✅ Sudah Ada | Penilaian hafalan & bacaan kitab sorogan santri |
| 10 | `sorogan_schedules` | [`SoroganSchedule`](file:///c:/wamp64/www/laravel-13/app/Modules/SoroganSchedule) | ✅ Sudah Ada | Jadwal sesi mengaji metode sorogan santri |

---

## 7. Inventaris, Aset, Logistik & Pengadaan (34 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `asset_categories` | [`AssetCategory`](file:///c:/wamp64/www/laravel-13/app/Modules/AssetCategory) | ✅ Sudah Ada | Kategori klasifikasi aset sarana prasarana |
| 2 | `asset_depreciation_logs` | [`AssetDepreciationLog`](file:///c:/wamp64/www/laravel-13/app/Modules/AssetDepreciationLog) | ✅ Sudah Ada | Jurnal log penyusutan aset bulanan |
| 3 | `asset_depreciations` | [`AssetDepreciation`](file:///c:/wamp64/www/laravel-13/app/Modules/AssetDepreciation) | ✅ Sudah Ada | Perhitungan nilai depresiasi aset tetap |
| 4 | `asset_disposals` | [`AssetDisposal`](file:///c:/wamp64/www/laravel-13/app/Modules/AssetDisposal) | ✅ Sudah Ada | Pemusnahan, lelang, atau penjualan aset rusak/usang |
| 5 | `asset_maintenances` | [`AssetMaintenance`](file:///c:/wamp64/www/laravel-13/app/Modules/AssetMaintenance) | ✅ Sudah Ada | Pencatatan servis berkala & perbaikan aset |
| 6 | `assets` | [`Asset`](file:///c:/wamp64/www/laravel-13/app/Modules/Asset) | ✅ Sudah Ada | Master data inventaris aset sarpras dan nomor barcode |
| 7 | `buildings` | [`Building`](file:///c:/wamp64/www/laravel-13/app/Modules/Building) | ✅ Sudah Ada | Master data gedung / fasilitas fisik kampus |
| 8 | `depreciation_logs` | [`DepreciationLog`](file:///c:/wamp64/www/laravel-13/app/Modules/DepreciationLog) | ✅ Sudah Ada | Jurnal kalkulasi depresiasi aktiva tetap |
| 9 | `facilities` | [`Facility`](file:///c:/wamp64/www/laravel-13/app/Modules/Facility) | ✅ Sudah Ada | Master fasilitas prasarana ruangan (Proyektor, AC, Kursi) |
| 10 | `facility_categories` | [`FacilityCategory`](file:///c:/wamp64/www/laravel-13/app/Modules/FacilityCategory) | ✅ Sudah Ada | Kategori sarana dan prasarana penunjang |
| 11 | `fixed_assets` | [`FixedAsset`](file:///c:/wamp64/www/laravel-13/app/Modules/FixedAsset) | ✅ Sudah Ada | Daftar aktiva tetap berwujud bernilai tinggi |
| 12 | `floors` | [`Floor`](file:///c:/wamp64/www/laravel-13/app/Modules/Floor) | ✅ Sudah Ada | Data lantai gedung |
| 13 | `goods_receipt_items` | [`GoodsReceiptItem`](file:///c:/wamp64/www/laravel-13/app/Modules/GoodsReceiptItem) | ✅ Sudah Ada | Rincian item barang yang diterima dari vendor |
| 14 | `goods_receipts` | [`GoodsReceipt`](file:///c:/wamp64/www/laravel-13/app/Modules/GoodsReceipt) | ✅ Sudah Ada | Dokumen tanda terima barang masuk gudang (LPB) |
| 15 | `inventory_categories` | [`InventoryCategory`](file:///c:/wamp64/www/laravel-13/app/Modules/InventoryCategory) | ✅ Sudah Ada | Klasifikasi kelompok barang persediaan |
| 16 | `inventory_items` | [`InventoryItem`](file:///c:/wamp64/www/laravel-13/app/Modules/InventoryItem) | ✅ Sudah Ada | Master barang persediaan habis pakai & ATK |
| 17 | `inventory_stocks` | [`InventoryStock`](file:///c:/wamp64/www/laravel-13/app/Modules/InventoryStock) | ✅ Sudah Ada | Posisi stok fisik persediaan per gudang |
| 18 | `locations` | [`Location`](file:///c:/wamp64/www/laravel-13/app/Modules/Location) | ✅ Sudah Ada | Master lokasi penempatan barang/aset |
| 19 | `products` | [`Product`](file:///c:/wamp64/www/laravel-13/app/Modules/Product) | ✅ Sudah Ada | Master produk barang komersil / koperasi |
| 20 | `purchase_order_items` | [`PurchaseOrderItem`](file:///c:/wamp64/www/laravel-13/app/Modules/PurchaseOrderItem) | ✅ Sudah Ada | Item barang yang dipesan ke supplier |
| 21 | `purchase_orders` | [`PurchaseOrder`](file:///c:/wamp64/www/laravel-13/app/Modules/PurchaseOrder) | ✅ Sudah Ada | Surat Pesanan Pembelian (PO) ke pemasok |
| 22 | `purchase_request_items` | [`PurchaseRequestItem`](file:///c:/wamp64/www/laravel-13/app/Modules/PurchaseRequestItem) | ✅ Sudah Ada | Item pengajuan kebutuhan pembelian barang |
| 23 | `purchase_requests` | [`PurchaseRequest`](file:///c:/wamp64/www/laravel-13/app/Modules/PurchaseRequest) | ✅ Sudah Ada | Surat Permintaan Pembelian (PR) dari unit kerja |
| 24 | `rooms` | [`Room`](file:///c:/wamp64/www/laravel-13/app/Modules/Room) | ✅ Sudah Ada | Master data ruang kelas, lab, aula & kantor |
| 25 | `stock_adjustment_items` | [`StockAdjustmentItem`](file:///c:/wamp64/www/laravel-13/app/Modules/StockAdjustmentItem) | ✅ Sudah Ada | Rincian selisih opname penyesuaian stok |
| 26 | `stock_adjustments` | [`StockAdjustment`](file:///c:/wamp64/www/laravel-13/app/Modules/StockAdjustment) | ✅ Sudah Ada | Penyesuaian stok selisih opname gudang |
| 27 | `stock_movements` | [`StockMovement`](file:///c:/wamp64/www/laravel-13/app/Modules/StockMovement) | ✅ Sudah Ada | Kartu kendali arus keluar-masuk barang persediaan |
| 28 | `stock_transfer_items` | [`StockTransferItem`](file:///c:/wamp64/www/laravel-13/app/Modules/StockTransferItem) | ✅ Sudah Ada | Item barang yang dipindahkan antar gudang |
| 29 | `stock_transfers` | [`StockTransfer`](file:///c:/wamp64/www/laravel-13/app/Modules/StockTransfer) | ✅ Sudah Ada | Surat jalan pemindahan stok antar unit/gudang |
| 30 | `suppliers` | [`Supplier`](file:///c:/wamp64/www/laravel-13/app/Modules/Supplier) | ✅ Sudah Ada | Master data vendor pemasok barang/jasa |
| 31 | `unit_of_measurements` | [`UnitOfMeasurement`](file:///c:/wamp64/www/laravel-13/app/Modules/UnitOfMeasurement) | ✅ Sudah Ada | Master satuan ukuran barang (Pcs, Box, Rim, Kg) |
| 32 | `uom_conversions` | [`UomConversion`](file:///c:/wamp64/www/laravel-13/app/Modules/UomConversion) | ✅ Sudah Ada | Tabel konversi perbandingan antar satuan (1 Box = 24 Pcs) |
| 33 | `vendors` | [`Vendor`](file:///c:/wamp64/www/laravel-13/app/Modules/Vendor) | ✅ Sudah Ada | Rekanan kerja sama pengadaan barang/jasa |
| 34 | `warehouses` | [`Warehouse`](file:///c:/wamp64/www/laravel-13/app/Modules/Warehouse) | ✅ Sudah Ada | Master lokasi gudang penyimpanan logistik |

---

## 8. Ujian, Evaluasi & CBT (Computer Based Test) (22 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `assignments_quizzes` | [`AssignmentsQuiz`](file:///c:/wamp64/www/laravel-13/app/Modules/AssignmentsQuiz) | ✅ Sudah Ada | Penugasan lembar kerja & kuis daring siswa |
| 2 | `cbt_activity_logs` | [`CbtActivityLog`](file:///c:/wamp64/www/laravel-13/app/Modules/CbtActivityLog) | ✅ Sudah Ada | Jejak audit aktivitas peserta saat ujian daring |
| 3 | `cbt_features` | [`CbtFeature`](file:///c:/wamp64/www/laravel-13/app/Modules/CbtFeature) | ✅ Sudah Ada | Fitur keamanan CBT (Acak Soal, Anti Alt-Tab, Timer) |
| 4 | `cbt_proctoring` | [`CbtProctoring`](file:///c:/wamp64/www/laravel-13/app/Modules/CbtProctoring) | ✅ Sudah Ada | Monitoring pengawasan ujian daring dari komputer guru |
| 5 | `cbt_proctoring_evidence` | [`CbtProctoringEvidence`](file:///c:/wamp64/www/laravel-13/app/Modules/CbtProctoringEvidence) | ✅ Sudah Ada | Bukti tangkapan layar / webcam pelanggaran tes |
| 6 | `cbt_schedule_features` | [`CbtScheduleFeature`](file:///c:/wamp64/www/laravel-13/app/Modules/CbtScheduleFeature) | ✅ Sudah Ada | Aktivasi pembatasan fitur per sesi ujian |
| 7 | `cbt_schedule_proctors` | [`CbtScheduleProctor`](file:///c:/wamp64/www/laravel-13/app/Modules/CbtScheduleProctor) | ✅ Sudah Ada | Penugasan guru pengawas ruang ujian CBT |
| 8 | `cbt_session_answers` | [`CbtSessionAnswer`](file:///c:/wamp64/www/laravel-13/app/Modules/CbtSessionAnswer) | ✅ Sudah Ada | Lembar jawaban & pilihan opsi soal peserta CBT |
| 9 | `cbt_sessions` | [`CbtSession`](file:///c:/wamp64/www/laravel-13/app/Modules/CbtSession) | ✅ Sudah Ada | Sesi login pengerjaan tes CBT oleh peserta |
| 10 | `exam_package_questions` | [`ExamPackageQuestion`](file:///c:/wamp64/www/laravel-13/app/Modules/ExamPackageQuestion) | ✅ Sudah Ada | Butir-butir soal yang masuk paket ujian |
| 11 | `exam_packages` | [`ExamPackage`](file:///c:/wamp64/www/laravel-13/app/Modules/ExamPackage) | ✅ Sudah Ada | Master paket bundel naskah soal ujian |
| 12 | `question_bank_versions` | [`QuestionBankVersion`](file:///c:/wamp64/www/laravel-13/app/Modules/QuestionBankVersion) | ✅ Sudah Ada | Riwayat perubahan & revisi redaksional soal |
| 13 | `question_banks` | [`QuestionBank`](file:///c:/wamp64/www/laravel-13/app/Modules/QuestionBank) | ✅ Sudah Ada | Master bank butir soal ujian |
| 14 | `question_matching_pairs` | [`QuestionMatchingPair`](file:///c:/wamp64/www/laravel-13/app/Modules/QuestionMatchingPair) | ✅ Sudah Ada | Kunci pasangan soal tipe menjodohkan (Matching) |
| 15 | `question_media` | [`QuestionMedia`](file:///c:/wamp64/www/laravel-13/app/Modules/QuestionMedia) | ✅ Sudah Ada | Berkas gambar, rekaman audio listening, atau video soal |
| 16 | `question_media_annotations` | [`QuestionMediaAnnotation`](file:///c:/wamp64/www/laravel-13/app/Modules/QuestionMediaAnnotation) | ✅ Sudah Ada | Anotasi teks pada lampiran media soal |
| 17 | `question_options` | [`QuestionOption`](file:///c:/wamp64/www/laravel-13/app/Modules/QuestionOption) | ✅ Sudah Ada | Opsi pilihan jawaban ganda & kunci jawaban |
| 18 | `question_stimuli` | [`QuestionStimulus`](file:///c:/wamp64/www/laravel-13/app/Modules/QuestionStimulus) | ✅ Sudah Ada | Wacana bacaan / stimulus soal berbasis literasi AKM |
| 19 | `question_types` | [`QuestionType`](file:///c:/wamp64/www/laravel-13/app/Modules/QuestionType) | ✅ Sudah Ada | Jenis tipe soal (Pilihan Ganda, Essay, Menjodohkan) |
| 20 | `quiz_attempts` | [`QuizAttempt`](file:///c:/wamp64/www/laravel-13/app/Modules/QuizAttempt) | ✅ Sudah Ada | Riwayat pengerjaan kuis latihan mandiri siswa |
| 21 | `test_schedules` | [`TestSchedule`](file:///c:/wamp64/www/laravel-13/app/Modules/TestSchedule) | ✅ Sudah Ada | Jadwal pelaksanaan ujian semester/harian |
| 22 | `test_types` | [`TestType`](file:///c:/wamp64/www/laravel-13/app/Modules/TestType) | ✅ Sudah Ada | Master jenis ujian (STS, SAS, Tryout, US) |

---

## 9. Fasilitas, Transportasi, Kantin & Klinik/UKS (14 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `canteen_menus` | [`CanteenMenu`](file:///c:/wamp64/www/laravel-13/app/Modules/CanteenMenu) | ✅ Sudah Ada | Daftar menu makanan dan minuman kantin |
| 2 | `canteen_order_items` | [`CanteenOrderItem`](file:///c:/wamp64/www/laravel-13/app/Modules/CanteenOrderItem) | ✅ Sudah Ada | Item makanan yang dipesan di kantin |
| 3 | `canteen_orders` | [`CanteenOrder`](file:///c:/wamp64/www/laravel-13/app/Modules/CanteenOrder) | ✅ Sudah Ada | Pencatatan transaksi kasir pesanan kantin |
| 4 | `canteen_settlements` | [`CanteenSettlement`](file:///c:/wamp64/www/laravel-13/app/Modules/CanteenSettlement) | ✅ Sudah Ada | Pencairan bagi hasil penjualan tenant kantin |
| 5 | `canteen_tenants` | [`CanteenTenant`](file:///c:/wamp64/www/laravel-13/app/Modules/CanteenTenant) | ✅ Sudah Ada | Master penyewa gerai makanan kantin sekolah |
| 6 | `clinic_medicines` | [`ClinicMedicine`](file:///c:/wamp64/www/laravel-13/app/Modules/ClinicMedicine) | ✅ Sudah Ada | Inventaris stok obat-obatan klinik/UKS |
| 7 | `clinic_prescriptions` | [`ClinicPrescription`](file:///c:/wamp64/www/laravel-13/app/Modules/ClinicPrescription) | ✅ Sudah Ada | Resep obat hasil diagnosa dokter/perawat UKS |
| 8 | `clinic_visits` | [`ClinicVisit`](file:///c:/wamp64/www/laravel-13/app/Modules/ClinicVisit) | ✅ Sudah Ada | Buku catatan kunjungan berobat siswa/staf UKS |
| 9 | `drivers` | [`Driver`](file:///c:/wamp64/www/laravel-13/app/Modules/Driver) | ✅ Sudah Ada | Data pengemudi armada bus antar-jemput sekolah |
| 10 | `gps_telemetry_logs` | [`GpsTelemetryLog`](file:///c:/wamp64/www/laravel-13/app/Modules/GpsTelemetryLog) | ✅ Sudah Ada | Koordinat posisi realtime armada kendaraan sekolah |
| 11 | `transport_routes` | [`TransportRoute`](file:///c:/wamp64/www/laravel-13/app/Modules/TransportRoute) | ✅ Sudah Ada | Rute jalur trayek penjemputan siswa |
| 12 | `transport_stops` | [`TransportStop`](file:///c:/wamp64/www/laravel-13/app/Modules/TransportStop) | ✅ Sudah Ada | Titik halte pemberhentian penjemputan santri |
| 13 | `vehicle_maintenance_logs` | [`VehicleMaintenanceLog`](file:///c:/wamp64/www/laravel-13/app/Modules/VehicleMaintenanceLog) | ✅ Sudah Ada | Riwayat servis & ganti oli kendaraan dinas |
| 14 | `vehicles` | [`Vehicle`](file:///c:/wamp64/www/laravel-13/app/Modules/Vehicle) | ✅ Sudah Ada | Master armada kendaraan operasional institusi |

---

## 10. E-Learning, Forum Diskusi & Komunikasi (8 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `discussion_replies` | [`DiscussionReply`](file:///c:/wamp64/www/laravel-13/app/Modules/DiscussionReply) | ✅ Sudah Ada | Balasan komentar pada forum diskusi kelas |
| 2 | `discussions` | [`Discussion`](file:///c:/wamp64/www/laravel-13/app/Modules/Discussion) | ✅ Sudah Ada | Topik forum diskusi materi mata pelajaran |
| 3 | `email_logs` | [`EmailLog`](file:///c:/wamp64/www/laravel-13/app/Modules/EmailLog) | ✅ Sudah Ada | Riwayat pengiriman surel otomatis sistem |
| 4 | `message_participants` | [`MessageParticipant`](file:///c:/wamp64/www/laravel-13/app/Modules/MessageParticipant) | ✅ Sudah Ada | Peserta ruang obrolan pesan perpesanan |
| 5 | `message_threads` | [`MessageThread`](file:///c:/wamp64/www/laravel-13/app/Modules/MessageThread) | ✅ Sudah Ada | Utas obrolan percakapan pesan |
| 6 | `messages` | [`Message`](file:///c:/wamp64/www/laravel-13/app/Modules/Message) | ✅ Sudah Ada | Isi pesan perpesanan internal sekolah |
| 7 | `sms_logs` | [`SmsLog`](file:///c:/wamp64/www/laravel-13/app/Modules/SmsLog) | ✅ Sudah Ada | Log pengiriman pesan SMS gateway |
| 8 | `whatsapp_logs` | [`WhatsappLog`](file:///c:/wamp64/www/laravel-13/app/Modules/WhatsappLog) | ✅ Sudah Ada | Log realtime pengiriman pesan WhatsApp API |

---

## 11. Alur Kerja (Workflow), Persetujuan & Notifikasi (10 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `approval_histories` | [`ApprovalHistory`](file:///c:/wamp64/www/laravel-13/app/Modules/ApprovalHistory) | ✅ Sudah Ada | Jejak rekam riwayat persetujuan dokumen |
| 2 | `approval_requests` | [`ApprovalRequest`](file:///c:/wamp64/www/laravel-13/app/Modules/ApprovalRequest) | ✅ Sudah Ada | Pengajuan berkas yang membutuhkan tanda tangan/acc |
| 3 | `notification_preferences` | [`NotificationPreference`](file:///c:/wamp64/www/laravel-13/app/Modules/NotificationPreference) | ✅ Sudah Ada | Pengaturan preferensi penerimaan notifikasi user |
| 4 | `notification_templates` | [`NotificationTemplate`](file:///c:/wamp64/www/laravel-13/app/Modules/NotificationTemplate) | ✅ Sudah Ada | Template format redaksional notifikasi dinamis |
| 5 | `notifications` | [`Notification`](file:///c:/wamp64/www/laravel-13/app/Modules/Notification) | ✅ Sudah Ada | Daftar notifikasi aktivitas pengguna |
| 6 | `promotion_approvals` | [`PromotionApproval`](file:///c:/wamp64/www/laravel-13/app/Modules/PromotionApproval) | ✅ Sudah Ada | Verifikasi pengesahan kenaikan kelas santri |
| 7 | `push_notification_tokens` | [`PushNotificationToken`](file:///c:/wamp64/www/laravel-13/app/Modules/PushNotificationToken) | ✅ Sudah Ada | Token pendaftaran push notification mobile Firebase |
| 8 | `workflow_approvers` | [`WorkflowApprover`](file:///c:/wamp64/www/laravel-13/app/Modules/WorkflowApprover) | ✅ Sudah Ada | Daftar pejabat yang berhak memberi persetujuan |
| 9 | `workflow_definitions` | [`WorkflowDefinition`](file:///c:/wamp64/www/laravel-13/app/Modules/WorkflowDefinition) | ✅ Sudah Ada | Rancangan skema alur proses alur persetujuan |
| 10 | `workflow_steps` | [`WorkflowStep`](file:///c:/wamp64/www/laravel-13/app/Modules/WorkflowStep) | ✅ Sudah Ada | Tahapan urutan hierarki verifikasi berkas |

---

## 12. Sistem, Organisasi, Pengaturan & Keamanan (ACL) (40 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `access_logs` | [`AccessLog`](file:///c:/wamp64/www/laravel-13/app/Modules/AccessLog) | ✅ Sudah Ada | Log jejak rekam akses pengguna ke sistem |
| 2 | `action_permissions` | [`ActionPermission`](file:///c:/wamp64/www/laravel-13/app/Modules/ActionPermission) | ✅ Sudah Ada | Hak akses permission terhadap aksi tertentu |
| 3 | `actions` | [`Action`](file:///c:/wamp64/www/laravel-13/app/Modules/Action) | ✅ Sudah Ada | Master aksi tombol operasi sistem (Create, Read, Edit, Delete) |
| 4 | `activity_logs` | [`ActivityLog`](file:///c:/wamp64/www/laravel-13/app/Modules/ActivityLog) | ✅ Sudah Ada | Audit log menyeluruh perubahan data oleh user |
| 5 | `announcements` | [`Announcement`](file:///c:/wamp64/www/laravel-13/app/Modules/Announcement) | ✅ Sudah Ada | Papan informasi pengumuman digital portal |
| 6 | `api_rate_limits` | [`ApiRateLimit`](file:///c:/wamp64/www/laravel-13/app/Modules/ApiRateLimit) | ✅ Sudah Ada | Batas kuota akses panggilan API sistem |
| 7 | `audit_trails` | [`AuditTrail`](file:///c:/wamp64/www/laravel-13/app/Modules/AuditTrail) | ✅ Sudah Ada | Jejak keamanan audit trail sistem |
| 8 | `crud_generator` | [`CrudGenerator`](file:///c:/wamp64/www/laravel-13/app/Modules/CrudGenerator) | ✅ Sudah Ada | Pusat generator kode CRUD modul aplikasi |
| 9 | `dapodik_mappings` | [`DapodikMapping`](file:///c:/wamp64/www/laravel-13/app/Modules/DapodikMapping) | ✅ Sudah Ada | Pemetaan ID database internal ke format Dapodik |
| 10 | `dapodik_sync_logs` | [`DapodikSyncLog`](file:///c:/wamp64/www/laravel-13/app/Modules/DapodikSyncLog) | ✅ Sudah Ada | Log riwayat sinkronisasi data Dapodik Kemdikbud |
| 11 | `dapodik_sync_schedules` | [`DapodikSyncSchedule`](file:///c:/wamp64/www/laravel-13/app/Modules/DapodikSyncSchedule) | ✅ Sudah Ada | Jadwal otomatisasi penarikan data Dapodik |
| 12 | `data_imports` | [`DataImport`](file:///c:/wamp64/www/laravel-13/app/Modules/DataImport) | ✅ Sudah Ada | Riwayat impor massal file Excel ke database |
| 13 | `dictionaries` | [`Dictionary`](file:///c:/wamp64/www/laravel-13/app/Modules/Dictionary) | ✅ Sudah Ada | Kamus data terjemahan glosarium sistem |
| 14 | `digital_signatures` | [`DigitalSignature`](file:///c:/wamp64/www/laravel-13/app/Modules/DigitalSignature) | ✅ Sudah Ada | Kunci sertifikat tanda tangan elektronik (TTE) |
| 15 | `document_access_logs` | [`DocumentAccessLog`](file:///c:/wamp64/www/laravel-13/app/Modules/DocumentAccessLog) | ✅ Sudah Ada | Log akses unduh dokumen arsip institusi |
| 16 | `document_number_sequences` | [`DocumentNumberSequence`](file:///c:/wamp64/www/laravel-13/app/Modules/DocumentNumberSequence) | ✅ Sudah Ada | Format penomoran surat otomatis (Nomor Agenda) |
| 17 | `document_types` | [`DocumentType`](file:///c:/wamp64/www/laravel-13/app/Modules/DocumentType) | ✅ Sudah Ada | Master jenis klasifikasi surat & dokumen resmi |
| 18 | `document_versions` | [`DocumentVersion`](file:///c:/wamp64/www/laravel-13/app/Modules/DocumentVersion) | ✅ Sudah Ada | Riwayat revisi arsip dokumen institusi |
| 19 | `documents` | [`Document`](file:///c:/wamp64/www/laravel-13/app/Modules/Document) | ✅ Sudah Ada | Master repository arsip dokumen digital |
| 20 | `file_uploads` | [`FileUpload`](file:///c:/wamp64/www/laravel-13/app/Modules/FileUpload) | ✅ Sudah Ada | Manajemen berkas unggahan pengguna |
| 21 | `generated_reports` | [`GeneratedReport`](file:///c:/wamp64/www/laravel-13/app/Modules/GeneratedReport) | ✅ Sudah Ada | Riwayat arsip berkas unduhan laporan sistem |
| 22 | `menu_management` | [`MenuManagement`](file:///c:/wamp64/www/laravel-13/app/Modules/MenuManagement) | ✅ Sudah Ada | Pengaturan struktur pohon menu aplikasi |
| 23 | `menus` | [`Menu`](file:///c:/wamp64/www/laravel-13/app/Modules/Menu) | ✅ Sudah Ada | Daftar item navigasi menu sidebar dan topbar |
| 24 | `menus_bak` | `MenusBak` | 💾 Tabel Backup | Data dan entitas menus bak |
| 25 | `modules` | [`Module`](file:///c:/wamp64/www/laravel-13/app/Modules/Module) | ✅ Sudah Ada | Pendaftaran modul fitur dalam aplikasi |
| 26 | `navigation` | [`Navigation`](file:///c:/wamp64/www/laravel-13/app/Modules/Navigation) | ✅ Sudah Ada | Navigasi menu portal publik |
| 27 | `organization_types` | [`OrganizationType`](file:///c:/wamp64/www/laravel-13/app/Modules/OrganizationType) | ✅ Sudah Ada | Jenis entitas organisasi (Yayasan, Sekolah, Unit) |
| 28 | `organizations` | [`Organization`](file:///c:/wamp64/www/laravel-13/app/Modules/Organization) | ✅ Sudah Ada | Master organisasi / yayasan induk multi-lembaga |
| 29 | `packages` | [`Package`](file:///c:/wamp64/www/laravel-13/app/Modules/Package) | ✅ Sudah Ada | Master paket langganan fitur aplikasi (SaaS) |
| 30 | `permissions` | [`Permission`](file:///c:/wamp64/www/laravel-13/app/Modules/Permission) | ✅ Sudah Ada | Master hak akses permission sistem |
| 31 | `policies` | [`Policy`](file:///c:/wamp64/www/laravel-13/app/Modules/Policy) | ✅ Sudah Ada | Kebijakan otoritas otorisasi pengguna |
| 32 | `reference_types` | [`ReferenceType`](file:///c:/wamp64/www/laravel-13/app/Modules/ReferenceType) | ✅ Sudah Ada | Master kelompok tipe kode referensi |
| 33 | `reference_values` | [`ReferenceValue`](file:///c:/wamp64/www/laravel-13/app/Modules/ReferenceValue) | ✅ Sudah Ada | Nilai parameter kode referensi dinamis sistem |
| 34 | `roles` | [`Role`](file:///c:/wamp64/www/laravel-13/app/Modules/Role) | ✅ Sudah Ada | Master peran pengguna (Super Admin, Guru, Siswa, Keuangan) |
| 35 | `scheduled_jobs` | [`ScheduledJob`](file:///c:/wamp64/www/laravel-13/app/Modules/ScheduledJob) | ✅ Sudah Ada | Daftar eksekusi tugas otomatis sistem (Cron) |
| 36 | `schools` | [`School`](file:///c:/wamp64/www/laravel-13/app/Modules/School) | ✅ Sudah Ada | Profil identitas lembaga sekolah / madrasah |
| 37 | `sliders` | [`Slider`](file:///c:/wamp64/www/laravel-13/app/Modules/Slider) | ✅ Sudah Ada | Banner slider gambar pada beranda portal publik |
| 38 | `system_logs` | [`SystemLog`](file:///c:/wamp64/www/laravel-13/app/Modules/SystemLog) | ✅ Sudah Ada | Log error runtime & aktivitas sistem |
| 39 | `tags` | [`Tag`](file:///c:/wamp64/www/laravel-13/app/Modules/Tag) | ✅ Sudah Ada | Master label tag penanda konten |
| 40 | `users` | [`User`](file:///c:/wamp64/www/laravel-13/app/Modules/User) | ✅ Sudah Ada | Master akun login pengguna aplikasi |

---

## 13. Tabel Pivot / Relasi Many-to-Many (10 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `action_user` | `ActionUser` | 🔗 Relasi / Pivot | Data dan entitas action user |
| 2 | `menu_role` | `MenuRole` | 🔗 Relasi / Pivot | Data dan entitas menu role |
| 3 | `question_bank_tag_pivot` | `QuestionBankTagPivot` | 🔗 Relasi / Pivot | Data dan entitas question bank tag pivot |
| 4 | `role_permission` | `RolePermission` | 🔗 Relasi / Pivot | Data dan entitas role permission |
| 5 | `role_permissions` | `RolePermission` | 🔗 Relasi / Pivot | Data dan entitas role permissions |
| 6 | `role_user` | `RoleUser` | 🔗 Relasi / Pivot | Data dan entitas role user |
| 7 | `trial_registration_education_level` | `TrialRegistrationEducationLevel` | 🔗 Relasi / Pivot | Data dan entitas trial registration education level |
| 8 | `trial_registration_module` | `TrialRegistrationModule` | 🔗 Relasi / Pivot | Data dan entitas trial registration module |
| 9 | `trial_registration_role` | `TrialRegistrationRole` | 🔗 Relasi / Pivot | Data dan entitas trial registration role |
| 10 | `user_roles` | `UserRole` | 🔗 Relasi / Pivot | Data dan entitas user roles |

---

## 14. Tabel Internal Framework Laravel (8 Tabel)

| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Status Modul | Keterangan / Deskripsi Fungsi |
|---|---|---|---|---|
| 1 | `cache` | `Cache` | ⚙️ Sistem Laravel | Data dan entitas cache |
| 2 | `cache_locks` | `CacheLock` | ⚙️ Sistem Laravel | Data dan entitas cache locks |
| 3 | `failed_jobs` | `FailedJob` | ⚙️ Sistem Laravel | Data dan entitas failed jobs |
| 4 | `job_batches` | `JobBatch` | ⚙️ Sistem Laravel | Data dan entitas job batches |
| 5 | `jobs` | `Job` | ⚙️ Sistem Laravel | Data dan entitas jobs |
| 6 | `migrations` | `Migration` | ⚙️ Sistem Laravel | Data dan entitas migrations |
| 7 | `password_reset_tokens` | `PasswordResetToken` | ⚙️ Sistem Laravel | Data dan entitas password reset tokens |
| 8 | `sessions` | `Session` | ⚙️ Sistem Laravel | Data dan entitas sessions |

---

