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
        // database/migrations/xxxx_create_sliders_table.php
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();          // untuk admin reference
            $table->enum('type', ['image', 'html']);      // tipe konten
            $table->string('image_path')->nullable();     // path gambar (jika type=image)
            $table->string('blade_view')->nullable();     // nama blade view (jika type=html)
            $table->longText('html_content')->nullable(); // raw HTML (opsional, alternatif blade)
            $table->string('alt_text')->nullable();       // alt image
            $table->string('link_url')->nullable();       // link opsional
            $table->string('link_target', 10)->default('_self');
            $table->json('settings')->nullable();         // setting tambahan (bg, overlay, dll)
            $table->integer('sort_order')->default(0);         // urutan slider
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_at')->nullable();    // jadwal tayang
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
