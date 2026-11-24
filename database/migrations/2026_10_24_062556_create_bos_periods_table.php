<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bos_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_count')->nullable()->after('period_number');
            $table->decimal('unit_cost_per_student', 15, 2)->nullable()->after('student_count');
            $table->date('dapodik_cutoff_date')->nullable()->after('unit_cost_per_student');
            $table->decimal('carry_over_amount', 20, 2)->default(0)->after('total_allocation'); // SiLPA TA lalu
            $table->string('audit_status', 50)->nullable()->after('status');
            $table->string('arkas_sync_status', 30)->default('NOT_SYNCED')->after('audit_status');
            $table->timestamp('arkas_synced_at')->nullable()->after('arkas_sync_status');
            $table->string('dinas_verification_status', 30)->default('PENDING')->after('arkas_synced_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bos_periods');
    }
};
