<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p5_assessments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                ->constrained('p5_projects')
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()
                ->constrained('p5_groups')
                ->nullOnDelete();

            // Deskripsi capaian (narasi untuk rapor)
            $table->text('achievement_description')->nullable();

            // Deskripsi per dimensi (JSON: {"kreatif": "...", "mandiri": "..."})
            $table->json('dimension_notes')->nullable();

            // Penilaian keseluruhan
            $table->enum('overall_predicate', [
                'sangat_berkembang',        // SB
                'berkembang_sesuai_harapan', // BSH
                'mulai_berkembang',          // MB
                'belum_berkembang',          // BB
            ])->nullable();

            // Refleksi siswa
            $table->text('student_reflection')->nullable();

            // Catatan fasilitator
            $table->text('facilitator_notes')->nullable();

            // Status penilaian
            $table->enum('status', ['draft', 'submitted', 'approved', 'published'])
                ->default('draft');

            // Approval
            $table->foreignId('assessed_by')->nullable()
                ->constrained('teachers')->nullOnDelete();
            $table->timestamp('assessed_at')->nullable();
            $table->foreignId('approved_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            // Publish ke rapor
            $table->boolean('is_published_to_report')->default(false);
            $table->timestamp('published_at')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['project_id', 'student_id'], 'unique_p5_assessment');
            $table->index('student_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p5_assessments');
    }
};
