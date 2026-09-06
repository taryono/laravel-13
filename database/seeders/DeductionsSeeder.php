<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeductionsSeeder extends Seeder
{
    public function run()
    {
        // Ambil data yang dibutuhkan
        $employees = DB::table('employees')->select('id')->get();
        $payrollPeriods = DB::table('payroll_periods')->whereIn('status', ['completed', 'processing'])->select('id')->get();
        $categories = DB::table('deduction_categories')
            ->where('is_active', 1)
            ->pluck('id', 'code');

        if ($employees->isEmpty() || $payrollPeriods->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('⚠️ Data tidak lengkap. Pastikan employees, payroll_periods, dan deduction_categories sudah ada.');
            return;
        }

        $deductions = [];
        $sampleEmployees = $employees->take(5);

        foreach ($sampleEmployees as $employee) {
            $period = $payrollPeriods->random();

            // Mapping potongan dengan kategori
            $deductionConfigs = [
                ['deduction_category_id' => $categories['BPJS-KES'], 'amount' => rand(50000, 150000)],
                ['deduction_category_id' => $categories['BPJS-TK'], 'amount' => rand(30000, 100000)],
                ['deduction_category_id' => $categories['PPh21'], 'amount' => rand(0, 200000)],
                ['deduction_category_id' => $categories['KOPERASI'], 'amount' => rand(100000, 250000)],
                ['deduction_category_id' => $categories['SOSIAL'], 'amount' => rand(20000, 50000)],
                ['deduction_category_id' => $categories['SERAGAM'], 'amount' => rand(50000, 150000)],
                ['deduction_category_id' => $categories['DENDA'], 'amount' => rand(50000, 100000)],
            ];

            // Tambahkan beberapa potongan opsional
            if (rand(0, 1) == 1) {
                $deductionConfigs[] = [
                    'deduction_category_id' => $categories['SERTIFIKASI'],
                    'amount' => rand(200000, 500000),
                    'recurring_until' => now()->addMonths(rand(3, 8))->format('Y-m-d')
                ];
            }

            if (rand(0, 2) == 1) {
                $deductionConfigs[] = [
                    'deduction_category_id' => $categories['REIMBURSE'],
                    'amount' => rand(100000, 300000)
                ];
            }

            foreach ($deductionConfigs as $config) {
                $deductions[] = [
                    'employee_id' => $employee->id,
                    'payroll_period_id' => $period->id,
                    'deduction_category_id' => $config['deduction_category_id'],
                    'amount' => $config['amount'],
                    'description' => null,
                    'is_recurring' => isset($config['recurring_until']) ? 1 : 0,
                    'recurring_until' => $config['recurring_until'] ?? null,
                    'is_paid' => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert dengan cek duplikat
        $existing = DB::table('deductions')
            ->select('employee_id', 'payroll_period_id', 'deduction_category_id')
            ->get()
            ->map(fn($item) => $item->employee_id . '|' . $item->payroll_period_id . '|' . $item->deduction_category_id)
            ->toArray();

        $newData = array_filter($deductions, function ($deduction) use ($existing) {
            $key = $deduction['employee_id'] . '|' . $deduction['payroll_period_id'] . '|' . $deduction['deduction_category_id'];
            return !in_array($key, $existing);
        });

        if (!empty($newData)) {
            $chunks = array_chunk($newData, 100);
            foreach ($chunks as $chunk) {
                DB::table('deductions')->insert($chunk);
            }
            $this->command->info('✅ ' . count($newData) . ' deduction(s) berhasil ditambahkan.');
        } else {
            $this->command->info('ℹ️ Tidak ada deduction baru yang ditambahkan.');
        }
    }
}