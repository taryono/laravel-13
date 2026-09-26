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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('asset_category_id')->constrained('asset_categories')->cascadeOnDelete();
            $table->string('code', 100)->unique();
            $table->string('name', 255);
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->text('specification')->nullable();
            $table->string('serial_number', 255)->nullable();
            $table->date('acquisition_date');
            $table->decimal('acquisition_cost', 15, 2);
            $table->decimal('residual_value', 15, 2)->nullable();
            $table->unsignedInteger('useful_life_months')->nullable();
            $table->enum('depreciation_method', ['straight_line', 'declining_balance', 'units_of_production'])->default('straight_line');
            $table->decimal('accumulated_depreciation', 15, 2)->default(0);
            $table->decimal('book_value', 15, 2)->default(0);
            $table->foreignId('asset_account_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null');
            $table->foreignId('accumulated_depreciation_account_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null');
            $table->foreignId('depreciation_expense_account_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null');
            $table->enum('status', ['draft', 'active', 'disposed', 'fully_depreciated'])->default('draft');
            $table->enum('condition', ['new', 'good', 'minor_damage', 'damaged', 'unusable'])->default('new');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->date('warranty_end')->nullable();
            $table->date('disposed_at')->nullable();
            $table->decimal('disposal_value', 15, 2)->nullable();
            $table->text('disposal_reason')->nullable();
            $table->string('photo_url')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};
