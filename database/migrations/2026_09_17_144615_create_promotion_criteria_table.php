<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_criteria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();
            $table->foreignId('class_level_id')->nullable()
                ->constrained('class_levels')->cascadeOnDelete();

            // Threshold nilai
            $table->decimal('min_final_score', 5, 2)->nullable();
            $table->decimal('min_attendance_rate', 5, 2)->nullable();
            $table->decimal('min_behavior_score', 5, 2)->nullable();
            $table->unsignedInteger('max_failed_subjects')->default(0);

            // Perilaku sistem
            $table->boolean('auto_promote')->default(true);
            $table->boolean('allow_manual_override')->default(true);

            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Satu kriteria per tahun + tingkat
            $table->unique(['academic_year_id', 'class_level_id'], 'unique_criteria');
            $table->index(['academic_year_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_criteria');
    }
};
