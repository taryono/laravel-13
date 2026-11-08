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
        Schema::create('canteen_menus', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('school_id')->nullable();
            $table->bigInteger('canteen_tenant_id')->nullable();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('barcode', 64)->nullable();
            $table->enum('category', ['food','drink','snack','stationery','other'])->default('food');
            $table->decimal('price', 15, 2)->default(0.00);
            $table->decimal('cost_price', 15, 2)->default(0.00);
            $table->text('photo_url')->nullable();
            $table->tinyInteger('is_available')->default(1);
            $table->unique(['canteen_tenant_id', 'barcode'], 'canteen_menus_barcode_unique');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canteen_menus');
    }
};
