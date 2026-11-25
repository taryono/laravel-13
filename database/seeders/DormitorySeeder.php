<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Models\Dormitory;
use Models\Organization;

class DormitorySeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::first();

        if (!$organization) {
            $this->command?->warn('DormitorySeeder dilewati: tabel organizations masih kosong.');
            return;
        }

        $data = [
            ['code' => 'AST-AISYAH', 'name' => 'Asrama Aisyah', 'gender_type' => 'female', 'capacity' => 80, 'address' => 'Kompleks Putri Blok A'],
            ['code' => 'AST-FATIMAH', 'name' => 'Asrama Fatimah', 'gender_type' => 'female', 'capacity' => 60, 'address' => 'Kompleks Putri Blok B'],
            ['code' => 'AST-UMAR', 'name' => 'Asrama Umar', 'gender_type' => 'male', 'capacity' => 100, 'address' => 'Kompleks Putra Blok A'],
            ['code' => 'AST-ALI', 'name' => 'Asrama Ali', 'gender_type' => 'male', 'capacity' => 90, 'address' => 'Kompleks Putra Blok B'],
        ];

        foreach ($data as $row) {
            Dormitory::updateOrCreate(
                ['organization_id' => $organization->id, 'code' => $row['code']],
                $row + ['organization_id' => $organization->id, 'is_active' => true]
            );
        }
    }
}