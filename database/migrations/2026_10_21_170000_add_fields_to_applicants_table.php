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
        Schema::table('applicants', function (Blueprint $table) {
            if (!Schema::hasColumn('applicants', 'admission_path_id')) {
                $table->unsignedBigInteger('admission_path_id')->nullable()->after('admission_id');
                $table->foreign('admission_path_id')->references('id')->on('admission_paths')->onDelete('set null');
            }
            if (!Schema::hasColumn('applicants', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('admission_path_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('applicants', 'nisn')) {
                $table->string('nisn', 20)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('applicants', 'extracurriculars')) {
                $table->json('extracurriculars')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('applicants', 'foreign_language')) {
                $table->string('foreign_language', 100)->nullable()->after('extracurriculars');
            }
            if (!Schema::hasColumn('applicants', 'special_skill')) {
                $table->string('special_skill', 255)->nullable()->after('foreign_language');
            }
            if (!Schema::hasColumn('applicants', 'agree_test')) {
                $table->boolean('agree_test')->default(true)->after('special_skill');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            if (Schema::hasColumn('applicants', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('applicants', 'admission_path_id')) {
                $table->dropForeign(['admission_path_id']);
                $table->dropColumn('admission_path_id');
            }
            $cols = ['nisn', 'extracurriculars', 'foreign_language', 'special_skill', 'agree_test'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('applicants', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
