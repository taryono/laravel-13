<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('demo_requests')) {
            Schema::create('demo_requests', function (Blueprint $table) {
                $table->id();
                $table->string('school_name');
                $table->string('person_name');
                $table->string('position');
                $table->string('email');
                $table->string('phone');
                $table->string('package');
                $table->dateTime('demo_datetime');
                $table->text('message')->nullable();
                $table->string('status')->default('pending'); // pending, contacted, scheduled, completed, cancelled
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('status');
                $table->index('email');
                $table->index('phone');
                $table->index('demo_datetime');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_requests');
    }
};
