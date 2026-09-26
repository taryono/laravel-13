<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_depreciation_logs', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('asset_id')
                ->constrained('assets')
                ->cascadeOnDelete();

            // Periode penyusutan
            $table->unsignedSmallInteger('period_year');   // ex: 2025
            $table->unsignedTinyInteger('period_month');   // 1 - 12
            $table->date('period_date');                   // tgl posting (ex: 2025-01-31)

            // Nilai
            $table->decimal('opening_book_value', 18, 2);        // nilai buku awal periode
            $table->decimal('depreciation_amount', 18, 2);       // beban penyusutan periode ini
            $table->decimal('accumulated_depreciation', 18, 2);  // akumulasi setelah posting
            $table->decimal('closing_book_value', 18, 2);        // nilai buku akhir periode

            // Metode & snapshot parameter (biar histori tetap akurat walau master berubah)
            $table->string('depreciation_method', 30);
            $table->decimal('purchase_price', 18, 2);
            $table->decimal('salvage_value', 18, 2)->default(0);
            $table->unsignedInteger('useful_life_months');

            // Status jurnal
            $table->boolean('is_posted')->default(false);
            $table->timestamp('posted_at')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Index
            $table->unique(['asset_id', 'period_year', 'period_month'], 'asset_period_unique');
            $table->index(['asset_id', 'period_date']);
            $table->index('is_posted');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_depreciation_logs');
    }
};
