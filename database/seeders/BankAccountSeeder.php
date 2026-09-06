<?php

namespace Database\Seeders;

use Models\BankAccount;
use Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opsional: Hapus data lama jika perlu
        // DB::table('bank_accounts')->truncate();

        // Pastikan account_id merujuk ke id di tabel accounts (Chart of Accounts)
        // Sesuaikan account_id berikut dengan data akunmu!
        $defaultAccountId = Account::where('code', '1-1000')->orWhere('name', 'Kas dan Bank')->first()?->id 
                            ?? Account::first()?->id 
                            ?? 1;

        $bankAccounts = [
            [
                'account_id'     => $defaultAccountId,
                'bank_name'      => 'Bank Central Asia (BCA)',
                'account_number' => '1234567890',
                'account_name'   => 'PT. Perusahaan Contoh',
                'currency_code'  => 'IDR',
                'current_balance'=> 50000000.00,
                'is_active'      => 1,
                'is_default'    => 1, // Rekening utama
            ],
            [
                'account_id'     => $defaultAccountId,
                'bank_name'      => 'Bank Mandiri',
                'account_number' => '9876543210',
                'account_name'   => 'PT. Perusahaan Contoh',
                'currency_code'  => 'IDR',
                'current_balance'=> 25500750.25,
                'is_active'      => 1,
                'is_default'    => 0,
            ],
            [
                'account_id'     => $defaultAccountId,
                'bank_name'      => 'Bank Rakyat Indonesia (BRI)',
                'account_number' => '1122334455',
                'account_name'   => 'PT. Perusahaan Contoh - Operasional',
                'currency_code'  => 'IDR',
                'current_balance'=> 10000000.00,
                'is_active'      => 1,
                'is_default'    => 0,
            ],
            [
                'account_id'     => $defaultAccountId,
                'bank_name'      => 'Bank Negara Indonesia (BNI)',
                'account_number' => '5566778899',
                'account_name'   => 'PT. Perusahaan Contoh - Gaji',
                'currency_code'  => 'IDR',
                'current_balance'=> 7500000.00,
                'is_active'      => 1,
                'is_default'    => 0,
            ],
            [
                'account_id'     => $defaultAccountId,
                'bank_name'      => 'Bank Syariah Indonesia (BSI)',
                'account_number' => '3344556677',
                'account_name'   => 'PT. Perusahaan Contoh - Syariah',
                'currency_code'  => 'IDR',
                'current_balance'=> 0.00,
                'is_active'      => 0, // Tidak aktif
                'is_default'    => 0,
            ],
        ];

        foreach ($bankAccounts as $account) {
            BankAccount::create($account);
        }
    }
}