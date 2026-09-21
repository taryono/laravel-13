<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 2026_09_22_000000_create_subject_categories_table.php
        Schema::create('subject_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('code', 20);
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();

            $table->unique(['organization_id', 'code']);
        });

        // Update subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('organization_id')
                ->constrained('subjects')->onDelete('set null');
            $table->dropColumn('category');
            $table->foreignId('category_id')->nullable()->after('parent_id')
                ->constrained('subject_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_categories');
    }
};
