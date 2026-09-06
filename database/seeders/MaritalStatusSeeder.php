<?php

namespace Database\Seeders;

use Models\MaritalStatus;
use Illuminate\Database\Seeder;

class MaritalStatusSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['code' => 'BK', 'name' => 'Belum Kawin', 'sort_order' => 1],
            ['code' => 'KW', 'name' => 'Kawin', 'sort_order' => 2],
            ['code' => 'CH', 'name' => 'Cerai Hidup', 'sort_order' => 3],
            ['code' => 'CM', 'name' => 'Cerai Mati', 'sort_order' => 4],
        ];

        foreach ($data as $item) {
            MaritalStatus::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}