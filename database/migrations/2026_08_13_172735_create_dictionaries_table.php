<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dictionaries', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->string('group')->nullable()->index();
            $table->text('name')->index();
            $table->text('en')->nullable();
            $table->text('in')->nullable();
            $table->text('metadata')->nullable(); // JSON untuk info tambahan
            $table->boolean('is_active')->default(true);
            $table->unique(['key', 'locale']); // Kombinasi key + locale unik
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dictionaries');
    }
};