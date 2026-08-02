<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * php artisan db:seed --class=MenuManagementMenuSeeder
     * php artisan db:seed --class=AclSeeder
     */
    public function run(): void
    {
        User::factory(10)->create(); 
        //  $this->call(AclSeeder::class);
        // $this->call(MenuManagementMenuSeeder::class);
    }
}
