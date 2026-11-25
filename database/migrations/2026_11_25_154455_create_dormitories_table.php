<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dormitories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->comment('FK ke organizations')
                ->constrained('organizations')->nullOnDelete();
            $table->string('code', 50)->comment('Kode asrama');
            $table->string('name', 150)->comment('Nama asrama');
            $table->enum('gender_type', ['male', 'female', 'mixed'])->default('male');
            $table->unsignedInteger('capacity')->default(0);
            $table->string('address', 255)->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'code'], 'uq_dormitories_org_code');
            $table->index('gender_type', 'idx_dormitories_gender');
            $table->index('is_active', 'idx_dormitories_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dormitories');
    }
};