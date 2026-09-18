<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();

            // Relasi utama
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()
                ->constrained('promotion_batches')
                ->nullOnDelete();

            // Tahun ajaran
            $table->foreignId('from_academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('to_academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Kelas asal & tujuan
            $table->foreignId('from_classroom_id')->nullable()
                ->constrained('classrooms')->nullOnDelete();
            $table->foreignId('to_classroom_id')->nullable()
                ->constrained('classrooms')->nullOnDelete();
            $table->foreignId('from_class_level_id')->nullable()
                ->constrained('class_levels')->nullOnDelete();
            $table->foreignId('to_class_level_id')->nullable()
                ->constrained('class_levels')->nullOnDelete();

            // Keputusan
            $table->enum('promotion_status', [
                'pending',
                'promoted',
                'retained',
                'graduated',
                'transferred',
                'dropped_out',
            ])->default('pending');

            // Nilai pertimbangan
            $table->decimal('final_score', 5, 2)->nullable();
            $table->decimal('attendance_rate', 5, 2)->nullable();
            $table->decimal('behavior_score', 5, 2)->nullable();

            // Catatan
            $table->text('decision_reason')->nullable();
            $table->text('notes')->nullable();

            // Approval
            $table->foreignId('decided_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();
            $table->foreignId('approved_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            // Flag manual override
            $table->boolean('is_manual')->default(false);

            // Status proses
            $table->enum('status', ['draft', 'processed', 'finalized', 'cancelled'])
                ->default('draft');

            // Snapshot (untuk rollback)
            $table->json('previous_state')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Unique: 1 siswa hanya 1 record per transisi tahun
            $table->unique(
                ['student_id', 'from_academic_year_id', 'to_academic_year_id'],
                'unique_student_promotion'
            );

            // Indexes
            $table->index('student_id');
            $table->index('batch_id');
            $table->index('promotion_status');
            $table->index(['from_academic_year_id', 'to_academic_year_id'], 'idx_promotion_years');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_promotions');
    }
};
