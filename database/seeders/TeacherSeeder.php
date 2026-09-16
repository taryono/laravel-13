<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizationId = 1;

        $teachers = [
            [
                'nip' => '198501012010011001',
                'name' => 'Ahmad Fauzi, S.Pd.',
                'specialization' => 'Pendidikan Agama Islam',
                'join_date' => '2010-01-04',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '198603152011012002',
                'name' => 'Siti Aminah, S.Pd.',
                'specialization' => 'Bahasa Indonesia',
                'join_date' => '2011-07-01',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '198709202012011003',
                'name' => 'Budi Santoso, S.Pd.',
                'specialization' => 'Matematika',
                'join_date' => '2012-01-02',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '198804102013012004',
                'name' => 'Dewi Lestari, S.Pd.',
                'specialization' => 'Bahasa Inggris',
                'join_date' => '2013-07-01',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '198902252014011005',
                'name' => 'Rizky Hidayat, S.Pd.',
                'specialization' => 'Ilmu Pengetahuan Alam',
                'join_date' => '2014-01-06',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '198905122015012006',
                'name' => 'Nurul Hidayah, S.Pd.',
                'specialization' => 'Ilmu Pengetahuan Sosial',
                'join_date' => '2015-07-01',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '199001182016011007',
                'name' => 'Agus Setiawan, S.Pd.',
                'specialization' => 'Pendidikan Jasmani',
                'join_date' => '2016-01-04',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '199102052017012008',
                'name' => 'Maya Sari, S.Pd.',
                'specialization' => 'Seni Budaya',
                'join_date' => '2017-07-03',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '199203102018011009',
                'name' => 'Fajar Nugroho, S.Pd.',
                'specialization' => 'Teknologi Informasi',
                'join_date' => '2018-01-08',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '199305202019012010',
                'name' => 'Rina Wulandari, S.Pd.',
                'specialization' => 'Bahasa Indonesia',
                'join_date' => '2019-07-01',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '199407152020011011',
                'name' => 'Dedi Kurniawan, S.Pd.',
                'specialization' => 'Matematika',
                'join_date' => '2020-01-06',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '199508122021012012',
                'name' => 'Ayu Pratiwi, S.Pd.',
                'specialization' => 'Bahasa Inggris',
                'join_date' => '2021-07-01',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '199609252022011013',
                'name' => 'Hendra Wijaya, S.Pd.',
                'specialization' => 'Ilmu Pengetahuan Alam',
                'join_date' => '2022-01-03',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '199701182023012014',
                'name' => 'Lina Marlina, S.Pd.',
                'specialization' => 'Pendidikan Agama Islam',
                'join_date' => '2023-07-03',
                'status' => 'ACTIVE',
            ],
            [
                'nip' => '199802052024011015',
                'name' => 'Andi Pratama, S.Pd.',
                'specialization' => 'Pendidikan Jasmani',
                'join_date' => '2024-01-08',
                'status' => 'ACTIVE',
            ],
        ];

        foreach ($teachers as $teacher) {
            DB::table('teachers')->updateOrInsert(
                [
                    'organization_id' => $organizationId,
                    'nip' => $teacher['nip'],
                ],
                [
                    'user_id' => null,
                    'name' => $teacher['name'],
                    'specialization' => $teacher['specialization'],
                    'join_date' => $teacher['join_date'],
                    'status' => $teacher['status'],
                    'deleted_at' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->command?->info(
            'Teacher seeder berhasil dibuat/diperbarui untuk organization_id ' .
            $organizationId . '.'
        );
    }
}