<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refactor menu_categories: drop lalu create ulang (tanpa ALTER TABLE).
     *
     * PERHATIAN: semua data lama di tabel ini akan hilang.
     * Jalankan MenuCategorySeeder setelah migrate.
     */
    public function up(): void
    {
        // Setara dengan SET FOREIGN_KEY_CHECKS = 0 (agar drop tidak gagal
        // jika tabel lain, mis. menus, masih punya FK ke tabel ini)
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('menu_categories');

        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            // Self reference (sebelumnya bigint signed tanpa FK)
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menu_categories')
                ->nullOnDelete();

            $table->string('code', 100);          // slug stabil: keuangan, cbt, dst.
            $table->string('name');               // judul card: "Keuangan"
            $table->string('description')->nullable(); // subjudul card: "SPP & Bayar"

            // Untuk tampilan card di Menu Utama
            $table->string('icon', 100)->nullable();   // nama icon, mis. "wallet"
            $table->string('color', 50)->nullable();   // tema warna, mis. "emerald"
            $table->unsignedInteger('sort_order')->default(0);

            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_to_trial')->default(false);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'code'], 'menu_categories_org_code_unique');
            $table->index('is_system', 'menu_categories_is_system_index');
            $table->index(['organization_id', 'is_active', 'sort_order'], 'menu_categories_org_active_sort_index');
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('menu_categories');
        Schema::enableForeignKeyConstraints();
    }
};