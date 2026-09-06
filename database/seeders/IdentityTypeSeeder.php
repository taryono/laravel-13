<?php

namespace Database\Seeders;

use Models\IdentityType;
use Illuminate\Database\Seeder;

class IdentityTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['code' => 'KTP', 'name' => 'Kartu Tanda Penduduk', 'max_length' => 16, 'is_required' => 1, 'sort_order' => 1],
            ['code' => 'KK', 'name' => 'Kartu Keluarga', 'max_length' => 16, 'is_required' => 0, 'sort_order' => 2],
            ['code' => 'AKTA', 'name' => 'Akta Kelahiran', 'max_length' => null, 'is_required' => 0, 'sort_order' => 3],
            ['code' => 'NPWP', 'name' => 'Nomor Pokok Wajib Pajak', 'max_length' => 16, 'is_required' => 0, 'sort_order' => 4],
            ['code' => 'BPJSKES', 'name' => 'BPJS Kesehatan', 'max_length' => 13, 'is_required' => 0, 'sort_order' => 5],
            ['code' => 'BPJSTK', 'name' => 'BPJS Ketenagakerjaan', 'max_length' => 11, 'is_required' => 0, 'sort_order' => 6],
            ['code' => 'SIM', 'name' => 'Surat Izin Mengemudi', 'max_length' => 12, 'is_required' => 0, 'sort_order' => 7],
            ['code' => 'PASPOR', 'name' => 'Paspor', 'max_length' => 9, 'is_required' => 0, 'sort_order' => 8],
        ];

        foreach ($data as $item) {
            IdentityType::updateOrCreate(['code' => $item['code']], $item);
        }
    }
}