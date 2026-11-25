<?php

namespace Database\Seeders;

use Models\FiscalYear;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FiscalYearSeeder extends Seeder
{
    public function run()
    {
        // ⚠️ Sesuaikan ID ini dengan data di sistem kamu!
        $defaultOrgId = 1;
        $defaultSchoolId = 1;
        $defaultUserId = 1; // user yang menutup periode (jika ada)

        $fiscalYears = [
            [
                'organization_id' => $defaultOrgId,
                'school_id' => $defaultSchoolId,
                'year' => 2025,
                'month' => 1,
                'start_date' => Carbon::create(2025, 1, 1),
                'end_date' => Carbon::create(2025, 12, 31),
                'status' => 'closed',
                'is_closed' => true,
                'is_active' => false,
                'closed_at' => Carbon::create(2026, 1, 15, 14, 30),
                'closed_by' => $defaultUserId,
            ],
            [
                'organization_id' => $defaultOrgId,
                'school_id' => $defaultSchoolId,
                'year' => 2026,
                'month' => 1,
                'start_date' => Carbon::create(2026, 1, 1),
                'end_date' => Carbon::create(2026, 12, 31),
                'status' => 'open',
                'is_closed' => false,
                'is_active' => true,
                'closed_at' => null,
                'closed_by' => null,
            ],
            [
                'organization_id' => $defaultOrgId,
                'school_id' => $defaultSchoolId,
                'year' => 2027,
                'month' => 1,
                'start_date' => Carbon::create(2027, 1, 1),
                'end_date' => Carbon::create(2027, 12, 31),
                'status' => 'open',
                'is_closed' => false,
                'is_active' => true,
                'closed_at' => null,
                'closed_by' => null,
            ],
        ];

        foreach ($fiscalYears as $fy) {
            FiscalYear::create($fy);
        }
    }
}
