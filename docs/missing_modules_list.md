# Analisis & Daftar Tabel yang Belum Memiliki Modul

> [!NOTE]
> **Status Pembaruan: SEMUA MODUL BISNIS TELAH SELESAI DIGENERATE (100% SELESAI)**
> Seluruh 221 modul bisnis yang sebelumnya belum memiliki modul CRUD kini telah berhasil digenerate menggunakan perintah:
> `php artisan make:crud-yajra {NamaModul} --columns={daftar_kolom} --no-menu`.
>
> Rincian lengkap status terkini dari seluruh 419 tabel dapat dilihat pada dokumentasi utama: [docs/modules_list.md](file:///c:/wamp64/www/laravel-13/docs/modules_list.md).

Berdasarkan analisis file skema database [`database/laravel-29-09-2026-2.sql`](file:///c:/wamp64/www/laravel-13/database/laravel-29-09-2026-2.sql) dan direktori modul [`app/Modules`](file:///c:/wamp64/www/laravel-13/app/Modules):

- **Total Tabel dalam SQL**: 419 tabel
- **Total Modul Bisnis yang Sudah Tersedia**: **400 modul** (100% tabel bisnis)
- **Tabel Pivot / Many-to-Many**: 10 tabel (tidak memerlukan modul mandiri)
- **Tabel Sistem Laravel Core**: 8 tabel
- **Tabel Cadangan / Backup**: 1 tabel (`menus_bak`)

---

## 1. Keuangan, RKAS, BOS & Pembayaran (50 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul (`StudlyCase`) | Keterangan |
|---|---|---|---|
| 1 | `bank_mutations` | `BankMutation` | Mutasi perbankan |
| 2 | `bank_reconciliation_items` | `BankReconciliationItem` | Detail rekonsiliasi bank |
| 3 | `bos_allocations` | `BosAllocation` | Alokasi dana BOS |
| 4 | `bos_components` | `BosComponent` | Komponen anggaran BOS |
| 5 | `bos_periods` | `BosPeriod` | Periode pelaporan BOS |
| 6 | `bos_realizations` | `BosRealization` | Realisasi belanja BOS |
| 7 | `bos_receipts` | `BosReceipt` | Bukti penerimaan BOS |
| 8 | `bos_report_items` | `BosReportItem` | Item laporan BOS |
| 9 | `bos_reports` | `BosReport` | Laporan BOS |
| 10 | `bos_usage_plans` | `BosUsagePlan` | Rencana penggunaan dana BOS |
| 11 | `budget_details` | `BudgetDetail` | Rincian pos anggaran |
| 12 | `digital_wallets` | `DigitalWallet` | Dompet digital / saldo internal |
| 13 | `exchange_rates` | `ExchangeRate` | Kurs valuta asing |
| 14 | `expense_items` | `ExpenseItem` | Rincian pengeluaran |
| 15 | `expense_payments` | `ExpensePayment` | Pembayaran beban / pengeluaran |
| 16 | `fee_masters` | `FeeMaster` | Master tarif biaya pendidikan |
| 17 | `fee_structures` | `FeeStructure` | Struktur tagihan biaya |
| 18 | `financial_aid_requests` | `FinancialAidRequest` | Pengajuan bantuan finansial |
| 19 | `fiscal_period_closings` | `FiscalPeriodClosing` | Tutup buku periode fiskal |
| 20 | `fiscal_periods` | `FiscalPeriod` | Periode tahun buku fiskal |
| 21 | `invoice_payments` | `InvoicePayment` | Pembayaran invoice |
| 22 | `journal_entry_lines` | `JournalEntryLine` | Baris jurnal akuntansi |
| 23 | `opening_balances` | `OpeningBalance` | Saldo awal akun keuangan |
| 24 | `payment_channels` | `PaymentChannel` | Saluran metode pembayaran |
| 25 | `payment_gateways` | `PaymentGateway` | Konfigurasi payment gateway |
| 26 | `payment_notifications` | `PaymentNotification` | Notifikasi transaksi bayar |
| 27 | `payment_transactions` | `PaymentTransaction` | Riwayat transaksi pembayaran |
| 28 | `payment_webhook_logs` | `PaymentWebhookLog` | Log webhook gateway bayar |
| 29 | `qris_transactions` | `QrisTransaction` | Transaksi QRIS |
| 30 | `rkas_activities` | `RkasActivity` | Kegiatan RKAS sekolah |
| 31 | `rkas_approvals` | `RkasApproval` | Persetujuan dokumen RKAS |
| 32 | `rkas_budgets` | `RkasBudget` | Rencana anggaran RKAS |
| 33 | `rkas_components` | `RkasComponent` | Komponen pembiayaan RKAS |
| 34 | `rkas_documents` | `RkasDocument` | Dokumen lampiran RKAS |
| 35 | `rkas_items` | `RkasItem` | Rincian item belanja RKAS |
| 36 | `rkas_programs` | `RkasProgram` | Program kegiatan RKAS |
| 37 | `rkas_realizations` | `RkasRealization` | Realisasi serapan RKAS |
| 38 | `rkas_report_items` | `RkasReportItem` | Butir laporan realisasi RKAS |
| 39 | `rkas_reports` | `RkasReport` | Laporan evaluasi RKAS |
| 40 | `rkas_revisions` | `RkasRevision` | Riwayat revisi RKAS |
| 41 | `rkas_sources` | `RkasSource` | Sumber dana RKAS |
| 42 | `rkas_standards` | `RkasStandard` | Standar Nasional Pendidikan (SNP) |
| 43 | `rkas_sub_activities` | `RkasSubActivity` | Sub-kegiatan RKAS |
| 44 | `student_installments` | `StudentInstallment` | Angsuran / cicilan tagihan santri |
| 45 | `student_invoice_batches` | `StudentInvoiceBatch` | Batch pembuatan invoice santri |
| 46 | `student_invoices` | `StudentInvoice` | Tagihan / faktur santri |
| 47 | `vendor_invoice_lines` | `VendorInvoiceLine` | Baris invoice tagihan vendor |
| 48 | `vendor_invoices` | `VendorInvoice` | Faktur tagihan dari vendor |
| 49 | `vendor_payments` | `VendorPayment` | Realisasi pembayaran ke vendor |
| 50 | `payroll_payments` | `PayrollPayment` | Pembayaran gaji karyawan |

---

## 2. Akademik, Kurikulum, Nilai & Rapor (26 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `course_prerequisites` | `CoursePrerequisite` | Prasyarat mata kuliah/pelajaran |
| 2 | `courses` | `Course` | Kursus / mata perkuliahan |
| 3 | `grade_boundaries` | `GradeBoundary` | Ambang batas interval nilai |
| 4 | `grade_scales` | `GradeScale` | Skala penilaian (huruf/angka) |
| 5 | `grades` | `Grade` | Rekapitulasi nilai siswa |
| 6 | `graduations` | `Graduation` | Data kelulusan siswa |
| 7 | `learning_materials` | `LearningMaterial` | Bahan ajar / materi belajar |
| 8 | `learning_outcomes` | `LearningOutcome` | Capaian Pembelajaran (CP) |
| 9 | `lesson_plans` | `LessonPlan` | RPP / Modul Ajar |
| 10 | `lesson_progress` | `LessonProgress` | Progres penyampaian materi |
| 11 | `lessons` | `Lesson` | Pertemuan / sesi belajar |
| 12 | `mastery_tracking` | `MasteryTracking` | Pelacakan ketuntasan belajar |
| 13 | `report_card_components` | `ReportCardComponent` | Komponen nilai pada rapor |
| 14 | `report_card_grades` | `ReportCardGrade` | Data nilai rapor siswa |
| 15 | `report_card_notes` | `ReportCardNote` | Catatan wali kelas di rapor |
| 16 | `report_card_signatures` | `ReportCardSignature` | Tanda tangan digital rapor |
| 17 | `report_card_template_curriculums` | `ReportCardTemplateCurriculum` | Relasi template rapor ke kurikulum |
| 18 | `report_card_template_sections` | `ReportCardTemplateSection` | Bagian/layout template rapor |
| 19 | `report_periods` | `ReportPeriod` | Periode pembagian rapor |
| 20 | `room_constraints` | `RoomConstraint` | Batasan alokasi ruangan jadwal |
| 21 | `student_assessments` | `StudentAssessment` | Penilaian asesmen siswa |
| 22 | `student_khs` | `StudentKhs` | Kartu Hasil Studi (KHS) |
| 23 | `student_krs` | `StudentKrs` | Kartu Rencana Studi (KRS) |
| 24 | `student_krs_items` | `StudentKrsItem` | Rincian matakuliah di KRS |
| 25 | `student_rubric_scores` | `StudentRubricScore` | Skor rubrik penilaian siswa |
| 26 | `thesis_supervisions` | `ThesisSupervision` | Bimbingan tugas akhir / skripsi |
| 27 | `yudisiums` | `Yudisium` | Penetapan yudisium kelulusan |

---

## 3. Siswa, Beasiswa, Konseling & Alumni (17 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `counseling_sessions` | `CounselingSession` | Sesi Bimbingan Konseling (BK) |
| 2 | `financial_aid_requests` | `FinancialAidRequest` | Pengajuan keringanan biaya |
| 3 | `scholarship_applications` | `ScholarshipApplication` | Pendaftaran beasiswa |
| 4 | `scholarship_disbursements` | `ScholarshipDisbursement` | Pencairan dana beasiswa |
| 5 | `scholarship_recipients` | `ScholarshipRecipient` | Data penerima beasiswa |
| 6 | `scholarship_types` | `ScholarshipType` | Jenis-jenis beasiswa |
| 7 | `scholarships` | `Scholarship` | Master program beasiswa |
| 8 | `student_ranges` | `StudentRange` | Rentang angkatan/peringkat santri |
| 9 | `student_transcripts` | `StudentTranscript` | Transkrip akademik siswa |
| 10 | `tracer_studies` | `TracerStudy` | Survei penelusuran alumni |
| 11 | `tracer_study_questions` | `TracerStudyQuestion` | Kuisioner tracer study |
| 12 | `tracer_study_responses` | `TracerStudyResponse` | Jawaban kuesioner tracer study |
| 13 | `extracurricular_achievements` | `ExtracurricularAchievement` | Prestasi ekstrakurikuler |
| 14 | `extracurricular_coaches` | `ExtracurricularCoach` | Pelatih / pembina ekskul |
| 15 | `extracurricular_fees` | `ExtracurricularFee` | Biaya pendaftaran ekskul |
| 16 | `extracurricular_schedules` | `ExtracurricularSchedule` | Jadwal latihan ekskul |
| 17 | `enrollment_conversions` | `EnrollmentConversion` | Konversi status registrasi santri |

---

## 4. Penerimaan Siswa Baru / PPDB / PMB (9 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `applicant_achievements` | `ApplicantAchievement` | Prestasi pendaftar baru |
| 2 | `applicant_categories` | `ApplicantCategory` | Kategori jalur pendaftaran |
| 3 | `applicant_domiciles` | `ApplicantDomicile` | Domisili / zonasi pendaftar |
| 4 | `applicant_notifications` | `ApplicantNotification` | Notifikasi status pendaftar |
| 5 | `applicant_parents` | `ApplicantParent` | Data orang tua pendaftar |
| 6 | `applicant_registrations` | `ApplicantRegistration` | Formulir pendaftaran calon santri |
| 7 | `applicant_status_histories` | `ApplicantStatusHistory` | Riwayat perubahan status PPDB |
| 8 | `applicant_statuses` | `ApplicantStatus` | Status verifikasi pendaftar |
| 9 | `applicant_support_programs` | `ApplicantSupportProgram` | Program afirmasi / beasiswa PPDB |

---

## 5. Kepegawaian, HRD, Payroll & Absensi (12 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `attendance_corrections` | `AttendanceCorrection` | Koreksi presensi pegawai |
| 2 | `attendance_import_batches` | `AttendanceImportBatch` | Batch impor presensi mesin finger |
| 3 | `employee_attendances` | `EmployeeAttendance` | Rekap kehadiran pegawai |
| 4 | `employee_bonuses` | `EmployeeBonus` | Bonus / insentif pegawai |
| 5 | `employee_deductions` | `EmployeeDeduction` | Potongan gaji karyawan |
| 6 | `payroll_detail_components` | `PayrollDetailComponent` | Rincian komponen slip gaji |
| 7 | `payroll_payment_batches` | `PayrollPaymentBatch` | Batch pembayaran payroll bank |
| 8 | `permission_requests` | `PermissionRequest` | Pengajuan izin / dinas luar |
| 9 | `teacher_comments` | `TeacherComment` | Umpan balik evaluasi guru |
| 10 | `work_calendar_holidays` | `WorkCalendarHoliday` | Kalender libur kerja institusi |
| 11 | `work_calendars` | `WorkCalendar` | Kalender kerja shift / divisi |
| 12 | `work_schedules` | `WorkSchedule` | Jadwal shift kerja karyawan |

---

## 6. Perpustakaan & Koleksi Buku (13 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `book_authors` | `BookAuthor` | Master pengarang buku |
| 2 | `book_categories` | `BookCategory` | Kategori koleksi pustaka |
| 3 | `book_copies` | `BookCopy` | Salinan eksemplar buku |
| 4 | `book_publishers` | `BookPublisher` | Master penerbit buku |
| 5 | `book_reservations` | `BookReservation` | Reservasi / booking buku |
| 6 | `digital_library_access_logs` | `DigitalLibraryAccessLog` | Log unduh/baca e-book |
| 7 | `digital_library_items` | `DigitalLibraryItem` | Katalog buku digital / PDF |
| 8 | `library_books` | `LibraryBook` | Master katalog buku fisik |
| 9 | `library_fines` | `LibraryFine` | Denda keterlambatan buku |
| 10 | `library_loans` | `LibraryLoan` | Peminjaman buku |
| 11 | `library_memberships` | `LibraryMembership` | Kartu anggota perpustakaan |
| 12 | `library_stock_opname` | `LibraryStockOpname` | Stock opname buku perpustakaan |
| 13 | `library_visits` | `LibraryVisit` | Buku tamu kunjungan perpustakaan |

---

## 7. Pesantren & Keasramaan (10 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `dormitory_assignments` | `DormitoryAssignment` | Penempatan kamar santri |
| 2 | `dormitory_movements` | `DormitoryMovement` | Mutasi / perpindahan kamar |
| 3 | `dormitory_permits` | `DormitoryPermit` | Surat izin keluar asrama/pesantren |
| 4 | `dormitory_rooms` | `DormitoryRoom` | Data kamar asrama santri |
| 5 | `kitab_references` | `KitabReference` | Referensi kitab kuning / rujukan |
| 6 | `pesantren_activities` | `PesantrenActivity` | Jadwal rutinitas kepesantrenan |
| 7 | `pesantren_rule_violations` | `PesantrenRuleViolation` | Pelanggaran tata tertib pondok |
| 8 | `pesantren_rules` | `PesantrenRule` | Aturan & sanksi kedisiplinan |
| 9 | `sorogan_evaluations` | `SoroganEvaluation` | Nilai setoran sorogan/bandongan |
| 10 | `sorogan_schedules` | `SoroganSchedule` | Jadwal sorogan ngaji kitab |

---

## 8. Inventaris, Aset, Gudang & Pengadaan (19 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `asset_disposals` | `AssetDisposal` | Penghapusan / lelang aset |
| 2 | `asset_maintenances` | `AssetMaintenance` | Perawatan / servis aset |
| 3 | `fixed_assets` | `FixedAsset` | Aset tetap institusi |
| 4 | `goods_receipt_items` | `GoodsReceiptItem` | Rincian penerimaan barang |
| 5 | `goods_receipts` | `GoodsReceipt` | Bukti tanda terima barang (LPB) |
| 6 | `inventory_items` | `InventoryItem` | Master barang inventaris |
| 7 | `inventory_stocks` | `InventoryStock` | Posisi stok gudang |
| 8 | `purchase_order_items` | `PurchaseOrderItem` | Rincian item PO |
| 9 | `purchase_orders` | `PurchaseOrder` | Surat Pesanan (PO) |
| 10 | `purchase_request_items` | `PurchaseRequestItem` | Rincian kebutuhan barang |
| 11 | `purchase_requests` | `PurchaseRequest` | Surat Permintaan Pembelian (PR) |
| 12 | `stock_adjustment_items` | `StockAdjustmentItem` | Rincian penyesuaian stok |
| 13 | `stock_adjustments` | `StockAdjustment` | Penyesuaian stok barang |
| 14 | `stock_movements` | `StockMovement` | Kartu mutasi arus stok |
| 15 | `stock_transfer_items` | `StockTransferItem` | Item transfer antar gudang |
| 16 | `stock_transfers` | `StockTransfer` | Pemindahan stok antar lokasi |
| 17 | `unit_of_measurements` | `UnitOfMeasurement` | Satuan ukur barang (UoM) |
| 18 | `uom_conversions` | `UomConversion` | Konversi antar satuan |
| 19 | `warehouses` | `Warehouse` | Lokasi gudang penyimpanan |

---

## 9. Ujian, Bank Soal, CBT & Kuis (13 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `assessment_rubrics` | `AssessmentRubric` | Rubrik penilaian evaluasi |
| 2 | `assignments_quizzes` | `AssignmentsQuiz` | Tugas kuis online |
| 3 | `cbt_features` | `CbtFeature` | Fitur pembatasan tes CBT |
| 4 | `cbt_proctoring_evidence` | `CbtProctoringEvidence` | Bukti kecurangan pengawas CBT |
| 5 | `cbt_schedule_features` | `CbtScheduleFeature` | Konfigurasi fitur jadwal tes |
| 6 | `cbt_schedule_proctors` | `CbtScheduleProctor` | Petugas pengawas ruang ujian |
| 7 | `exam_package_questions` | `ExamPackageQuestion` | Relasi soal ke paket ujian |
| 8 | `question_bank_versions` | `QuestionBankVersion` | Versi riwayat butir soal |
| 9 | `question_matching_pairs` | `QuestionMatchingPair` | Pasangan soal menjodohkan |
| 10 | `question_media` | `QuestionMedia` | Lampiran gambar/audio soal |
| 11 | `question_media_annotations` | `QuestionMediaAnnotation` | Anotasi media pada soal |
| 12 | `question_options` | `QuestionOption` | Opsi pilihan jawaban soal |
| 13 | `quiz_attempts` | `QuizAttempt` | Riwayat pengerjaan kuis siswa |

---

## 10. Kantin & Layanan Kesehatan / UKS (8 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `canteen_menus` | `CanteenMenu` | Menu makanan/minuman kantin |
| 2 | `canteen_order_items` | `CanteenOrderItem` | Rincian pesanan kantin |
| 3 | `canteen_orders` | `CanteenOrder` | Transaksi pemesanan kantin |
| 4 | `canteen_settlements` | `CanteenSettlement` | Settlement pembayaran tenant |
| 5 | `canteen_tenants` | `CanteenTenant` | Penyewa gerai / tenant kantin |
| 6 | `clinic_medicines` | `ClinicMedicine` | Inventaris obat poliklinik/UKS |
| 7 | `clinic_prescriptions` | `ClinicPrescription` | Resep obat pemeriksaan siswa |
| 8 | `clinic_visits` | `ClinicVisit` | Rekam medis kunjungan klinik/UKS |

---

## 11. E-Learning & Forum Diskusi (4 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `discussion_replies` | `DiscussionReply` | Balasan komentar diskusi |
| 2 | `discussions` | `Discussion` | Topik forum diskusi kelas |
| 3 | `courses` | `Course` | Kelas pembelajaran daring |
| 4 | `course_prerequisites` | `CoursePrerequisite` | Syarat pengambilan kursus |

---

## 12. Workflow & Persetujuan / Approval (6 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `approval_histories` | `ApprovalHistory` | Jejak rekam persetujuan |
| 2 | `approval_requests` | `ApprovalRequest` | Permintaan persetujuan |
| 3 | `promotion_approvals` | `PromotionApproval` | Persetujuan kenaikan kelas |
| 4 | `workflow_approvers` | `WorkflowApprover` | Daftar pejabat penyetuju |
| 5 | `workflow_definitions` | `WorkflowDefinition` | Konfigurasi jalur alur kerja |
| 6 | `workflow_steps` | `WorkflowStep` | Tahapan urutan verifikasi |

---

## 13. Komunikasi, Notifikasi, Log Pengiriman & Sistem (20 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `activity_logs` | `ActivityLog` | Audit jejak aktivitas user |
| 2 | `dapodik_mappings` | `DapodikMapping` | Pemetaan kode data Dapodik |
| 3 | `dapodik_sync_logs` | `DapodikSyncLog` | Log sinkronisasi Dapodik |
| 4 | `dapodik_sync_schedules` | `DapodikSyncSchedule` | Jadwal cron sinkronisasi |
| 5 | `document_access_logs` | `DocumentAccessLog` | Log akses unduh dokumen |
| 6 | `email_logs` | `EmailLog` | Log riwayat email terkirim |
| 7 | `employee_attendance_logs` | `EmployeeAttendanceLog` | Raw log tap mesin presensi |
| 8 | `finance_audit_logs` | `FinanceAuditLog` | Audit log transaksi keuangan |
| 9 | `gps_telemetry_logs` | `GpsTelemetryLog` | Log koordinat GPS armada sekolah |
| 10 | `message_participants` | `MessageParticipant` | Partisipan ruang pesan percakapan |
| 11 | `message_threads` | `MessageThread` | Utas obrolan / pesan pribadi |
| 12 | `messages` | `Message` | Isi pesan perpesanan internal |
| 13 | `notification_preferences` | `NotificationPreference` | Preferensi saluran notifikasi |
| 14 | `notification_templates` | `NotificationTemplate` | Template pesan WA/email/SMS |
| 15 | `notifications` | `Notification` | Daftar notifikasi user |
| 16 | `push_notification_tokens` | `PushNotificationToken` | Token FCM mobile app |
| 17 | `sms_logs` | `SmsLog` | Log pengiriman SMS Gateway |
| 18 | `whatsapp_logs` | `WhatsappLog` | Log pengiriman notifikasi WhatsApp |
| 19 | `audit_trails` | `AuditTrail` | Jejak audit keamanan sistem |
| 20 | `scheduled_jobs` | `ScheduledJob` | Antrean tugas terjadwal |

---

## 14. Master Data & Referensi Umum (11 Tabel)
| No | Nama Tabel | Rekomendasi Nama Modul | Keterangan |
|---|---|---|---|
| 1 | `customers` | `Customer` | Data pelanggan eksternal |
| 2 | `digital_signatures` | `DigitalSignature` | Kunci tanda tangan digital |
| 3 | `document_number_sequences` | `DocumentNumberSequence` | Penomoran otomatis surat/dokumen |
| 4 | `document_versions` | `DocumentVersion` | Riwayat revisi arsip dokumen |
| 5 | `drivers` | `Driver` | Pengemudi armada sekolah |
| 6 | `file_uploads` | `FileUpload` | Manajemen file berkas unduhan |
| 7 | `genders` | `Gender` | Master jenis kelamin |
| 8 | `generated_reports` | `GeneratedReport` | Arsip laporan yang digenerate |
| 9 | `identity_types` | `IdentityType` | Jenis tanda pengenal (KTP/NISN/dll) |
| 10 | `navigation` | `Navigation` | Navigasi menu dinamis portal |
| 11 | `menus_bak` | `MenusBak` | Tabel backup menu *(bisa dihapus)* |

---

## 15. Tabel Pivot / Relasi Many-to-Many (10 Tabel)
> *Tabel ini berfungsi sebagai tabel penghubung (pivot) antar entitas, biasanya tidak memerlukan modul CRUD tersendiri melainkan dikelola langsung melalui relasi `belongsToMany`.*

1. `action_user` *(Penghubung Action $\leftrightarrow$ User)*
2. `menu_role` *(Penghubung Menu $\leftrightarrow$ Role)*
3. `question_bank_tag_pivot` *(Penghubung Bank Soal $\leftrightarrow$ Tag)*
4. `role_permission` *(Penghubung Role $\leftrightarrow$ Permission)*
5. `role_permissions` *(Penghubung Role $\leftrightarrow$ Permissions)*
6. `role_user` *(Penghubung Role $\leftrightarrow$ User)*
7. `trial_registration_education_level` *(Penghubung Uji Coba $\leftrightarrow$ Jenjang)*
8. `trial_registration_module` *(Penghubung Uji Coba $\leftrightarrow$ Modul)*
9. `trial_registration_role` *(Penghubung Uji Coba $\leftrightarrow$ Role)*
10. `user_roles` *(Penghubung User $\leftrightarrow$ Role)*

---

## 16. Tabel Bawaan Framework Laravel (8 Tabel)
> *Dikelola otomatis oleh engine Laravel, tidak perlu dibuatkan modul.*

1. `migrations`
2. `failed_jobs`
3. `jobs`
4. `job_batches`
5. `sessions`
6. `cache`
7. `cache_locks`
8. `password_reset_tokens`
