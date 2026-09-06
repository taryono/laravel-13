<?php

namespace Database\Seeders;

use Models\PtkpStatus;
use Illuminate\Database\Seeder;

class PtkpStatusSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 'TK/0', 'name' => 'Tidak Kawin, 0 Tanggungan', 'amount' => 54000000.00, 'sort_order' => 1],
            ['id' => 'TK/1', 'name' => 'Tidak Kawin, 1 Tanggungan', 'amount' => 58500000.00, 'sort_order' => 2],
            ['id' => 'TK/2', 'name' => 'Tidak Kawin, 2 Tanggungan', 'amount' => 63000000.00, 'sort_order' => 3],
            ['id' => 'TK/3', 'name' => 'Tidak Kawin, 3 Tanggungan', 'amount' => 67500000.00, 'sort_order' => 4],
            ['id' => 'K/0', 'name' => 'Kawin, 0 Tanggungan', 'amount' => 108000000.00, 'sort_order' => 5],
            ['id' => 'K/1', 'name' => 'Kawin, 1 Tanggungan', 'amount' => 112500000.00, 'sort_order' => 6],
            ['id' => 'K/2', 'name' => 'Kawin, 2 Tanggungan', 'amount' => 117000000.00, 'sort_order' => 7],
            ['id' => 'K/3', 'name' => 'Kawin, 3 Tanggungan', 'amount' => 121500000.00, 'sort_order' => 8],
            ['id' => 'K/I/0', 'name' => 'Kawin Istri Penghasilan, 0 Tanggungan', 'amount' => 54000000.00, 'sort_order' => 9],
            ['id' => 'K/I/1', 'name' => 'Kawin Istri Penghasilan, 1 Tanggungan', 'amount' => 58500000.00, 'sort_order' => 10],
            ['id' => 'K/I/2', 'name' => 'Kawin Istri Penghasilan, 2 Tanggungan', 'amount' => 63000000.00, 'sort_order' => 11],
            ['id' => 'K/I/3', 'name' => 'Kawin Istri Penghasilan, 3 Tanggungan', 'amount' => 67500000.00, 'sort_order' => 12],
        ];

        foreach ($data as $item) {
            PtkpStatus::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}