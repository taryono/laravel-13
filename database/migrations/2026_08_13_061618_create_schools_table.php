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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trial_registration_id')->nullable()->constrained();
            $table->string('name');
            $table->string('npsn')->nullable();
            $table->string('jumlah_siswa')->nullable(); // jenjang enum DIHAPUS
            $table->string('alamat')->nullable();
            $table->enum('status', ['trial','active','suspended'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
