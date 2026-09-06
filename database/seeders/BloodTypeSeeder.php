<?php

namespace Database\Seeders;

use Models\BloodType;
use Illuminate\Database\Seeder;

class BloodTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['code' => 'A', 'rhesus' => '+', 'name' => 'A+', 'sort_order' => 1],
            ['code' => 'A', 'rhesus' => '-', 'name' => 'A-', 'sort_order' => 2],
            ['code' => 'B', 'rhesus' => '+', 'name' => 'B+', 'sort_order' => 3],
            ['code' => 'B', 'rhesus' => '-', 'name' => 'B-', 'sort_order' => 4],
            ['code' => 'AB', 'rhesus' => '+', 'name' => 'AB+', 'sort_order' => 5],
            ['code' => 'AB', 'rhesus' => '-', 'name' => 'AB-', 'sort_order' => 6],
            ['code' => 'O', 'rhesus' => '+', 'name' => 'O+', 'sort_order' => 7],
            ['code' => 'O', 'rhesus' => '-', 'name' => 'O-', 'sort_order' => 8],
        ];

        foreach ($data as $item) {
            BloodType::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}