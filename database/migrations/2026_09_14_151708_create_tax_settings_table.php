<?php

// database/migrations/2026_09_14_151708_modify_tax_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tax_settings', function (Blueprint $table) {
            // Tambahkan kolom kunci asing
            $table->foreignId('tax_type_id')
                ->after('tax_code')
                ->constrained()
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            // Hapus kolom enum lama
            $table->dropColumn('tax_type');
        });
    }

    public function down(): void
    {
        Schema::table('tax_settings', function (Blueprint $table) {
            // Kembalikan kolom enum jika rollback
            $table->enum('tax_type', ['pph21','pph23','pph25','ppn','ppnbm','other'])
                ->after('tax_code')
                ->notNull();

            $table->dropForeign(['tax_type_id']);
            $table->dropColumn('tax_type_id');
        });
    }
};