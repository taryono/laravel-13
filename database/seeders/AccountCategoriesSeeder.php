<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'code' => 'ASSET',
                'name' => 'Aset (Aktiva)',
                'type' => 'aset',
                'normal_balance' => 'debit',
                'description' => 'Segala sesuatu yang dimiliki yayasan yang memiliki nilai ekonomis',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LIABILITY',
                'name' => 'Kewajiban (Liabilitas)',
                'type' => 'kewajiban',
                'normal_balance' => 'kredit',
                'description' => 'Semua utang yayasan kepada pihak ketiga',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'EQUITY',
                'name' => 'Ekuitas / Aset Bersih',
                'type' => 'ekuitas',
                'normal_balance' => 'kredit',
                'description' => 'Kekayaan bersih yayasan (Aset - Kewajiban)',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'REVENUE',
                'name' => 'Pendapatan',
                'type' => 'pendapatan',
                'normal_balance' => 'kredit',
                'description' => 'Semua pemasukan yayasan dari berbagai sumber',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'EXPENSE',
                'name' => 'Beban',
                'type' => 'beban',
                'normal_balance' => 'debit',
                'description' => 'Semua pengeluaran yayasan untuk operasional',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('account_categories')->insert($categories);
    }
}