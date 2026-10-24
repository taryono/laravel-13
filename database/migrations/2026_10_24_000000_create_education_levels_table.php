<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education_levels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('name_en', 100)->nullable();
            $table->text('description')->nullable();

            $table->string('stage', 20);
            $table->integer('sort_order')->default(0);
            $table->unsignedTinyInteger('duration_years')->nullable();
            $table->unsignedTinyInteger('min_age')->nullable();
            $table->unsignedTinyInteger('max_age')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->unique(['organization_id', 'code'], 'education_levels_org_code_unique');
            $table->index(['organization_id', 'is_active', 'sort_order'], 'education_levels_org_active_sort_index');
            $table->index('stage', 'education_levels_stage_index');
        });

        // CHECK constraint: ditegakkan di MySQL 8.0.16+ (versi lama mengabaikannya).
        DB::statement(
            "ALTER TABLE `education_levels`
             ADD CONSTRAINT `education_levels_stage_check`
             CHECK (`stage` IN ('paud', 'dasar', 'menengah', 'tinggi'))"
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('education_levels');
    }
};