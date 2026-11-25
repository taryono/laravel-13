<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->comment('FK ke rooms')
                ->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('employee_id')->comment('FK ke employees (ustadzah/ustadz)')
                ->constrained('employees')->cascadeOnDelete();
            $table->enum('role', ['head', 'assistant'])->default('assistant')
                ->comment('head = penanggung jawab utama');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('notes', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['room_id', 'employee_id'], 'uq_room_sup_room_emp');
            $table->index('role', 'idx_room_sup_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_supervisors');
    }
};