<?php
// database/migrations/xxxx_xx_xx_create_shift_swap_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_swaps', function (Blueprint $table) {
            $table->id();
            $table->date('date')->comment('Tanggal pertukaran shift');

            // Karyawan A
            $table->foreignId('employee_id_a')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('original_shift_a')->constrained('shifts')->cascadeOnDelete();
            $table->foreignId('target_shift_a')->constrained('shifts')->cascadeOnDelete();

            // Karyawan B
            $table->foreignId('employee_id_b')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('original_shift_b')->constrained('shifts')->cascadeOnDelete();
            $table->foreignId('target_shift_b')->constrained('shifts')->cascadeOnDelete();

            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Pastikan 1 karyawan tidak bisa tukar 2x di hari yang sama
            $table->unique(['date', 'employee_id_a']);
            $table->unique(['date', 'employee_id_b']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_swap');
    }
};