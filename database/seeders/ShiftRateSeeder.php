<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Models\Shift;
use Models\ShiftRate;

class ShiftRateSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ShiftRate::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil shift yang sudah ada dari ShiftSeeder
        $shiftPagi  = Shift::where('code', 'S1')->first();
        $shiftSore  = Shift::where('code', 'S2')->first();
        $shiftMalam = Shift::where('code', 'S3')->first();

        if (!$shiftPagi || !$shiftSore || !$shiftMalam) {
            $this->command->warn('Shift S1, S2, S3 belum ada. Jalankan ShiftSeeder terlebih dahulu.');
            return;
        }

        $rates = [
            // ===== SHIFT PAGI (07:00 - 15:00) =====
            ['shift_id' => $shiftPagi->id, 'day_type' => 'weekday',          'base_rate' => 100000, 'overtime_rate_per_hour' => 18750,  'overtime_multiplier' => 1.5, 'night_allowance' => 0,      'meal_allowance' => 15000, 'transport_allowance' => 10000],
            ['shift_id' => $shiftPagi->id, 'day_type' => 'saturday',         'base_rate' => 125000, 'overtime_rate_per_hour' => 23438,  'overtime_multiplier' => 1.5, 'night_allowance' => 0,      'meal_allowance' => 15000, 'transport_allowance' => 10000],
            ['shift_id' => $shiftPagi->id, 'day_type' => 'sunday',           'base_rate' => 150000, 'overtime_rate_per_hour' => 28125,  'overtime_multiplier' => 2.0, 'night_allowance' => 0,      'meal_allowance' => 20000, 'transport_allowance' => 15000],
            ['shift_id' => $shiftPagi->id, 'day_type' => 'national_holiday', 'base_rate' => 200000, 'overtime_rate_per_hour' => 37500,  'overtime_multiplier' => 3.0, 'night_allowance' => 0,      'meal_allowance' => 25000, 'transport_allowance' => 20000],
            ['shift_id' => $shiftPagi->id, 'day_type' => 'special_holiday',  'base_rate' => 175000, 'overtime_rate_per_hour' => 32813,  'overtime_multiplier' => 2.5, 'night_allowance' => 0,      'meal_allowance' => 20000, 'transport_allowance' => 15000],

            // ===== SHIFT SORE (14:00 - 22:00) =====
            ['shift_id' => $shiftSore->id, 'day_type' => 'weekday',          'base_rate' => 110000, 'overtime_rate_per_hour' => 20625,  'overtime_multiplier' => 1.5, 'night_allowance' => 10000,  'meal_allowance' => 15000, 'transport_allowance' => 10000],
            ['shift_id' => $shiftSore->id, 'day_type' => 'saturday',         'base_rate' => 137500, 'overtime_rate_per_hour' => 25781,  'overtime_multiplier' => 1.5, 'night_allowance' => 10000,  'meal_allowance' => 15000, 'transport_allowance' => 10000],
            ['shift_id' => $shiftSore->id, 'day_type' => 'sunday',           'base_rate' => 165000, 'overtime_rate_per_hour' => 30938,  'overtime_multiplier' => 2.0, 'night_allowance' => 15000,  'meal_allowance' => 20000, 'transport_allowance' => 15000],
            ['shift_id' => $shiftSore->id, 'day_type' => 'national_holiday', 'base_rate' => 220000, 'overtime_rate_per_hour' => 41250,  'overtime_multiplier' => 3.0, 'night_allowance' => 20000,  'meal_allowance' => 25000, 'transport_allowance' => 20000],
            ['shift_id' => $shiftSore->id, 'day_type' => 'special_holiday',  'base_rate' => 192500, 'overtime_rate_per_hour' => 36094,  'overtime_multiplier' => 2.5, 'night_allowance' => 15000,  'meal_allowance' => 20000, 'transport_allowance' => 15000],

            // ===== SHIFT MALAM (22:00 - 06:00) =====
            ['shift_id' => $shiftMalam->id, 'day_type' => 'weekday',          'base_rate' => 130000, 'overtime_rate_per_hour' => 24375,  'overtime_multiplier' => 1.5, 'night_allowance' => 25000,  'meal_allowance' => 20000, 'transport_allowance' => 15000],
            ['shift_id' => $shiftMalam->id, 'day_type' => 'saturday',         'base_rate' => 162500, 'overtime_rate_per_hour' => 30469,  'overtime_multiplier' => 1.5, 'night_allowance' => 25000,  'meal_allowance' => 20000, 'transport_allowance' => 15000],
            ['shift_id' => $shiftMalam->id, 'day_type' => 'sunday',           'base_rate' => 195000, 'overtime_rate_per_hour' => 36563,  'overtime_multiplier' => 2.0, 'night_allowance' => 35000,  'meal_allowance' => 25000, 'transport_allowance' => 20000],
            ['shift_id' => $shiftMalam->id, 'day_type' => 'national_holiday', 'base_rate' => 260000, 'overtime_rate_per_hour' => 48750,  'overtime_multiplier' => 3.0, 'night_allowance' => 50000,  'meal_allowance' => 30000, 'transport_allowance' => 25000],
            ['shift_id' => $shiftMalam->id, 'day_type' => 'special_holiday',  'base_rate' => 227500, 'overtime_rate_per_hour' => 42656,  'overtime_multiplier' => 2.5, 'night_allowance' => 40000,  'meal_allowance' => 25000, 'transport_allowance' => 20000],
        ];

        foreach ($rates as $rate) {
            ShiftRate::create(array_merge($rate, [
                'is_active' => 1,
            ]));
        }

        $this->command->info('✅ ' . count($rates) . ' shift rates berhasil di-seed.');
    }
}