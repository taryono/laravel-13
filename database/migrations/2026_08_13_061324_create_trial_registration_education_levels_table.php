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
        Schema::create('trial_registration_education_level', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trial_registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('edulation_level_id')->constrained();
            $table->unique(['trial_registration_id', 'edulation_level_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_registration_education_level');
    }
};
