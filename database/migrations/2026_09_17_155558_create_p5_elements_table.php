<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p5_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dimension_id')
                ->constrained('p5_dimensions')
                ->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['dimension_id', 'code'], 'unique_element_per_dimension');
            $table->index('dimension_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p5_elements');
    }
};
