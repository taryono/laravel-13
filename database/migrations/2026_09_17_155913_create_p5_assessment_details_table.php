<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('p5_assessment_details')) {
            Schema::create('p5_assessment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')
                ->constrained('p5_assessments')
                ->cascadeOnDelete();
            $table->foreignId('dimension_id')
                ->constrained('p5_dimensions')
                ->cascadeOnDelete();
            $table->foreignId('element_id')->nullable()
                ->constrained('p5_elements')
                ->nullOnDelete();

            // Capaian
            $table->enum('level', [
                'sangat_berkembang',        // SB
                'berkembang_sesuai_harapan', // BSH
                'mulai_berkembang',          // MB
                'belum_berkembang',          // BB
            ])->nullable();

            // Deskripsi narasi
            $table->text('description')->nullable();

            // Bukti/evidence
            $table->string('evidence_file')->nullable();
            $table->text('evidence_notes')->nullable();

            $table->timestamps();

            $table->index(['assessment_id', 'dimension_id'], 'idx_assessment_dimension');
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('p5_assessment_details');
    }
};
