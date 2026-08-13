<?php

namespace Database\Seeders;

use Models\Dictionary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DictionarySeeder extends Seeder
{
    public function run()
    {
        $dictionaries = [
            [
                'name' => 'Welcome to Our School',
                'group' => 'home',
                'en' => 'Welcome to Our School',
                'in' => 'Selamat Datang di Sekolah Kami'
            ],
            [
                'name' => 'Save Button',
                'group' => 'form',
                'en' => 'Save',
                'in' => 'Simpan'
            ],
             [
                'name' => 'Home',
                'group' => 'navigation',
                'en' => 'Home',
                'in' => 'Beranda'
            ],
            [
                'name' => 'Dashboard',
                'group' => 'navigation',
                'en' => 'Dashboard',
                'in' => 'Dasbor'
            ],
            [
                'name' => 'Profile',
                'group' => 'navigation',
                'en' => 'Profile',
                'in' => 'Profil'
            ],
            
            // Form
            [
                'name' => 'Save',
                'group' => 'form',
                'en' => 'Save',
                'in' => 'Simpan'
            ],
            [
                'name' => 'Cancel',
                'group' => 'form',
                'en' => 'Cancel',
                'in' => 'Batal'
            ],
            [
                'name' => 'Delete',
                'group' => 'form',
                'en' => 'Delete',
                'in' => 'Hapus'
            ],
            [
                'name' => 'Edit',
                'group' => 'form',
                'en' => 'Edit',
                'in' => 'Ubah'
            ],
            
            // Homepage
            [
                'name' => 'Welcome to Our School',
                'group' => 'home',
                'en' => 'Welcome to Our School',
                'in' => 'Selamat Datang di Sekolah Kami'
            ],
            [
                'name' => 'The Best Education for Your Future',
                'group' => 'home',
                'en' => 'The Best Education for Your Future',
                'in' => 'Pendidikan Terbaik untuk Masa Depan Anda'
            ],
            [
                'name' => 'Get Started',
                'group' => 'home',
                'en' => 'Get Started',
                'in' => 'Mulai Sekarang'
            ],
            
            // Package
            [
                'name' => 'Starter Package',
                'group' => 'package',
                'en' => 'Starter Package',
                'in' => 'Paket Starter'
            ],
            [
                'name' => 'Professional Package',
                'group' => 'package',
                'en' => 'Professional Package',
                'in' => 'Paket Profesional'
            ],
            [
                'name' => 'Enterprise Package',
                'group' => 'package',
                'en' => 'Enterprise Package',
                'in' => 'Paket Enterprise'
            ],
        ];

        foreach ($dictionaries as $dict) {
            // Generate key secara otomatis dari name
            $key = Str::snake($dict['name']); // welcome_to_our_school
            
            // Atau gunakan slug untuk URL friendly
            // $key = Str::slug($dict['name'], '_'); // welcome_to_our_school
            
            Dictionary::firstOrCreate(
                ['key' => $key],
                [
                    'group' => $dict['group'],
                    'en' => $dict['en'],
                    'in' => $dict['in']
                ]
            );
        }
    }
}