<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountCategoriesSeeder extends Seeder
{
    public function run()
    {
        $organizationId = 1;
        $now = now();

        $categories = [
            [
                'code' => 'ASSET',
                'name' => 'Aset (Aktiva)',
                'type' => 'aset',
                'normal_balance' => 'debit',
                'description' => 'Segala sesuatu yang dimiliki yayasan yang memiliki nilai ekonomis',
                'is_active' => 1,
            ],
            [
                'code' => 'LIABILITY',
                'name' => 'Kewajiban (Liabilitas)',
                'type' => 'kewajiban',
                'normal_balance' => 'kredit',
                'description' => 'Semua utang yayasan kepada pihak ketiga',
                'is_active' => 1,
            ],
            [
                'code' => 'EQUITY',
                'name' => 'Ekuitas / Aset Bersih',
                'type' => 'ekuitas',
                'normal_balance' => 'kredit',
                'description' => 'Kekayaan bersih yayasan (Aset - Kewajiban)',
                'is_active' => 1,
            ],
            [
                'code' => 'REVENUE',
                'name' => 'Pendapatan',
                'type' => 'pendapatan',
                'normal_balance' => 'kredit',
                'description' => 'Semua pemasukan yayasan dari berbagai sumber',
                'is_active' => 1,
            ],
            [
                'code' => 'EXPENSE',
                'name' => 'Beban',
                'type' => 'beban',
                'normal_balance' => 'debit',
                'description' => 'Semua pengeluaran yayasan untuk operasional',
                'is_active' => 1,
            ],
        ];

        foreach ($categories as $category) {
            $existing = DB::table('account_categories')
                ->where('organization_id', $organizationId)
                ->where('code', $category['code'])
                ->first();

            if ($existing) {
                DB::table('account_categories')
                    ->where('id', $existing->id)
                    ->update([
                        'name' => $category['name'],
                        'type' => $category['type'],
                        'normal_balance' => $category['normal_balance'],
                        'description' => $category['description'],
                        'is_active' => $category['is_active'],
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('account_categories')->insert([
                    'organization_id' => $organizationId,
                    'code' => $category['code'],
                    'name' => $category['name'],
                    'type' => $category['type'],
                    'normal_balance' => $category['normal_balance'],
                    'description' => $category['description'],
                    'is_active' => $category['is_active'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
