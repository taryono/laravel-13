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
        Schema::create('trial_registrations', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique();
            $table->unsignedTinyInteger('current_step')->default(1);
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');

            // Step 1 (kolom "jenjang" enum DIHAPUS -> pindah ke pivot)
            $table->string('nama_sekolah')->nullable();
            $table->string('npsn')->nullable();
            $table->string('jumlah_siswa')->nullable();
            $table->string('alamat_sekolah')->nullable();
            $table->string('nama_user')->nullable();
            $table->enum('jabatan', ['kepala_sekolah','wakil_kepala_sekolah','guru','tata_usaha','admin_it','lainnya'])->nullable();

            // Step 3 (kolom "role_id" FK tunggal DIHAPUS -> pindah ke pivot)

            // Step 4
            $table->boolean('notif_whatsapp')->default(false);
            $table->boolean('notif_email')->default(false);
            $table->boolean('notif_sms')->default(false);

            // Step 5
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('password')->nullable();

            $table->foreignId('school_id')->nullable()->constrained('schools');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_registrations');
    }
};
