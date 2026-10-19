<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_builder_components', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('type', 50);
            $table->string('category', 50)->default('general');
            $table->string('icon', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('curriculum_id')->nullable();
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->longText('default_config')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_builder_components');
    }
};
