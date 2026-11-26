<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeLeaveSeeder extends Seeder
{
    public function run(): void
    {
        $year = now()->year;

        $employeeIds = DB::table('employees')->orderBy('id')->limit(5)->pluck('id')->values();
        $typeIds     = DB::table('leave_types')->pluck('id', 'code');

        if ($employeeIds->isEmpty() || $typeIds->isEmpty()) {
            $this->command?->error('Data employees / leave_types belum ada. Jalankan seeder keduanya terlebih dahulu.');
            return;
        }

        // Contoh pengajuan cuti. 'status' mengikuti enum: pending, approved, rejected, cancelled
        // (sesuaikan jika nilai enum di database Anda berbeda).
        $samples = [
            ['type' => 'ANNUAL',   'start' => [3, 9],   'end' => [3, 11],  'status' => 'approved',  'reason' => 'Acara keluarga di luar kota.', 'attachment' => null],
            ['type' => 'SICK',     'start' => [4, 14],  'end' => [4, 15],  'status' => 'approved',  'reason' => 'Demam dan flu, istirahat sesuai anjuran dokter.', 'attachment' => 'attachments/leaves/surat-dokter-contoh.pdf'],
            ['type' => 'ANNUAL',   'start' => [12, 28], 'end' => [12, 30], 'status' => 'draft',   'reason' => 'Liburan akhir tahun bersama keluarga.', 'attachment' => null],
            ['type' => 'ANNUAL',   'start' => [6, 1],   'end' => [6, 5],   'status' => 'rejected',  'reason' => 'Keperluan pribadi.', 'attachment' => null],
            ['type' => 'MARRIAGE', 'start' => [8, 17],  'end' => [8, 19],  'status' => 'approved',  'reason' => 'Pernikahan karyawan.', 'attachment' => 'attachments/leaves/undangan-nikah-contoh.pdf'],
            ['type' => 'ANNUAL',   'start' => [10, 12], 'end' => [10, 13], 'status' => 'cancelled', 'reason' => 'Rencana perjalanan dibatalkan.', 'attachment' => null],
        ];

        foreach ($samples as $i => $sample) {
            if (!isset($typeIds[$sample['type']])) {
                continue;
            }

            $employeeId = $employeeIds[$i % $employeeIds->count()];
            $approverId = $employeeIds[($i + 1) % $employeeIds->count()];

            $start = Carbon::create($year, $sample['start'][0], $sample['start'][1]);
            $end   = Carbon::create($year, $sample['end'][0], $sample['end'][1]);

            $processed = in_array($sample['status'], ['approved', 'rejected'], true);

            DB::table('employee_leaves')->updateOrInsert(
                [
                    'employee_id'   => $employeeId,
                    'leave_type_id' => $typeIds[$sample['type']],
                    'start_date'    => $start->toDateString(),
                    'end_date'      => $end->toDateString(),
                ],
                [
                    'total_days'      => $this->workingDays($start, $end),
                    'reason'          => $sample['reason'],
                    'attachment_path' => $sample['attachment'],
                    'status'          => $sample['status'],
                    'approved_by'     => $processed ? $approverId : null,
                    'approved_at'     => $processed ? $start->copy()->subDays(5)->setTime(9, 0) : null,
                ]
            );
        }

        $this->syncBalances($year);
    }

    /**
     * Hitung hari kerja (Senin-Jumat) antara dua tanggal, inklusif.
     * Hari libur nasional tidak diperhitungkan.
     */
    private function workingDays(Carbon $start, Carbon $end): float
    {
        $days = 0;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if (!$date->isWeekend()) {
                $days++;
            }
        }

        return (float) $days;
    }

    /**
     * Samakan used_days & remaining_days di leave_balances dengan
     * total cuti berstatus approved pada tahun berjalan.
     */
    private function syncBalances(int $year): void
    {
        $sums = DB::table('employee_leaves')
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->groupBy('employee_id', 'leave_type_id')
            ->select('employee_id', 'leave_type_id', DB::raw('SUM(total_days) as used'))
            ->get();

        foreach ($sums as $sum) {
            $used = (float) $sum->used;

            DB::table('leave_balances')
                ->where('employee_id', $sum->employee_id)
                ->where('leave_type_id', $sum->leave_type_id)
                ->where('year', $year)
                ->update([
                    'used_days'      => $used,
                    'remaining_days' => DB::raw('allocated_days - ' . $used),
                ]);
        }
    }
}