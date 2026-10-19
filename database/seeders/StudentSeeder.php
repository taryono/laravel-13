<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // ============ Ambil data referensi ============
        $schools = DB::table('schools')
            ->select('id', 'organization_id')
            ->get();

        $organizations = DB::table('organizations')->pluck('id')->toArray();

        // Applicant boleh kosong (nullable FK)
        $applicants = DB::table('applicants')->pluck('id')->toArray();

        // Validasi dependency wajib
        if ($schools->isEmpty() || empty($organizations)) {
            $this->command->warn('Skip StudentSeeder: tabel schools atau organizations masih kosong.');
            return;
        }

        // ============ Master data siswa ============
        $maleNames = [
            'Ahmad',
            'Budi',
            'Cahyo',
            'Dimas',
            'Eko',
            'Fajar',
            'Gilang',
            'Hendra',
            'Ivan',
            'Joko',
            'Krisna',
            'Lukman',
            'Made',
            'Nanda',
            'Oka',
            'Putu',
            'Rangga',
            'Sandi',
            'Teguh',
            'Umar',
            'Wahyu',
            'Yoga',
            'Zaki',
            'Arif',
            'Bayu',
            'Candra',
            'Doni',
            'Erwin',
        ];

        $femaleNames = [
            'Ayu',
            'Bella',
            'Citra',
            'Dewi',
            'Eka',
            'Fitri',
            'Gita',
            'Hana',
            'Indah',
            'Jihan',
            'Kartika',
            'Lestari',
            'Maya',
            'Nadia',
            'Olivia',
            'Putri',
            'Rina',
            'Sari',
            'Tiara',
            'Umi',
            'Vina',
            'Wulan',
            'Yuni',
            'Zahra',
            'Anisa',
            'Bunga',
            'Dinda',
            'Elsa',
        ];

        $lastNames = [
            'Pratama',
            'Wijaya',
            'Santoso',
            'Kurniawan',
            'Hidayat',
            'Saputra',
            'Maulana',
            'Rahayu',
            'Setiawan',
            'Nugroho',
            'Siregar',
            'Simanjuntak',
            'Nainggolan',
            'Wibowo',
            'Hartono',
            'Susanto',
            'Gunawan',
            'Firmansyah',
            'Prasetyo',
            'Utama',
            'Hakim',
            'Ramadhan',
            'Anggraini',
            'Kusuma',
            'Lestari',
        ];

        $birthPlaces = [
            'Jakarta',
            'Bandung',
            'Surabaya',
            'Medan',
            'Semarang',
            'Yogyakarta',
            'Malang',
            'Bogor',
            'Depok',
            'Tangerang',
            'Bekasi',
            'Palembang',
            'Makassar',
            'Denpasar',
            'Padang',
            'Pontianak',
            'Balikpapan',
            'Manado',
            'Batam',
            'Pekanbaru',
        ];

        $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

        $streets = [
            'Jl. Merdeka',
            'Jl. Sudirman',
            'Jl. Diponegoro',
            'Jl. Gatot Subroto',
            'Jl. Ahmad Yani',
            'Jl. Pemuda',
            'Jl. Kartini',
            'Jl. Pahlawan',
            'Jl. Melati',
            'Jl. Mawar',
            'Jl. Kenanga',
            'Jl. Anggrek',
            'Jl. Cempaka',
            'Jl. Flamboyan',
            'Jl. Dahlia',
        ];

        $now = now();
        $rows = [];

        // ============ Set pelacak uniqueness ============
        // NIS unik per organization_id
        $usedNis = []; // ['org_id' => ['NIS001' => true, ...]]
        // NISN unik per organization_id
        $usedNisn = [];

        // Jumlah siswa per sekolah (bisa disesuaikan)
        $studentsPerSchool = 30;

        // Counter global NIS / NISN per organisasi
        $nisCounter = [];

        foreach ($schools as $school) {
            $orgId = $school->organization_id ?? $organizations[array_rand($organizations)];

            for ($i = 0; $i < $studentsPerSchool; $i++) {
                // ---- NIS unik per organisasi ----
                $nisCounter[$orgId] = ($nisCounter[$orgId] ?? 0) + 1;
                $nis = sprintf('NIS%05d%03d', $orgId, $nisCounter[$orgId]);

                // Pastikan tidak bentrok dengan data existing (kalau seeder dijalankan ulang)
                while (isset($usedNis[$orgId][$nis])) {
                    $nisCounter[$orgId]++;
                    $nis = sprintf('NIS%05d%03d', $orgId, $nisCounter[$orgId]);
                }
                $usedNis[$orgId][$nis] = true;

                // ---- NISN unik per organisasi (10 digit angka) ----
                do {
                    $nisn = (string) random_int(1000000000, 9999999999);
                } while (isset($usedNisn[$orgId][$nisn]));
                $usedNisn[$orgId][$nisn] = true;

                // ---- Pilih gender & nama ----
                $gender = random_int(0, 1) === 1 ? 'MALE' : 'FEMALE';
                $firstName = $gender === 'MALE'
                    ? $maleNames[array_rand($maleNames)]
                    : $femaleNames[array_rand($femaleNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $fullName = $firstName . ' ' . $lastName;

                // ---- Tanggal lahir ----
                $birthDate = Carbon::now()
                    ->subYears(random_int(13, 18))
                    ->subDays(random_int(0, 365))
                    ->format('Y-m-d');

                // ---- Tanggal masuk ----
                $joinDate = Carbon::now()
                    ->subYears(random_int(0, 3))
                    ->subDays(random_int(0, 30))
                    ->format('Y-m-d');

                // ---- Status & admission ----
                $status = random_int(1, 100) <= 90
                    ? 'ACTIVE'
                    : ['GRADUATED', 'TRANSFERRED', 'DROPPED', 'SUSPENDED'][array_rand([0, 1, 2, 3])];

                $admissionStatus = ['NEW', 'TRANSFER', 'RETURNING'][random_int(0, 2)];

                // ---- Alamat ----
                $street = $streets[array_rand($streets)];
                $houseNo = random_int(1, 200);
                $address = "{$street} No. {$houseNo}, RT " . str_pad(random_int(1, 20), 2, '0', STR_PAD_LEFT)
                    . "/RW " . str_pad(random_int(1, 15), 2, '0', STR_PAD_LEFT);

                // ---- Nomor telepon ----
                $parentsPhone = '08' . random_int(1, 9) . random_int(10000000, 99999999);
                $emergencyContact = '08' . random_int(1, 9) . random_int(10000000, 99999999);

                // ---- Applicant_id (opsional) ----
                $applicantId = null;
                if (!empty($applicants) && random_int(1, 100) <= 30) {
                    $applicantId = $applicants[array_rand($applicants)];
                }

                // ---- Enrollment year ----
                $enrollmentYear = (int) Carbon::parse($joinDate)->format('Y');

                // ---- Registration number ----
                $registrationNumber = 'REG-' . $enrollmentYear . '-' . str_pad($nisCounter[$orgId], 4, '0', STR_PAD_LEFT);

                $rows[] = [
                    'organization_id'     => $orgId,
                    'school_id'           => $school->id,
                    'user_id'             => null, // diisi nanti jika ada UserSeeder
                    'applicant_id'        => $applicantId,
                    'nis'                 => $nis,
                    'nisn'                => $nisn,
                    'registration_number' => $registrationNumber,
                    'name'                => $fullName,
                    'birth_place'         => $birthPlaces[array_rand($birthPlaces)],
                    'birth_date'          => $birthDate,
                    'gender'              => $gender,
                    'address'             => $address,
                    'religion'            => $religions[array_rand($religions)],
                    'parents_phone'       => $parentsPhone,
                    'emergency_contact'   => $emergencyContact,
                    'medical_notes'       => random_int(1, 100) <= 20
                        ? 'Riwayat alergi ringan, tidak ada kondisi khusus.'
                        : null,
                    'join_date'           => $joinDate,
                    'status'              => $status,
                    'admission_status'    => $admissionStatus,
                    'enrollment_year'     => $enrollmentYear,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                    'deleted_at'          => null,
                    'created_by'          => null,
                    'updated_by'          => null,
                ];
            }
        }

        if (empty($rows)) {
            $this->command->warn('StudentSeeder: tidak ada baris yang dihasilkan.');
            return;
        }

        // ============ Insert per chunk ============
        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('students')->insert($chunk);
        }

        $this->command->info('StudentSeeder: ' . count($rows) . ' siswa berhasil dibuat.');
    }
}
