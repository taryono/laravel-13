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
        // Table: facilities
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade'); // <-- RELASI KE ROOMS
            $table->string('code', 100)->unique(); // FAS-001, FAS-002
            $table->string('name', 191); // Whiteboard, Proyektor, AC
            $table->text('description')->nullable();
            $table->integer('is_active')->default('1'); // active, maintenance, broken
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);
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
