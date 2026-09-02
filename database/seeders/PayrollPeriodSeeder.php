<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Models\PayrollPeriod;

class PayrollPeriodSeeder extends Seeder
{
    // database/seeders/PayrollPeriodSeeder.php
    public function run()
    {
        $years = [2025, 2026];
        foreach ($years as $year) {
            for ($month = 1; $month <= 12; $month++) {
                PayrollPeriod::create([
                    'code' => "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT),
                    'name' => "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT),
                    'period_month' => $month,
                    'period_year' => $year,
                    'start_date' => "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01",
                    'end_date' => date("Y-m-t", strtotime("{$year}-{$month}-01")),
                    'payment_date' => date("Y-m-d", strtotime("+1 month", strtotime("{$year}-{$month}-01"))),
                    'status' => $year < date('Y') || ($year == date('Y') && $month < date('m')) ? 'completed' : 'draft',
                    'is_closed' => $year < date('Y') || ($year == date('Y') && $month < date('m')) ? 1 : 0,
                    'closed_at'   => NULL,
                    'created_by' => 1,
                    'closed_by'   => 1,
                ]);
            }
        }
    }
}