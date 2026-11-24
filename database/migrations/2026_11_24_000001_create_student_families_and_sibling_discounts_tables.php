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
        // 1. student_families
        Schema::create('student_families', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('family_code', 50);
            $table->string('family_name', 150);
            $table->unsignedBigInteger('primary_parent_id')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Indexes
            $table->unique(['organization_id', 'family_code'], 'idx_student_families_unique_code');
            $table->index('organization_id', 'idx_student_families_org');
            $table->index(['organization_id', 'family_name'], 'idx_student_families_name');
            $table->index('primary_parent_id', 'idx_student_families_primary_parent');
            $table->index('deleted_at', 'idx_student_families_deleted_at');
            $table->index('created_by', 'idx_student_families_created_by');
            $table->index('updated_by', 'idx_student_families_updated_by');

            // Foreign Keys
            $table->foreign('organization_id', 'sf_organization_fk')
                ->references('id')->on('organizations')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('primary_parent_id', 'sf_primary_parent_fk')
                ->references('id')->on('parents')
                ->onDelete('set null')->onUpdate('restrict');

            $table->foreign('created_by', 'sf_created_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');

            $table->foreign('updated_by', 'sf_updated_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');
        });

        // 2. student_family_members
        Schema::create('student_family_members', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('student_family_id');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('applicant_id')->nullable();
            $table->unsignedSmallInteger('sibling_order')->nullable();
            $table->string('relation', 30)->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('joined_at')->nullable();
            $table->date('left_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Indexes
            $table->unique(['student_family_id', 'student_id'], 'idx_student_family_members_unique_student');
            $table->unique(['student_family_id', 'applicant_id'], 'idx_student_family_members_unique_applicant');
            $table->index('organization_id', 'idx_student_family_members_org');
            $table->index(['student_family_id', 'is_active'], 'idx_student_family_members_family_active');
            $table->index('student_id', 'idx_student_family_members_student');
            $table->index('applicant_id', 'idx_student_family_members_applicant');
            $table->index('deleted_at', 'idx_student_family_members_deleted_at');
            $table->index('created_by', 'idx_student_family_members_created_by');
            $table->index('updated_by', 'idx_student_family_members_updated_by');

            // Foreign Keys
            $table->foreign('organization_id', 'sfm_organization_fk')
                ->references('id')->on('organizations')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('student_family_id', 'sfm_family_fk')
                ->references('id')->on('student_families')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('student_id', 'sfm_student_fk')
                ->references('id')->on('students')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('applicant_id', 'sfm_applicant_fk')
                ->references('id')->on('applicants')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('created_by', 'sfm_created_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');

            $table->foreign('updated_by', 'sfm_updated_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');
        });

        // 3. sibling_discount_programs
        Schema::create('sibling_discount_programs', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('code', 50);
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('min_sibling_count')->default(2);
            $table->enum('calculation_basis', ['percentage', 'fixed_amount'])->default('percentage');
            $table->enum('allocation_method', ['all_members', 'selected_members', 'tiered'])->default('all_members');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Indexes
            $table->unique(['organization_id', 'code'], 'idx_sibling_discount_programs_unique_code');
            $table->index(['organization_id', 'school_id', 'is_active'], 'idx_sibling_discount_programs_org_school');
            $table->index('school_id', 'idx_sibling_discount_programs_school');
            $table->index('deleted_at', 'idx_sibling_discount_programs_deleted_at');
            $table->index('created_by', 'idx_sibling_discount_programs_created_by');
            $table->index('updated_by', 'idx_sibling_discount_programs_updated_by');

            // Foreign Keys
            $table->foreign('organization_id', 'sdp_organization_fk')
                ->references('id')->on('organizations')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('school_id', 'sdp_school_fk')
                ->references('id')->on('schools')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('created_by', 'sdp_created_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');

            $table->foreign('updated_by', 'sdp_updated_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');
        });

        // 4. sibling_discount_rules
        Schema::create('sibling_discount_rules', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('sibling_discount_program_id');
            $table->unsignedSmallInteger('min_sibling_count');
            $table->unsignedSmallInteger('max_sibling_count')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed_amount']);
            $table->decimal('discount_value', 18, 2);
            $table->unsignedInteger('priority')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Indexes
            $table->index(['sibling_discount_program_id', 'min_sibling_count', 'max_sibling_count'], 'idx_sibling_discount_rules_program');
            $table->index('deleted_at', 'idx_sibling_discount_rules_deleted_at');
            $table->index('created_by', 'idx_sibling_discount_rules_created_by');
            $table->index('updated_by', 'idx_sibling_discount_rules_updated_by');

            // Foreign Keys
            $table->foreign('sibling_discount_program_id', 'sdr_program_fk')
                ->references('id')->on('sibling_discount_programs')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('created_by', 'sdr_created_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');

            $table->foreign('updated_by', 'sdr_updated_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');
        });

        // 5. sibling_discount_allocations
        Schema::create('sibling_discount_allocations', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('sibling_discount_rule_id');
            $table->enum('target_type', ['all_members', 'second_onward', 'specific_order', 'youngest', 'oldest'])->default('all_members');
            $table->unsignedSmallInteger('sibling_order_from')->nullable();
            $table->unsignedSmallInteger('sibling_order_to')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed_amount']);
            $table->decimal('discount_value', 18, 2);

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Indexes
            $table->index('sibling_discount_rule_id', 'idx_sibling_discount_allocations_rule');
            $table->index('deleted_at', 'idx_sibling_discount_allocations_deleted_at');
            $table->index('created_by', 'idx_sibling_discount_allocations_created_by');
            $table->index('updated_by', 'idx_sibling_discount_allocations_updated_by');

            // Foreign Keys
            $table->foreign('sibling_discount_rule_id', 'sda_rule_fk')
                ->references('id')->on('sibling_discount_rules')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('created_by', 'sda_created_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');

            $table->foreign('updated_by', 'sda_updated_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');
        });

        // 6. sibling_discount_eligibilities
        Schema::create('sibling_discount_eligibilities', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('academic_year_id');
            $table->unsignedBigInteger('student_family_id');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('applicant_id')->nullable();
            $table->unsignedSmallInteger('sibling_count');
            $table->unsignedSmallInteger('sibling_order');
            $table->boolean('is_eligible')->default(false);
            $table->unsignedBigInteger('discount_program_id')->nullable();
            $table->date('snapshot_date');

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Indexes
            $table->unique(['academic_year_id', 'student_family_id', 'student_id'], 'idx_sibling_discount_eligibilities_unique_student');
            $table->index('organization_id', 'idx_sibling_discount_eligibilities_org');
            $table->index(['student_family_id', 'academic_year_id'], 'idx_sibling_discount_eligibilities_family_year');
            $table->index(['academic_year_id', 'is_eligible'], 'idx_sibling_discount_eligibilities_status');
            $table->index('student_id', 'idx_sibling_discount_eligibilities_student');
            $table->index('applicant_id', 'idx_sibling_discount_eligibilities_applicant');
            $table->index('discount_program_id', 'idx_sibling_discount_eligibilities_program');
            $table->index('deleted_at', 'idx_sibling_discount_eligibilities_deleted_at');
            $table->index('created_by', 'idx_sibling_discount_eligibilities_created_by');
            $table->index('updated_by', 'idx_sibling_discount_eligibilities_updated_by');

            // Foreign Keys
            $table->foreign('organization_id', 'sde_organization_fk')
                ->references('id')->on('organizations')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('academic_year_id', 'sde_academic_year_fk')
                ->references('id')->on('academic_years')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('student_family_id', 'sde_family_fk')
                ->references('id')->on('student_families')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('student_id', 'sde_student_fk')
                ->references('id')->on('students')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('applicant_id', 'sde_applicant_fk')
                ->references('id')->on('applicants')
                ->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('discount_program_id', 'sde_program_fk')
                ->references('id')->on('sibling_discount_programs')
                ->onDelete('set null')->onUpdate('restrict');

            $table->foreign('created_by', 'sde_created_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');

            $table->foreign('updated_by', 'sde_updated_by_fk')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Urutan drop dibalik agar tidak terkena constraint violation
        Schema::dropIfExists('sibling_discount_eligibilities');
        Schema::dropIfExists('sibling_discount_allocations');
        Schema::dropIfExists('sibling_discount_rules');
        Schema::dropIfExists('sibling_discount_programs');
        Schema::dropIfExists('student_family_members');
        Schema::dropIfExists('student_families');
    }
};