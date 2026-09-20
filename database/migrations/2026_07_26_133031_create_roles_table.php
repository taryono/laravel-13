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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code', 50)
                ->nullable() // isi dulu data lama, lalu ubah jadi tidak boleh null
                ->unique()
                ->after('id'); // posisi strategis
            $table->string('name');
            $table->string('display_name');
            $table->string('description');
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('roles')
                ->onDelete('set null');

            // Indeks untuk pencarian cepat
            $table->index('parent_id');

            $table->timestamps();
            $table->unique(['user_id', 'name']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
