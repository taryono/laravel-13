<?php
// database/migrations/xxxx_xx_xx_create_teachers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nip', 50)->nullable();
            $table->string('name', 150);
            $table->string('specialization', 100)->nullable();
            $table->unsignedBigInteger('education_level_id')->nullable();
            $table->date('join_date')->nullable();
            $table->enum('status', ['ACTIVE', 'RESIGNED', 'RETIRED'])->default('ACTIVE');
            $table->string('subject')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->unique(['organization_id', 'nip']);
            $table->index('status');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
