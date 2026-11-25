<?php

namespace Database\Seeders;

use Models\AcademicYear;
use Models\BosAllocation;
use Models\BosComponent;
use Models\BosPeriod;
use Models\BosRealization;
use Models\BosReceipt;
use Models\BosUsagePlan;
use Models\School;
use Illuminate\Database\Seeder;

class BosDashboardDummySeeder extends Seeder
{
    public function run(): void
    {
        $school = School::firstOrFail();
        $academicYear = AcademicYear::firstOrFail();

        // 1. Buat Periode BOS Tahap I 2025 (Sesuai UI Mockup)
        $period = BosPeriod::updateOrCreate(
            ['school_id' => $school->id, 'code' => 'BOS-REG-2025-T1'],
            [
                'academic_year_id' => $academicYear->id,
                'name' => 'Tahap I 2025',
                'authority' => 'KEMENDIKBUD',
                'regulation_version' => 'Permendikbudristek No. 63/2023',
                'period_number' => 1,
                'start_date' => '2025-01-01',
                'end_date' => '2025-07-31',
                'total_allocation' => 640000000.00,
                'total_received' => 640000000.00,
                'total_used' => 288000000.00,
                'total_remaining' => 352000000.00,
                'status' => 'VERIFIED',
                'is_active' => true,
            ]
        );

        // 2. Catat Penerimaan Dana BOS di Bank
        BosReceipt::updateOrCreate(
            ['bos_period_id' => $period->id, 'receipt_number' => 'BKM/BOS/2025/001'],
            [
                'receipt_date' => '2025-02-10',
                'amount' => 640000000.00,
                'transfer_reference' => 'SP2D-KEMENDIKBUD-88291',
                'bank_name' => 'Bank BJB Cab. Utama',
                'notes' => 'Penyaluran Dana BOS Reguler Tahap I',
                'is_active' => true,
            ]
        );

        // 3. Buat Master Komponen & Alokasi SNP (Sesuai Widget Kanan UI)
        $componentsData = [
            ['code' => 'KMP-01', 'name' => 'Pemeliharaan Sarana & Prasarana', 'type' => 'SARPRAS', 'max' => null, 'alloc' => 150000000, 'used' => 82000000],
            ['code' => 'KMP-02', 'name' => 'Honor Guru Non-ASN', 'type' => 'HONORARIUM', 'max' => 50.0, 'alloc' => 135000000, 'used' => 41000000],
            ['code' => 'KMP-03', 'name' => 'Pembelajaran & Ekstrakurikuler', 'type' => 'PEMBELAJARAN', 'max' => null, 'alloc' => 110000000, 'used' => 58000000],
            ['code' => 'KMP-04', 'name' => 'Pengembangan Perpustakaan & Buku', 'type' => 'PERPUSTAKAAN', 'max' => null, 'alloc' => 55000000, 'used' => 41000000],
            ['code' => 'KMP-05', 'name' => 'Langganan Daya & Jasa', 'type' => 'UTILITIES', 'max' => null, 'alloc' => 90000000, 'used' => 42000000],
        ];

        foreach ($componentsData as $idx => $item) {
            $component = BosComponent::updateOrCreate(
                ['authority' => 'KEMENDIKBUD', 'code' => $item['code']],
                [
                    'name' => $item['name'],
                    'component_type' => $item['type'],
                    'max_percentage' => $item['max'],
                    'order_index' => $idx + 1,
                    'is_active' => true,
                ]
            );

            BosAllocation::updateOrCreate(
                ['bos_period_id' => $period->id, 'bos_component_id' => $component->id],
                [
                    'allocation_amount' => $item['alloc'],
                    'percentage' => round(($item['alloc'] / 640000000) * 100, 2),
                    'is_active' => true,
                ]
            );

            $plan = BosUsagePlan::updateOrCreate(
                ['bos_period_id' => $period->id, 'code' => 'PLAN-' . $item['code']],
                [
                    'bos_component_id' => $component->id,
                    'name' => 'Rencana ' . $item['name'],
                    'planned_amount' => $item['alloc'],
                    'status' => 'APPROVED',
                    'is_active' => true,
                ]
            );

            // Buat 1 contoh transaksi realisasi per komponen
            BosRealization::updateOrCreate(
                ['bos_period_id' => $period->id, 'receipt_number' => 'BOS/SPK/00' . ($idx + 1)],
                [
                    'bos_usage_plan_id' => $plan->id,
                    'realization_date' => now()->subDays(5 - $idx)->toDateString(),
                    'description' => 'Realisasi Belanja ' . $item['name'],
                    'amount' => $item['used'],
                    'tax_amount' => 0,
                    'payment_method' => 'TRANSFER',
                    'status' => 'RECORDED',
                    'is_active' => true,
                ]
            );
        }
    }
}