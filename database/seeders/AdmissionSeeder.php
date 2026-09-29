<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdmissionSeeder extends Seeder
{
    public function run()
    {
        // 1. Ensure school exists
        $school = DB::table('schools')->first();
        if (!$school) {
            $schoolId = DB::table('schools')->insertGetId([
                'name' => 'SMAN 3 Bandung',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $schoolId = $school->id;
            // Optionally update school name to SMAN 3 Bandung if user is testing this view
            DB::table('schools')->where('id', $schoolId)->update(['name' => 'SMAN 3 Bandung']);
        }

        // 2. Ensure Academic Year exists
        $academicYear = DB::table('academic_years')->where('name', 'like', '%2025%')->first();
        if (!$academicYear) {
            $academicYear = DB::table('academic_years')->first();
        }
        $academicYearId = $academicYear ? $academicYear->id : 1;

        // 3. Clear existing admission data to avoid duplicates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admission_announcements')->truncate();
        DB::table('admission_document_verifications')->truncate();
        DB::table('admission_interviews')->truncate();
        DB::table('admission_paths')->truncate();
        DB::table('admission_quota_settings')->truncate();
        DB::table('admission_registration_fees')->truncate();
        DB::table('admission_selections')->truncate();
        DB::table('admission_test_results')->truncate();
        DB::table('admission_test_schedules')->truncate();
        DB::table('admission_timelines')->truncate();
        DB::table('admissions')->truncate();
        DB::table('applicant_achievements')->truncate();
        DB::table('applicant_categories')->truncate();
        DB::table('applicant_domiciles')->truncate();
        DB::table('applicant_invoices')->truncate();
        DB::table('applicant_parents')->truncate();
        DB::table('applicant_payments')->truncate();
        DB::table('applicant_registrations')->truncate();
        DB::table('applicant_status_histories')->truncate();
        DB::table('applicant_statuses')->truncate();
        DB::table('applicant_support_programs')->truncate();
        DB::table('applicants')->truncate();
        DB::table('application_documents')->truncate();
        DB::table('activity_logs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        // 4. Create Admission
        $admissionId = DB::table('admissions')->insertGetId([
            'school_id' => $schoolId,
            'academic_year_id' => $academicYearId,
            'name' => 'PPDB 2025/2026 - SMAN 3 Bandung',
            'code' => 'PPDB-2025',
            'type' => 'REGULER',
            'start_date' => Carbon::now()->subDays(6)->toDateString(),
            'end_date' => Carbon::now()->addDays(2)->addHours(14)->addMinutes(35)->toDateString(),
            'quota' => 400,
            'registration_fee' => 0.00,
            'min_age' => 14,
            'max_age' => 21,
            'requirements' => 'KK, Akta Kelahiran, Ijazah SMP/MTs, SPTJM Keabsahan Dokumen.',
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 5. Create Admission Paths
        $paths = [
            [
                'admission_id' => $admissionId,
                'code' => 'ZONASI',
                'name' => 'Jalur Zonasi Domisili (50%)',
                'description' => 'Jalur pendaftaran berdasarkan jarak radius domisili calon siswa ke sekolah.',
                'quota' => 200,
                'sort_order' => 1,
                'is_active' => 1,
                'open_date' => Carbon::now()->subDays(6)->toDateString(),
                'close_date' => Carbon::now()->addDays(2)->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'admission_id' => $admissionId,
                'code' => 'AFIRMASI',
                'name' => 'Jalur Afirmasi / KETM / ABK (20%)',
                'description' => 'Jalur khusus untuk keluarga ekonomi tidak mampu (KIP/PIP) dan disabilitas.',
                'quota' => 80,
                'sort_order' => 2,
                'is_active' => 1,
                'open_date' => Carbon::now()->subDays(6)->toDateString(),
                'close_date' => Carbon::now()->addDays(2)->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'admission_id' => $admissionId,
                'code' => 'PRESTASI',
                'name' => 'Jalur Prestasi Nilai & Kejuaraan (25%)',
                'description' => 'Jalur berdasarkan prestasi nilai rapor serta piagam kejuaraan akademik & non-akademik.',
                'quota' => 100,
                'sort_order' => 3,
                'is_active' => 1,
                'open_date' => Carbon::now()->subDays(6)->toDateString(),
                'close_date' => Carbon::now()->addDays(2)->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'admission_id' => $admissionId,
                'code' => 'PERPINDAHAN',
                'name' => 'Perpindahan Tugas Orang Tua (5%)',
                'description' => 'Jalur mutasi kerja instansi / dinas orang tua.',
                'quota' => 20,
                'sort_order' => 4,
                'is_active' => 1,
                'open_date' => Carbon::now()->subDays(6)->toDateString(),
                'close_date' => Carbon::now()->addDays(2)->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $pathIds = [];
        foreach ($paths as $p) {
            $pathIds[$p['code']] = DB::table('admission_paths')->insertGetId($p);
        }

        // 6. Create Admission Quota Settings
        $quotaData = [
            ['path_code' => 'ZONASI', 'quota' => 200, 'filled' => 200, 'reserved' => 0],
            ['path_code' => 'AFIRMASI', 'quota' => 80, 'filled' => 72, 'reserved' => 8],
            ['path_code' => 'PRESTASI', 'quota' => 100, 'filled' => 94, 'reserved' => 6],
            ['path_code' => 'PERPINDAHAN', 'quota' => 20, 'filled' => 18, 'reserved' => 2],
        ];
        foreach ($quotaData as $q) {
            DB::table('admission_quota_settings')->insert([
                'admission_id' => $admissionId,
                'admission_path_id' => $pathIds[$q['path_code']],
                'quota' => $q['quota'],
                'filled' => $q['filled'],
                'reserved' => $q['reserved'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 7. Create Timelines
        $timelines = [
            [
                'admission_id' => $admissionId,
                'phase_name' => 'Sosialisasi & Uji Coba Akun Siswa',
                'start_date' => '2025-06-01',
                'end_date' => '2025-06-08',
                'description' => 'Pembuatan akun mandiri orang tua dan integrasi NIK Disdukcapil.',
                'order_number' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'admission_id' => $admissionId,
                'phase_name' => 'Pendaftaran Tahap 1 & Verifikasi Fisik',
                'start_date' => '2025-06-10',
                'end_date' => '2025-06-18',
                'description' => 'Zonasi murni 50% & Afirmasi KIP/Disabilitas 20%. Penyerahan berkas verifikasi.',
                'order_number' => 2,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'admission_id' => $admissionId,
                'phase_name' => 'Sidang Pleno Dewan Guru & Penetapan',
                'start_date' => '2025-06-20',
                'end_date' => '2025-06-20',
                'description' => 'Penguncian nilai akhir ranking zonasi dan verifikasi dokumen sanggahan.',
                'order_number' => 3,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'admission_id' => $admissionId,
                'phase_name' => 'Pengumuman Kelulusan & Daftar Ulang',
                'start_date' => '2025-06-23',
                'end_date' => '2025-06-25',
                'description' => 'Penayangan hasil di web portal terbuka & notifikasi otomatis calon murid.',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('admission_timelines')->insert($timelines);

        // 8. Create Applicants & Associated Data
        $applicantsData = [
            [
                'name' => 'Siti Nurhaliza',
                'nik' => '3273102901081081',
                'nisn' => '0089123456',
                'prev_school' => 'SMPN 5 Bandung',
                'school_loc' => 'Kel. Merdeka, Kec. Sumur Bandung',
                'reg_num' => 'PPDB25-300101',
                'status' => 'VERIFIED',
                'score' => 96.00,
                'rank' => 1,
                'path' => 'ZONASI',
                'distance' => 142.00,
                'docs' => ['KK', 'Akta', 'SPTJM'],
                'verif_status' => 'verified',
                'reg_time' => Carbon::now()->subDays(4)->setTime(8, 15),
            ],
            [
                'name' => 'Muhammad Farhan Al-Hafiz',
                'nik' => '3273102901080002',
                'nisn' => '0089283719',
                'prev_school' => 'SMP Negeri 5 Bandung',
                'school_loc' => 'Kel. Merdeka, Kec. Sumur Bandung',
                'reg_num' => 'PPDB25-300184',
                'status' => 'REGISTERED',
                'score' => 92.50,
                'rank' => 2,
                'path' => 'ZONASI',
                'distance' => 840.00,
                'docs' => ['KK', 'Akta', 'SPTJM'],
                'verif_status' => 'pending',
                'reg_time' => Carbon::now()->subDays(1)->setTime(8, 30),
            ],
            [
                'name' => 'Annisa Rahmawati Putri',
                'nik' => '3273114502090001',
                'nisn' => '0091823901',
                'prev_school' => 'SMP Negeri 2 Bandung',
                'school_loc' => 'Kel. Citarum, Kec. Bandung Wetan',
                'reg_num' => 'PPDB25-300185',
                'status' => 'VERIFIED',
                'score' => 88.00,
                'rank' => 12,
                'path' => 'AFIRMASI',
                'distance' => 650.00,
                'kip_id' => '8941094012',
                'docs' => ['KK', 'KIP'],
                'verif_status' => 'verified',
                'reg_time' => Carbon::now()->subDays(1)->setTime(8, 45),
            ],
            [
                'name' => 'Bagas Adityo Nugroho',
                'nik' => '3273181208080004',
                'nisn' => '0081293847',
                'prev_school' => 'SMP Taruna Bakti',
                'school_loc' => 'Kel. Cihapit, Kec. Bandung Wetan',
                'reg_num' => 'PPDB25-300186',
                'status' => 'REVISION_REQUIRED',
                'score' => 93.85,
                'rank' => 15,
                'path' => 'PRESTASI',
                'distance' => 1100.00,
                'achievement' => 'Juara 1 O2SN Renang',
                'docs' => ['KK', 'Piagam'],
                'verif_status' => 'revision_required',
                'reg_time' => Carbon::now()->subDays(1)->setTime(9, 12),
            ],
            [
                'name' => 'Rifki Kurniawan (Garis Batas)',
                'nik' => '3273102901089912',
                'nisn' => '0089987654',
                'prev_school' => 'SMPN 7 Bandung',
                'school_loc' => 'Kel. Malabar, Kec. Lengkong',
                'reg_num' => 'PPDB25-300200',
                'status' => 'VERIFIED',
                'score' => 81.20,
                'rank' => 200,
                'path' => 'ZONASI',
                'distance' => 1238.00,
                'docs' => ['KK', 'Akta'],
                'verif_status' => 'verified',
                'reg_time' => Carbon::now()->subDays(2)->setTime(10, 00),
            ],
            [
                'name' => 'Naila Rahmadani',
                'nik' => '3273102901087723',
                'nisn' => '0089554433',
                'prev_school' => 'SMPN 13 Bandung',
                'school_loc' => 'Kel. Tamansari, Kec. Bandung Wetan',
                'reg_num' => 'PPDB25-300210',
                'status' => 'VERIFIED',
                'score' => 89.42,
                'rank' => 100,
                'path' => 'PRESTASI',
                'distance' => 1450.00,
                'achievement' => 'Juara 2 OSN Matematika',
                'docs' => ['KK', 'Piagam'],
                'verif_status' => 'verified',
                'reg_time' => Carbon::now()->subDays(2)->setTime(11, 20),
            ],
            [
                'name' => 'Dwi Cahyo Pratama',
                'nik' => '3273102901086655',
                'nisn' => '0087654321',
                'prev_school' => 'SMPN 1 Bandung',
                'school_loc' => 'Kel. Babakan Ciamis, Kec. Sumur Bandung',
                'reg_num' => 'PPDB25-300215',
                'status' => 'REGISTERED',
                'score' => 87.00,
                'rank' => 45,
                'path' => 'ZONASI',
                'distance' => 920.00,
                'docs' => ['KK', 'Akta', 'SPTJM'],
                'verif_status' => 'pending',
                'reg_time' => Carbon::now()->subHours(5),
            ],
            [
                'name' => 'Aulia Zahra Salsabila',
                'nik' => '3273102901083321',
                'nisn' => '0098761234',
                'prev_school' => 'SMP BPK Penabur',
                'school_loc' => 'Kel. Kebon Pisang, Kec. Sumur Bandung',
                'reg_num' => 'PPDB25-300222',
                'status' => 'REGISTERED',
                'score' => 91.00,
                'rank' => 38,
                'path' => 'AFIRMASI',
                'distance' => 780.00,
                'kip_id' => '8941094055',
                'docs' => ['KK', 'KIP'],
                'verif_status' => 'pending',
                'reg_time' => Carbon::now()->subHours(2),
            ],
        ];

        foreach ($applicantsData as $app) {
            $applicantId = DB::table('applicants')->insertGetId([
                'school_id' => $schoolId,
                'admission_id' => $admissionId,
                'name' => $app['name'],
                'nik' => $app['nik'],
                'birth_place' => 'Bandung',
                'birth_date' => '2009-05-15',
                'gender' => 'L',
                'address' => $app['school_loc'],
                'phone' => '081234567890',
                'email' => strtolower(str_replace([' ', '(', ')'], ['', '', ''], $app['name'])) . '@gmail.com',
                'previous_school' => $app['prev_school'],
                'registration_number' => $app['reg_num'],
                'status' => $app['status'],
                'total_score' => $app['score'],
                'ranking' => $app['rank'],
                'registered_at' => $app['reg_time'],
                'is_active' => 1,
                'created_at' => $app['reg_time'],
                'updated_at' => $app['reg_time'],
            ]);

            // Domicile
            DB::table('applicant_domiciles')->insert([
                'applicant_id' => $applicantId,
                'address' => $app['school_loc'],
                'distance_to_school_m' => $app['distance'],
                'is_verified' => in_array($app['verif_status'], ['verified', 'revision_required']) ? 1 : 0,
                'created_at' => $app['reg_time'],
                'updated_at' => $app['reg_time'],
            ]);

            // Documents & Verifications
            foreach ($app['docs'] as $docType) {
                $docId = DB::table('application_documents')->insertGetId([
                    'applicant_id' => $applicantId,
                    'document_type' => $docType,
                    'document_url' => '/uploads/documents/' . strtolower($docType) . '_' . $app['reg_num'] . '.pdf',
                    'file_name' => $docType . '_' . $app['reg_num'] . '.pdf',
                    'file_size' => 1024 * 512,
                    'verified' => $app['verif_status'] === 'verified' ? 1 : 0,
                    'uploaded_at' => $app['reg_time'],
                    'created_at' => $app['reg_time'],
                    'updated_at' => $app['reg_time'],
                ]);

                DB::table('admission_document_verifications')->insert([
                    'applicant_id' => $applicantId,
                    'application_document_id' => $docId,
                    'admission_id' => $admissionId,
                    'document_type' => $docType,
                    'document_number' => 'DOC-' . rand(100000, 999999),
                    'file_path' => '/uploads/documents/' . strtolower($docType) . '_' . $app['reg_num'] . '.pdf',
                    'verification_status' => $app['verif_status'],
                    'verification_method' => 'online',
                    'is_mandatory' => 1,
                    'is_valid' => $app['verif_status'] === 'verified' ? 1 : 0,
                    'revision_notes' => $app['verif_status'] === 'revision_required' ? 'Sertifikat piagam belum dilegalisir oleh KONI/Dispora Kota.' : null,
                    'created_at' => $app['reg_time'],
                    'updated_at' => $app['reg_time'],
                ]);
            }

            // Support program if any
            if (!empty($app['kip_id'])) {
                DB::table('applicant_support_programs')->insert([
                    'applicant_id' => $applicantId,
                    'program_type' => 'kip',
                    'card_number' => $app['kip_id'],
                    'program_name' => 'Kartu Indonesia Pintar (KIP)',
                    'validation_status' => 'valid',
                    'created_at' => $app['reg_time'],
                    'updated_at' => $app['reg_time'],
                ]);
            }

            // Achievement if any
            if (!empty($app['achievement'])) {
                DB::table('applicant_achievements')->insert([
                    'applicant_id' => $applicantId,
                    'achievement_type' => 'sport',
                    'title' => $app['achievement'],
                    'level' => 'province',
                    'rank' => 'Juara 1',
                    'score_point' => $app['score'],
                    'created_at' => $app['reg_time'],
                    'updated_at' => $app['reg_time'],
                ]);
            }

            // Selection ranking
            DB::table('admission_selections')->insert([
                'admission_id' => $admissionId,
                'applicant_id' => $applicantId,
                'selection_type' => $app['path'],
                'score' => $app['score'],
                'result' => $app['rank'] <= 200 ? 'passed' : 'pending',
                'created_at' => $app['reg_time'],
                'updated_at' => $app['reg_time'],
            ]);
        }

        // 9. Add Activity Log
        DB::table('activity_logs')->insert([
            'user_id' => 1,
            'user_role' => 'Verifikator Berkas',
            'action' => 'VALIDATE_DOCUMENT',
            'module' => 'admission_document_verifications',
            'description' => 'Verifikator Budi Santoso, S.Pd memvalidasi berkas pendaftar PPDB25-300185 (SMPN 2 Bandung)',
            'status' => 'success',
            'created_at' => Carbon::now()->subMinutes(24),
        ]);
    }
}
