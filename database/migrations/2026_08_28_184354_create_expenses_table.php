<?php
// database/migrations/2024_01_01_000002_update_expenses_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                if (Schema::hasColumn('expenses', 'expense_category')) {
                    $table->dropColumn('expense_category');
                }
                
                if (Schema::hasTable('expense_categories') && !Schema::hasColumn('expenses', 'expense_category_id')) {
                    $table->foreignId('expense_category_id')
                          ->after('vendor_name')
                          ->nullable()
                          ->constrained('expense_categories')
                          ->onDelete('restrict');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                if (Schema::hasColumn('expenses', 'expense_category_id')) {
                    $table->dropForeign(['expense_category_id']);
                    $table->dropColumn('expense_category_id');
                }
                if (!Schema::hasColumn('expenses', 'expense_category')) {
                    $table->string('expense_category', 100)->after('vendor_name')->nullable();
                }
            });
        }
    }
};