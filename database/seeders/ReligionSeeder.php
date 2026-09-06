<?php

namespace Database\Seeders;

use Models\Religion;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['code' => 'ISL', 'name' => 'Islam', 'sort_order' => 1],
            ['code' => 'KRST', 'name' => 'Kristen', 'sort_order' => 2],
            ['code' => 'KAT', 'name' => 'Katolik', 'sort_order' => 3],
            ['code' => 'HND', 'name' => 'Hindu', 'sort_order' => 4],
            ['code' => 'BUD', 'name' => 'Budha', 'sort_order' => 5],
            ['code' => 'KON', 'name' => 'Khonghucu', 'sort_order' => 6],
            ['code' => 'LNN', 'name' => 'Lainnya', 'sort_order' => 99],
        ];

        foreach ($data as $item) {
            Religion::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}