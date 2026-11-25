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
        Schema::create('bos_reports', function (Blueprint $table) {
            $table->id();
            $table->string('tte_document_number', 100)->nullable()->after('verified_by');
            $table->timestamp('tte_signed_at')->nullable()->after('tte_document_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bos_reports');
    }
};
