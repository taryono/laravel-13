<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools', 'id', 'fk_admissions_school')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('academic_year_id')
                ->constrained('academic_years', 'id', 'fk_admissions_academic_year')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('class_level_id')
                ->nullable()
                ->constrained('class_levels', 'id', 'fk_admissions_class_level')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('name', 100);
            $table->string('code', 20);
            $table->string('type', 20)->nullable()->default('REGULAR');

            $table->date('start_date');
            $table->date('end_date');

            $table->unsignedInteger('quota')->default(0);
            $table->decimal('registration_fee', 15, 2)->default(0.00);

            // ---- Batas usia (menggantikan min_age / max_age) ----
            $table->unsignedTinyInteger('min_age_years')->nullable();
            $table->unsignedTinyInteger('min_age_months')->nullable();
            $table->date('min_age_reference_date')->nullable();

            $table->unsignedTinyInteger('max_age_years')->nullable();
            $table->unsignedTinyInteger('max_age_months')->nullable();
            $table->date('max_age_reference_date')->nullable();

            $table->text('requirements')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', 'id', 'fk_admissions_created_by')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users', 'id', 'fk_admissions_updated_by')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // ---- Unique constraint kompatibel softDeletes ----
            // Gunakan generated column portabel (MySQL 5.7+, MariaDB 10.2+, SQLite 3.31+)
            $table->boolean('is_not_deleted')
                ->storedAs('CASE WHEN deleted_at IS NULL THEN 1 ELSE NULL END');

            $table->unique(['school_id', 'code', 'is_not_deleted'], 'uq_admissions_school_code_active');

            // ---- Indexes ----
            $table->index(['school_id', 'academic_year_id', 'is_active'], 'idx_admissions_school_year_active');
            $table->index(['start_date', 'end_date'], 'idx_admissions_dates');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};