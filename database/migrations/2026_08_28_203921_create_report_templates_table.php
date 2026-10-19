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
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('type', 50)->default('academic');
            $table->unsignedBigInteger('curriculum_id')->nullable();
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->string('paper_size', 20)->default('A4');
            $table->decimal('custom_paper_width', 8, 2)->nullable();
            $table->decimal('custom_paper_height', 8, 2)->nullable();
            $table->string('orientation', 20)->default('portrait');
            $table->decimal('margin_top', 4, 2)->default(2.00);
            $table->decimal('margin_right', 4, 2)->default(2.00);
            $table->decimal('margin_bottom', 4, 2)->default(2.00);
            $table->decimal('margin_left', 4, 2)->default(2.50);
            $table->longText('configuration')->nullable();
            $table->longText('compiled_html')->nullable();
            $table->longText('compiled_css')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('report_template_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->integer('version')->default(1);
            $table->longText('configuration')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('template_id')->references('id')->on('report_templates')->onDelete('cascade');
        });

        Schema::create('report_template_curriculums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_template_id');
            $table->unsignedBigInteger('curriculum_id')->nullable();
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('report_template_id')->references('id')->on('report_templates')->onDelete('cascade');
        });

        Schema::create('template_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('name', 255);
            $table->string('type', 50);
            $table->string('file_path', 255);
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('report_template_signatories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_template_id');
            $table->string('role', 50)->default('headmaster');
            $table->string('label', 100)->nullable();
            $table->string('data_source', 50)->default('automatic');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('manual_name', 150)->nullable();
            $table->string('manual_employee_number', 50)->nullable();
            $table->unsignedBigInteger('signature_image_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('report_template_id')->references('id')->on('report_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_template_signatories');
        Schema::dropIfExists('template_assets');
        Schema::dropIfExists('report_template_curriculums');
        Schema::dropIfExists('report_template_versions');
        Schema::dropIfExists('report_templates');
    }
};
