<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p5_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('p5_projects')
                ->cascadeOnDelete();

            $table->string('name', 150);       // "Kelompok 1", "Tim Hidroponik"
            $table->text('topic')->nullable(); // topik spesifik kelompok
            $table->text('description')->nullable();

            // Ketua kelompok
            $table->foreignId('leader_student_id')->nullable()
                ->constrained('students')->nullOnDelete();

            // Fasilitator kelompok (bisa beda per kelompok)
            $table->foreignId('facilitator_id')->nullable()
                ->constrained('teachers')->nullOnDelete();

            // Output kelompok
            $table->string('product_name')->nullable();
            $table->string('product_photo')->nullable();
            $table->text('product_description')->nullable();

            // Status
            $table->enum('status', ['forming', 'active', 'completed'])
                ->default('forming');

            $table->unsignedInteger('order')->default(0);

            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['project_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p5_groups');
    }
};
