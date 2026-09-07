<?php
// database/migrations/2024_01_01_000002_update_expenses_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('expense_category');
            
            // Tambahkan kolom baru
            $table->foreignId('expense_category_id')
                  ->after('vendor_name')
                  ->nullable(false) // Wajib diisi
                  ->constrained('expense_categories')
                  ->onDelete('restrict'); // Atau cascade sesuai kebutuhan
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['expense_category_id']);
            $table->dropColumn('expense_category_id');
            $table->string('expense_category', 100)->after('vendor_name');
        });
    }
};