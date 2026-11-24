<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('extracurricular_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_extracurricular_id')
                ->constrained('student_extracurriculars', 'id', 'fk_ext_att_student_ext')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Tambahkan constraint & nama FK eksplisit agar aman dari limit 64 karakter MySQL
            $table->foreignId('extracurricular_day_id')
                ->constrained('extracurricular_days', 'id', 'fk_ext_att_day')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->date('meeting_date');

            $table->string('status', 20)->default('PRESENT'); // PRESENT, SICK, PERMIT, ABSENT

            $table->text('notes')->nullable();

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('teachers', 'id', 'fk_ext_att_teacher')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', 'id', 'fk_ext_att_created_by')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users', 'id', 'fk_ext_att_updated_by')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // 1. Unique Constraint: sertakan extracurricular_day_id & deleted_at
            // Catatan: Di MySQL, nilai NULL pada deleted_at dianggap unik (tidak bentrok).
            // Jika ingin mencegah duplikat aktif sepenuhnya, gunakan virtual column (lihat catatan di bawah).
            $table->unique(
                ['student_extracurricular_id', 'extracurricular_day_id', 'meeting_date', 'deleted_at'],
                'uq_ext_att_student_day_date_del'
            );

            // 2. Composite Indexes sesuai pola query harian & rekapitulasi
            $table->index(['extracurricular_day_id', 'meeting_date'], 'idx_ext_att_day_date');
            $table->index(['meeting_date', 'status'], 'idx_ext_att_date_status');
            $table->index(['is_active', 'deleted_at'], 'idx_ext_att_active_deleted');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extracurricular_attendances');
    }
};