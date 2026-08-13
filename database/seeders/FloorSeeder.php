<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloorSeeder extends Seeder
{
    public function run(): void
    {
        $floors = [];

        // Gedung A (GED-A) - 4 Lantai
        for ($i = 1; $i <= 4; $i++) {
            $floors[] = [
                'building_id' => 1,
                'code' => 'GED-A-0' . $i,
                'name' => 'Lantai ' . $i,
                'floor_number' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Gedung B (GED-B) - 2 Lantai
        for ($i = 1; $i <= 2; $i++) {
            $floors[] = [
                'building_id' => 2,
                'code' => 'GED-B-0' . $i,
                'name' => 'Lantai ' . $i,
                'floor_number' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Gedung C (GED-C) - 3 Lantai
        for ($i = 1; $i <= 3; $i++) {
            $floors[] = [
                'building_id' => 3,
                'code' => 'GED-C-0' . $i,
                'name' => 'Lantai ' . $i,
                'floor_number' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Gedung D (GED-D) - 5 Lantai
        for ($i = 1; $i <= 5; $i++) {
            $floors[] = [
                'building_id' => 4,
                'code' => 'GED-D-0' . $i,
                'name' => 'Lantai ' . $i,
                'floor_number' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Gedung E (GED-E) - 4 Lantai
        for ($i = 1; $i <= 4; $i++) {
            $floors[] = [
                'building_id' => 5,
                'code' => 'GED-E-0' . $i,
                'name' => 'Lantai ' . $i,
                'floor_number' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Gedung F (GED-F) - 2 Lantai
        for ($i = 1; $i <= 2; $i++) {
            $floors[] = [
                'building_id' => 6,
                'code' => 'GED-F-0' . $i,
                'name' => 'Lantai ' . $i,
                'floor_number' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Gedung G (GED-G) - 3 Lantai
        for ($i = 1; $i <= 3; $i++) {
            $floors[] = [
                'building_id' => 7,
                'code' => 'GED-G-0' . $i,
                'name' => 'Lantai ' . $i,
                'floor_number' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Gedung H (GED-H) - 3 Lantai
        for ($i = 1; $i <= 3; $i++) {
            $floors[] = [
                'building_id' => 8,
                'code' => 'GED-H-0' . $i,
                'name' => 'Lantai ' . $i,
                'floor_number' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('floors')->insert($floors);
    }
}