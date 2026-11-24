
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();

            // Organization
            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Identitas pribadi
            $table->string('name', 150);
            $table->char('nik', 16)->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('religion', 20)->nullable();
            $table->char('nationality', 3)->default('IDN');
            $table->enum('marital_status', [
                'SINGLE',
                'MARRIED',
                'DIVORCED',
                'WIDOWED',
            ])->nullable();

            // Kontak
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('home_phone', 20)->nullable();
            $table->string('email', 100)->nullable();

            // Alamat
            $table->text('address')->nullable();
            $table->string('address_detail', 255)->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('village', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('postal_code', 10)->nullable();

            // Pendidikan dan pekerjaan
            $table->enum('education_level', [
                'Tidak Sekolah',
                'SD',
                'SMP',
                'SMA/SMK',
                'D1-D3',
                'S1',
                'S2',
                'S3',
            ])->nullable();

            $table->string('occupation', 100)->nullable();

            $table->enum('occupation_status', [
                'EMPLOYED',
                'SELF_EMPLOYED',
                'UNEMPLOYED',
                'RETIRED',
                'OTHER',
            ])->nullable();

            $table->enum('income_range', [
                '< 1 juta',
                '1 - 3 juta',
                '3 - 5 juta',
                '5 - 10 juta',
                '> 10 juta',
            ])->nullable();

            $table->string('workplace', 150)->nullable();
            $table->string('position', 100)->nullable();

            // Status dan informasi tambahan
            $table->text('special_needs')->nullable();
            $table->boolean('is_alive')->default(true);
            $table->date('death_date')->nullable();
            $table->boolean('is_active')->default(true);

            // Dokumen
            $table->string('family_card_number', 16)->nullable();
            $table->string('id_card_path', 255)->nullable();
            $table->string('family_card_path', 255)->nullable();
            $table->string('photo_path', 255)->nullable();

            // Keterangan
            $table->text('notes')->nullable();

            // Audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Timestamps & Soft Delete
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique(
                ['organization_id', 'nik'],
                'parents_org_nik_unique'
            );

            $table->unique(
                ['organization_id', 'user_id'],
                'parents_org_user_unique'
            );

            $table->index(
                ['organization_id', 'name'],
                'parents_org_name_idx'
            );

            $table->index('phone', 'parents_phone_idx');
            $table->index('email', 'parents_email_idx');

            $table->index(
                ['organization_id', 'is_active'],
                'parents_active_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
