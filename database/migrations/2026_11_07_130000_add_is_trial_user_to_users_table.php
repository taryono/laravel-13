<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'is_trial_user')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_trial_user')->default(false)->after('email')->index();
            });
        }

        // Backfill pengguna yang sebelumnya mendaftar via trial registration
        if (Schema::hasTable('trial_registrations')) {
            $trialUserIds = DB::table('trial_registrations')
                ->whereNotNull('user_id')
                ->pluck('user_id')
                ->unique()
                ->filter();

            if ($trialUserIds->isNotEmpty()) {
                DB::table('users')
                    ->whereIn('id', $trialUserIds)
                    ->update(['is_trial_user' => 1]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'is_trial_user')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_trial_user');
            });
        }
    }
};

