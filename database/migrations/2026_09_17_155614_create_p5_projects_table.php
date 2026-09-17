<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p5_projects', function (Blueprint $table) {
            $table->id();

            // Identitas
            $table->string('code', 50)->unique();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->text('objectives')->nullable();     // tujuan pembelajaran
            $table->text('essential_questions')->nullable(); // pertanyaan pemandu

            // Relasi konteks akademik
            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('school_id')->nullable()
                ->constrained('schools')->nullOnDelete();
            $table->foreignId('theme_id')
                ->constrained('p5_themes')
                ->restrictOnDelete();

            // Fase & tingkat
            $table->enum('phase', ['A', 'B', 'C', 'D', 'E', 'F'])
                ->comment('A-B: SD, C-D: SMP, E-F: SMA/SMK');
            $table->foreignId('class_level_id')->nullable()
                ->constrained('class_levels')->nullOnDelete();

            // Jadwal
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('total_hours')->default(0);  // total JP
            $table->string('schedule_notes')->nullable();

            // Output & Produk
            $table->string('product_name')->nullable();   // "Video dokumenter", "Taman hidroponik"
            $table->text('product_description')->nullable();
            $table->string('product_photo')->nullable();  // path foto produk
            $table->json('attachments')->nullable();      // file pendukung

            // Koordinator & Fasilitator
            $table->foreignId('coordinator_id')->nullable()
                ->constrained('teachers')->nullOnDelete();
            $table->foreignId('co_coordinator_id')->nullable()
                ->constrained('teachers')->nullOnDelete();

            // Status
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])
                ->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Konfigurasi
            $table->json('settings')->nullable();
            // contoh: {"allow_self_group": true, "min_group_size": 3, "max_group_size": 5}

            // Audit
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['academic_year_id', 'status'], 'idx_p5_year_status');
            $table->index('theme_id');
            $table->index('phase');
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p5_projects');
    }
};
