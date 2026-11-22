<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subdistricts', function (Blueprint $table) {
            $table->id();
            $table->char('code', 10)->nullable();
            $table->string('name', 45)->nullable();
            $table->integer('district_id')->nullable();
            $table->char('zip', 5)->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('elevation')->nullable();
            $table->string('timezone')->nullable();
            $table->integer('ordering')->nullable();
            $table->integer('last_id')->nullable();
            $table->integer('is_free_text')->nullable();
            $table->datetime('verified_at')->nullable();
            $table->integer('is_user_input')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('district_id', 'ng_country_id');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subdistricts');
    }
};
