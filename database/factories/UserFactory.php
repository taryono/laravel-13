<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password123'), // sandi seragam untuk uji coba
            'remember_token'    => Str::random(10),

            // Kolom khusus tabel kamu
            'two_factor_secret'         => null,
            'two_factor_recovery_codes'  => null,
            'two_factor_confirmed_at'    => null,
            'last_login_at'              => $this->faker->optional()->dateTimeThisMonth(),
            'last_login_ip'              => $this->faker->optional()->ipv4(),
            'current_organization_id'    => $this->faker->optional()->randomElement([1, 2, 3]),
            'isCheckingRole'             => $this->faker->optional()->randomElement([0, 1]),
            'created_by'                 => null,
            'updated_by'                 => null,
        ];
    }

    // State khusus: Admin Utama / sudah diverifikasi lengkap
    public function admin()
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => now(),
            'current_organization_id' => 1,
            'isCheckingRole' => 1,
        ]);
    }

    // State: Belum verifikasi email
    public function unverified()
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
