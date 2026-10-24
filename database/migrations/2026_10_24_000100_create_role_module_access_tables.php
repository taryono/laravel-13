<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\ValidationException;

return new class extends Migration
{
    public function up(): void
    {
        // Draft pilihan akses saat wizard trial berjalan.
        Schema::create('trial_registration_role_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trial_registration_id')->constrained('trial_registrations')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['trial_registration_id', 'role_id', 'module_id'], 'trm_registration_role_module_unique');
        });

        // Hasil akhir: role X di sekolah Y boleh mengakses modul Z.
        Schema::create('school_role_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['school_id', 'role_id', 'module_id'], 'srm_school_role_module_unique');
            $table->index(['organization_id', 'school_id'], 'srm_org_school_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_role_modules');
        Schema::dropIfExists('trial_registration_role_modules');
    }
};