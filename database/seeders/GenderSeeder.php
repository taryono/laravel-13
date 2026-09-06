<?php

namespace Database\Seeders;

use Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['code' => 'L', 'name' => 'Laki-laki', 'sort_order' => 1],
            ['code' => 'P', 'name' => 'Perempuan', 'sort_order' => 2],
        ];

        foreach ($data as $item) {
            Gender::updateOrCreate(['code' => $item['code']], $item);
        }
    }
}