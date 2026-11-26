<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Perkuat integritas data kantin + dompet digital.
 *
 * Asumsi kondisi awal = dump SQL terakhir (wallet_cards, wallet_topups,
 * canteen_tenant_users masih InnoDB, FK belum lengkap).
 * Jika sebagian perubahan sudah Anda jalankan manual, hapus bagian yang sudah ada.
 *
 * Cadangkan database sebelum menjalankan.
 */
return new class extends Migration {
    public function up(): void
    {
        // 0. Pastikan tidak ada data yatim yang membuat FK gagal dibuat.
        $this->assertNoOrphans('wallet_cards', 'digital_wallet_id', 'digital_wallets');
        $this->assertNoOrphans('wallet_topups', 'digital_wallet_id', 'digital_wallets');
        $this->assertNoOrphans('canteen_tenant_users', 'canteen_tenant_id', 'canteen_tenants');
        $this->assertNoOrphans('canteen_tenant_users', 'user_id', 'users');
        $this->assertNoOrphans('canteen_orders', 'wallet_transaction_id', 'wallet_transactions');

        // 1. InnoDB -> InnoDB (syarat transaksi & foreign key)
        foreach (['wallet_cards', 'wallet_topups', 'canteen_tenant_users'] as $table) {
            DB::statement("ALTER TABLE `{$table}` ENGINE = InnoDB ROW_FORMAT = DYNAMIC");
        }

        // 2. Foreign key untuk tabel yang tadinya InnoDB
        Schema::table('wallet_cards', function (Blueprint $t) {
            $t->foreign('digital_wallet_id', 'wallet_cards_wallet_fk')
                ->references('id')->on('digital_wallets')
                ->restrictOnDelete()->cascadeOnUpdate();
        });

        Schema::table('wallet_topups', function (Blueprint $t) {
            $t->unsignedBigInteger('created_by')->nullable()->after('wallet_transaction_id');

            $t->foreign('digital_wallet_id', 'wallet_topups_wallet_fk')
                ->references('id')->on('digital_wallets')
                ->restrictOnDelete()->cascadeOnUpdate();
            $t->foreign('wallet_transaction_id', 'wallet_topups_wtx_fk')
                ->references('id')->on('wallet_transactions')
                ->restrictOnDelete()->cascadeOnUpdate();
            $t->foreign('created_by', 'wallet_topups_user_fk')
                ->references('id')->on('users')
                ->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::table('canteen_tenant_users', function (Blueprint $t) {
            $t->foreign('canteen_tenant_id', 'ctu_tenant_fk')
                ->references('id')->on('canteen_tenants')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $t->foreign('user_id', 'ctu_user_fk')
                ->references('id')->on('users')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });

        // 3. Rapikan ledger wallet_transactions
        // Perbaiki typo enum 'adjusment' secara aman: tambah dulu nilai baru, pindahkan data, lalu buang yang lama.
        DB::statement("ALTER TABLE wallet_transactions MODIFY transaction_type
            ENUM('topup','canteen_payment','refund','cashout','adjusment','adjustment') NOT NULL");
        DB::table('wallet_transactions')
            ->where('transaction_type', 'adjusment')
            ->update(['transaction_type' => 'adjustment']);
        DB::statement("ALTER TABLE wallet_transactions MODIFY transaction_type
            ENUM('topup','canteen_payment','refund','cashout','adjustment') NOT NULL");

        // Pembayaran kantin dari saldo bukan 'cash', jadi default NULL.
        DB::statement("ALTER TABLE wallet_transactions MODIFY payment_method
            ENUM('cash','bank_transfer','qris','payment_gateway') NULL DEFAULT NULL");

        Schema::table('wallet_transactions', function (Blueprint $t) {
            $t->unique('idempotency_key', 'uq_wtx_idem');
            $t->index(['digital_wallet_id', 'created_at'], 'wallet_tx_wallet_time_index');
            $t->index(['reference_type', 'reference_id'], 'wallet_tx_morph_index');
        });

        DB::statement('ALTER TABLE wallet_transactions ADD CONSTRAINT chk_wtx_amount CHECK (amount > 0)');
        DB::statement("ALTER TABLE wallet_transactions ADD CONSTRAINT chk_wtx_balance CHECK (
            (direction = 'in'  AND balance_after = balance_before + amount) OR
            (direction = 'out' AND balance_after = balance_before - amount)
        )");

        // 4. canteen_orders: lepas FK lama dulu (harus terpisah dari penambahan)
        Schema::table('canteen_orders', function (Blueprint $t) {
            $t->dropForeign('canteen_orders_wallet_fk');
            $t->dropForeign('canteen_orders_settlement_fk');
        });

        Schema::table('canteen_orders', function (Blueprint $t) {
            $t->foreign('digital_wallet_id', 'canteen_orders_wallet_fk')
                ->references('id')->on('digital_wallets')
                ->restrictOnDelete()->restrictOnUpdate();
            $t->foreign('settlement_id', 'canteen_orders_settlement_fk')
                ->references('id')->on('canteen_settlements')
                ->restrictOnDelete()->restrictOnUpdate();
            $t->foreign('wallet_transaction_id', 'canteen_orders_wtx_fk')
                ->references('id')->on('wallet_transactions')
                ->restrictOnDelete()->restrictOnUpdate();

            $t->unique('payment_reference', 'canteen_orders_payref_unique');
        });

        DB::statement('ALTER TABLE canteen_orders ADD CONSTRAINT chk_order_total CHECK (total_amount > 0)');
        DB::statement('ALTER TABLE canteen_orders ADD CONSTRAINT chk_order_commission
            CHECK (commission_amount >= 0 AND commission_amount <= total_amount)');
        DB::statement("ALTER TABLE canteen_orders ADD CONSTRAINT chk_order_wallet
            CHECK (payment_type <> 'wallet_rfid' OR digital_wallet_id IS NOT NULL)");

        // 5. Data uang tidak boleh ikut terhapus bersama organisasi
        Schema::table('digital_wallets', function (Blueprint $t) {
            $t->dropForeign('digital_wallets_org_fk');
        });
        Schema::table('digital_wallets', function (Blueprint $t) {
            $t->foreign('organization_id', 'digital_wallets_org_fk')
                ->references('id')->on('organizations')
                ->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::table('canteen_settlements', function (Blueprint $t) {
            $t->dropForeign('settlement_org_fk');
        });
        Schema::table('canteen_settlements', function (Blueprint $t) {
            $t->foreign('organization_id', 'settlement_org_fk')
                ->references('id')->on('organizations')
                ->restrictOnDelete()->restrictOnUpdate();
        });
    }

    /**
     * Rollback dasar. Engine tidak dikembalikan ke InnoDB dan nilai enum
     * 'adjustment' tidak dikembalikan ke typo, karena itu memang tidak diinginkan.
     */
    public function down(): void
    {
        Schema::table('canteen_settlements', function (Blueprint $t) {
            $t->dropForeign('settlement_org_fk');
        });
        Schema::table('canteen_settlements', function (Blueprint $t) {
            $t->foreign('organization_id', 'settlement_org_fk')
                ->references('id')->on('organizations')
                ->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::table('digital_wallets', function (Blueprint $t) {
            $t->dropForeign('digital_wallets_org_fk');
        });
        Schema::table('digital_wallets', function (Blueprint $t) {
            $t->foreign('organization_id', 'digital_wallets_org_fk')
                ->references('id')->on('organizations')
                ->cascadeOnDelete()->restrictOnUpdate();
        });

        DB::statement('ALTER TABLE canteen_orders DROP CHECK chk_order_wallet');
        DB::statement('ALTER TABLE canteen_orders DROP CHECK chk_order_commission');
        DB::statement('ALTER TABLE canteen_orders DROP CHECK chk_order_total');

        Schema::table('canteen_orders', function (Blueprint $t) {
            $t->dropUnique('canteen_orders_payref_unique');
            $t->dropForeign('canteen_orders_wtx_fk');
            $t->dropForeign('canteen_orders_settlement_fk');
            $t->dropForeign('canteen_orders_wallet_fk');
        });
        Schema::table('canteen_orders', function (Blueprint $t) {
            $t->foreign('digital_wallet_id', 'canteen_orders_wallet_fk')
                ->references('id')->on('digital_wallets')
                ->nullOnDelete()->cascadeOnUpdate();
            $t->foreign('settlement_id', 'canteen_orders_settlement_fk')
                ->references('id')->on('canteen_settlements')
                ->nullOnDelete()->cascadeOnUpdate();
        });

        DB::statement('ALTER TABLE wallet_transactions DROP CHECK chk_wtx_balance');
        DB::statement('ALTER TABLE wallet_transactions DROP CHECK chk_wtx_amount');
        Schema::table('wallet_transactions', function (Blueprint $t) {
            $t->dropIndex('wallet_tx_morph_index');
            $t->dropIndex('wallet_tx_wallet_time_index');
            $t->dropUnique('uq_wtx_idem');
        });

        Schema::table('canteen_tenant_users', function (Blueprint $t) {
            $t->dropForeign('ctu_user_fk');
            $t->dropForeign('ctu_tenant_fk');
        });

        Schema::table('wallet_topups', function (Blueprint $t) {
            $t->dropForeign('wallet_topups_user_fk');
            $t->dropForeign('wallet_topups_wtx_fk');
            $t->dropForeign('wallet_topups_wallet_fk');
            $t->dropColumn('created_by');
        });

        Schema::table('wallet_cards', function (Blueprint $t) {
            $t->dropForeign('wallet_cards_wallet_fk');
        });
    }

    private function assertNoOrphans(string $table, string $column, string $refTable): void
    {
        $count = DB::table($table)
            ->leftJoin("{$refTable} as ref", "{$table}.{$column}", '=', 'ref.id')
            ->whereNotNull("{$table}.{$column}")
            ->whereNull('ref.id')
            ->count();

        if ($count > 0) {
            throw new RuntimeException(
                "Ada {$count} baris di {$table}.{$column} yang tidak punya pasangan di {$refTable}. Bersihkan dulu sebelum migrasi."
            );
        }
    }
};