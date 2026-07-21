<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED auto_increment
            $table->string('name', 191);
            $table->string('flyout_title')->nullable()->after('name'); 
            $table->string('text', 255)->nullable();
            $table->string('url', 191);
            $table->string('action', 191);
            $table->integer('parent_id');
            $table->integer('is_active')->comment('Status Aktif Menu');
            $table->string('role', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->enum('icon_type', ['material', 'fa'])->default('material')->nullable();
            $table->integer('sequence')->nullable();
            $table->string('can', 255)->nullable()->comment('Akses Key');
            $table->integer('node_id')->nullable();
            $table->integer('is_private')->nullable();
            $table->integer('is_publish')->nullable();
            $table->string('classname', 255)->nullable();
            $table->integer('module_id')->nullable();
            $table->integer('menu_type_id')->nullable()->comment('Posisi Menu');
            $table->string('icon_color', 255)->nullable();
            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};