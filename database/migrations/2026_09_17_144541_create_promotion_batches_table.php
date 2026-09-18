<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_batches', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();

            // Relasi tahun ajaran
            $table->foreignId('from_academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('to_academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Statistik
            $table->unsignedInteger('total_students')->default(0);
            $table->unsignedInteger('processed_count')->default(0);
            $table->unsignedInteger('promoted_count')->default(0);
            $table->unsignedInteger('retained_count')->default(0);
            $table->unsignedInteger('graduated_count')->default(0);
            $table->unsignedInteger('transferred_count')->default(0);
            $table->unsignedInteger('dropped_out_count')->default(0);

            // Status proses
            $table->enum('status', ['draft', 'processing', 'completed', 'cancelled'])
                ->default('draft');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexes
            $table->index(['from_academic_year_id', 'to_academic_year_id'], 'idx_batch_years');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_batches');
    }
};
