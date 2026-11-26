<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveBalanceSeeder extends Seeder
{
    /**
     * Jenis cuti yang memakai saldo tahunan per karyawan.
     * Cuti berbasis kejadian (melahirkan, menikah, duka, dll.) tidak diberi saldo.
     */
    private const BALANCE_CODES = ['ANNUAL', 'SICK'];

    public function run(): void
    {
        $year = now()->year;

        $types = DB::table('leave_types')
            ->whereIn('code', self::BALANCE_CODES)
            ->where('is_active', 1)
            ->get(['id', 'default_days']);

        if ($types->isEmpty()) {
            $this->command?->error('leave_types belum ada. Jalankan LeaveTypeSeeder terlebih dahulu.');
            return;
        }

        DB::table('employees')
            ->select('id')
            ->orderBy('id')
            ->chunkById(500, function ($employees) use ($types, $year) {
                foreach ($employees as $employee) {
                    foreach ($types as $type) {
                        $keys = [
                            'employee_id'   => $employee->id,
                            'leave_type_id' => $type->id,
                            'year'          => $year,
                        ];

                        $existing = DB::table('leave_balances')->where($keys)->first();

                        if ($existing) {
                            // Jangan menimpa used_days yang sudah berjalan
                            DB::table('leave_balances')->where($keys)->update([
                                'allocated_days' => $type->default_days,
                                'remaining_days' => $type->default_days - $existing->used_days,
                            ]);
                        } else {
                            DB::table('leave_balances')->insert($keys + [
                                'allocated_days' => $type->default_days,
                                'used_days'      => 0,
                                'remaining_days' => $type->default_days,
                            ]);
                        }
                    }
                }
            });
    }
}