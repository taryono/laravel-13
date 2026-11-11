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
            if (!Schema::hasColumn('applicants', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
            if (!Schema::hasColumn('applicants', 'remember_token')) {
                $table->rememberToken()->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            if (Schema::hasColumn('applicants', 'remember_token')) {
                $table->dropRememberToken();
            }
            if (Schema::hasColumn('applicants', 'password')) {
                $table->dropColumn('password');
            }
        });
    }
};

