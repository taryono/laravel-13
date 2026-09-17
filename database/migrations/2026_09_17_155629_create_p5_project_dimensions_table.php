<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p5_project_dimensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('p5_projects')
                ->cascadeOnDelete();
            $table->foreignId('dimension_id')
                ->constrained('p5_dimensions')
                ->cascadeOnDelete();

            // Elemen spesifik yang jadi fokus (opsional, JSON array of element_id)
            $table->json('element_ids')->nullable();

            // Bobot penilaian per dimensi (opsional, kalau mau weighted)
            $table->decimal('weight', 5, 2)->default(100.00);

            // Catatan
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['project_id', 'dimension_id'], 'unique_project_dimension');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p5_project_dimensions');
    }
};
