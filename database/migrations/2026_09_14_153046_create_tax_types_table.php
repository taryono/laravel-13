<?php 
// database/migrations/xxxx_xx_xx_create_tax_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('code', 20)->unique(); // pph21, pph23, pph25, ppn, ppnbm, other
            $table->string('name'); // Nama lengkap: PPh Pasal 21, PPN, dll.
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });

        // Isi data awal sesuai enum lama
        DB::table('tax_types')->insert([
            ['code' => 'pph21', 'name' => 'PPh Pasal 21', 'description' => 'Pajak Penghasilan Pasal 21', 'is_active' => true],
            ['code' => 'pph23', 'name' => 'PPh Pasal 23', 'description' => 'Pajak Penghasilan Pasal 23', 'is_active' => true],
            ['code' => 'pph25', 'name' => 'PPh Pasal 25', 'description' => 'Pajak Penghasilan Pasal 25', 'is_active' => true],
            ['code' => 'ppn',   'name' => 'PPN',          'description' => 'Pajak Pertambahan Nilai', 'is_active' => true],
            ['code' => 'ppnbm', 'name' => 'PPnBM',        'description' => 'Pajak Penjualan Barang Mewah', 'is_active' => true],
            ['code' => 'other', 'name' => 'Lainnya',      'description' => 'Jenis pajak lainnya', 'is_active' => true],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_types');
    }
};