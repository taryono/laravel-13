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
        // 1. Pivot applicant & applicant_category
        if (!Schema::hasTable('applicant_applicant_category')) {
            Schema::create('applicant_applicant_category', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('applicant_id');
                $table->unsignedBigInteger('applicant_category_id');
                $table->timestamps();

                $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('cascade');
                $table->foreign('applicant_category_id')->references('id')->on('applicant_categories')->onDelete('cascade');
            });
        }

        // 2. Add missing columns to applicant_invoice_items
        if (Schema::hasTable('applicant_invoice_items')) {
            Schema::table('applicant_invoice_items', function (Blueprint $table) {
                if (!Schema::hasColumn('applicant_invoice_items', 'applicant_invoice_id')) {
                    $table->unsignedBigInteger('applicant_invoice_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('applicant_invoice_items', 'name')) {
                    $table->string('name')->nullable()->after('applicant_invoice_id');
                }
                if (!Schema::hasColumn('applicant_invoice_items', 'amount')) {
                    $table->decimal('amount', 15, 2)->default(0)->after('name');
                }
                if (!Schema::hasColumn('applicant_invoice_items', 'description')) {
                    $table->text('description')->nullable()->after('amount');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_applicant_category');
        if (Schema::hasTable('applicant_invoice_items')) {
            Schema::table('applicant_invoice_items', function (Blueprint $table) {
                $table->dropColumn(['applicant_invoice_id', 'name', 'amount', 'description']);
            });
        }
    }
};
