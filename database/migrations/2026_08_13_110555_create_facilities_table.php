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
        // Table: facility_categories
Schema::create('facility_categories', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
});

        // Table: facilities
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade'); // <-- RELASI KE ROOMS
            $table->string('code')->unique(); // FAS-001, FAS-002
            $table->string('name'); // Whiteboard, Proyektor, AC
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, maintenance, broken
            $table->integer('quantity')->default(1); // Jumlah barang
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
