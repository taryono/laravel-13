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
        Schema::create('report_data_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->index();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('category')->default('academic')->index();
            $table->text('description')->nullable();
            $table->json('fields')->nullable();
            $table->json('sample_data')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true)->index();
            $table->integer('order')->default(0);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_data_sources');
    }
};
