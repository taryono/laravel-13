<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExamPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel schools & subjects sudah ada datanya
        // Jika belum, seeder ini akan skip untuk menghindari foreign key error
        $schools = DB::table('schools')->pluck('id')->toArray();
        $subjects = DB::table('subjects')->pluck('id')->toArray();

        if (empty($schools) || empty($subjects)) {
            $this->command->warn('Skip ExamPackageSeeder: tabel schools atau subjects masih kosong.');
            return;
        }

        $packages = [
            [
                'name'              => 'UTS Matematika Kelas X Semester 1',
                'description'       => 'Ujian Tengah Semester mata pelajaran Matematika untuk kelas X semester 1.',
                'duration_minutes'  => 90,
                'total_questions'   => 40,
                'passing_score'     => 70,
                'is_randomized'     => 1,
                'shuffle_options'   => 1,
                'show_results'      => 1,
                'is_active'         => 1,
            ],
            [
                'name'              => 'UAS Bahasa Indonesia Kelas XI',
                'description'       => 'Ujian Akhir Semester Bahasa Indonesia kelas XI.',
                'duration_minutes'  => 120,
                'total_questions'   => 50,
                'passing_score'     => 75,
                'is_randomized'     => 1,
                'shuffle_options'   => 0,
                'show_results'      => 1,
                'is_active'         => 1,
            ],
            [
                'name'              => 'Kuis IPA - Sistem Tata Surya',
                'description'       => 'Kuis singkat tentang sistem tata surya untuk kelas VII.',
                'duration_minutes'  => 30,
                'total_questions'   => 20,
                'passing_score'     => 60,
                'is_randomized'     => 0,
                'shuffle_options'   => 1,
                'show_results'      => 1,
                'is_active'         => 1,
            ],
            [
                'name'              => 'Try Out UN Bahasa Inggris',
                'description'       => 'Try out persiapan Ujian Nasional Bahasa Inggris.',
                'duration_minutes'  => 60,
                'total_questions'   => 35,
                'passing_score'     => 65,
                'is_randomized'     => 1,
                'shuffle_options'   => 1,
                'show_results'      => 0,
                'is_active'         => 1,
            ],
            [
                'name'              => 'Ulangan Harian IPS - Sejarah',
                'description'       => 'Ulangan harian bab sejarah Indonesia.',
                'duration_minutes'  => 45,
                'total_questions'   => 25,
                'passing_score'     => 70,
                'is_randomized'     => 0,
                'shuffle_options'   => 0,
                'show_results'      => 1,
                'is_active'         => 0, // non-aktif sebagai contoh
            ],
        ];

        $now = now();
        $data = [];

        foreach ($packages as $index => $pkg) {
            $data[] = array_merge($pkg, [
                'school_id'  => $schools[array_rand($schools)],
                'subject_id' => $subjects[array_rand($subjects)],
                'created_by' => null, // bisa diisi user_id jika sudah ada user
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);
        }

        DB::table('exam_packages')->insert($data);

        $this->command->info('ExamPackageSeeder: ' . count($data) . ' data berhasil dimasukkan.');
    }
}
