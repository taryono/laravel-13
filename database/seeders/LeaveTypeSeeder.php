<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $organizationId = DB::table('organizations')->value('id');

        if (!$organizationId) {
            $this->command?->error('Tabel organizations masih kosong. Jalankan seeder organisasi terlebih dahulu.');
            return;
        }

        $types = [
            [
                'code'                    => 'ANNUAL',
                'name'                    => 'Cuti Tahunan',
                'default_days'            => 12,
                'max_consecutive_days'    => 6,
                'min_working_days_before' => 3,
                'is_paid'                 => 1,
                'requires_attachment'     => 0,
            ],
            [
                'code'                    => 'SICK',
                'name'                    => 'Cuti Sakit',
                'default_days'            => 12,
                'max_consecutive_days'    => 14,
                'min_working_days_before' => 0,
                'is_paid'                 => 1,
                'requires_attachment'     => 1, // surat dokter
            ],
            [
                'code'                    => 'MATERNITY',
                'name'                    => 'Cuti Melahirkan',
                'default_days'            => 90,
                'max_consecutive_days'    => 90,
                'min_working_days_before' => 14,
                'is_paid'                 => 1,
                'requires_attachment'     => 1,
            ],
            [
                'code'                    => 'PATERNITY',
                'name'                    => 'Cuti Istri Melahirkan/Keguguran',
                'default_days'            => 2,
                'max_consecutive_days'    => 2,
                'min_working_days_before' => 0,
                'is_paid'                 => 1,
                'requires_attachment'     => 1,
            ],
            [
                'code'                    => 'MARRIAGE',
                'name'                    => 'Cuti Menikah',
                'default_days'            => 3,
                'max_consecutive_days'    => 3,
                'min_working_days_before' => 14,
                'is_paid'                 => 1,
                'requires_attachment'     => 1,
            ],
            [
                'code'                    => 'BEREAVEMENT',
                'name'                    => 'Cuti Duka (Keluarga Inti Meninggal)',
                'default_days'            => 2,
                'max_consecutive_days'    => 2,
                'min_working_days_before' => 0,
                'is_paid'                 => 1,
                'requires_attachment'     => 1,
            ],
            [
                'code'                    => 'UNPAID',
                'name'                    => 'Cuti Di Luar Tanggungan (Tanpa Gaji)',
                'default_days'            => 0,
                'max_consecutive_days'    => 30,
                'min_working_days_before' => 14,
                'is_paid'                 => 0,
                'requires_attachment'     => 0,
            ],
        ];

        foreach ($types as $type) {
            DB::table('leave_types')->updateOrInsert(
                [
                    'organization_id' => $organizationId,
                    'code'            => $type['code'],
                ],
                $type + ['is_active' => 1]
            );
        }
    }
}