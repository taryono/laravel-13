<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Prasyarat: tabel sorogan_schedules, organizations, kitab_references,
     * teachers, employees, dormitories, rooms, academic_years, academic_semesters
     * sudah dimigrasi lebih dulu.
     */
    public function up(): void
    {
        Schema::create('sorogan_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sorogan_schedule_id')->nullable()->comment('FK ke sorogan_schedules')
                ->constrained('sorogan_schedules')->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->comment('FK ke organizations')
                ->constrained('organizations')->nullOnDelete();
            $table->foreignId('kitab_reference_id')->nullable()->comment('FK ke kitab_references')
                ->constrained('kitab_references')->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->comment('FK ke teachers')
                ->constrained('teachers')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->comment('FK ke employees')
                ->constrained('employees')->nullOnDelete();
            $table->foreignId('dormitory_id')->nullable()->comment('FK ke dormitories')
                ->constrained('dormitories')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->comment('FK ke rooms')
                ->constrained('rooms')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->comment('FK ke academic_years')
                ->constrained('academic_years')->nullOnDelete();
            $table->foreignId('academic_semester_id')->nullable()->comment('FK ke academic_semesters')
                ->constrained('academic_semesters')->nullOnDelete();

            $table->string('title', 200);
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->dateTime('actual_start_at')->nullable();
            $table->dateTime('actual_end_at')->nullable();
            $table->string('location', 255)->nullable();
            $table->string('chapter', 100)->nullable();
            $table->unsignedInteger('page_from')->nullable();
            $table->unsignedInteger('page_to')->nullable();
            $table->text('topic')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled', 'postponed'])
                ->default('scheduled');
            $table->string('cancellation_reason', 255)->nullable();
            $table->unsignedInteger('total_participants')->default(0);
            $table->unsignedInteger('present_count')->default(0);
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('session_date', 'idx_sorogan_sess_date');
            $table->index('status', 'idx_sorogan_sess_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorogan_sessions');
    }
};