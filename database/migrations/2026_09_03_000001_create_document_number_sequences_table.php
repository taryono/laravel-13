<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel ini menyimpan counter terakhir untuk setiap kombinasi
     * organisasi + jenis dokumen + periode (tahun/bulan/tanpa periode).
     *
     * Satu baris = satu "sequence" yang di-lock (SELECT ... FOR UPDATE)
     * setiap kali nomor baru diminta, supaya aman untuk concurrency.
     */
    public function up(): void
    {
        Schema::create('document_number_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('document_type', 50);   // contoh: 'contract', 'loan', 'payroll_run', dst
            $table->string('period_key', 20);       // contoh: '2026', '2026-09', atau 'ALL' jika tanpa reset
            $table->unsignedBigInteger('last_number')->default(0);
            $table->timestamps();

            $table->unique(
                ['organization_id', 'document_type', 'period_key'],
                'doc_number_sequences_unique'
            );

            $table->foreign('organization_id')
                ->references('id')->on('organizations')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_number_sequences');
    }
};
