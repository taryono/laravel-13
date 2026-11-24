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
        Schema::create('bos_realizations', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_siplah')->default(false)->after('payment_method');
            $table->string('tax_type', 30)->nullable()->after('tax_amount');   // PPN_11, PPH_21, dll
            $table->string('tax_status', 30)->nullable()->after('tax_type');   // TERPOTONG, CLEARED, dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bos_realizations');
    }
};
