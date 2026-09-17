<?php

namespace Database\Seeders;

use Models\P5Theme;
use Illuminate\Database\Seeder;

class P5ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'code' => 'gaya_hidup_berkelanjutan',
                'name' => 'Gaya Hidup Berkelanjutan',
                'name_en' => 'Sustainable Lifestyle',
                'description' => 'Memahami dampak aktivitas manusia terhadap keberlanjutan kehidupan, serta membangun kesadaran untuk gaya hidup ramah lingkungan.',
                'icon' => 'bi-recycle',
                'color' => '#10b981',
                'order' => 1,
            ],
            [
                'code' => 'kearifan_lokal',
                'name' => 'Kearifan Lokal',
                'name_en' => 'Local Wisdom',
                'description' => 'Menggali dan melestarikan nilai-nilai kearifan lokal sebagai bagian dari identitas dan kekayaan budaya bangsa.',
                'icon' => 'bi-house-heart',
                'color' => '#f59e0b',
                'order' => 2,
            ],
            [
                'code' => 'bhineka_tunggal_ika',
                'name' => 'Bhineka Tunggal Ika',
                'name_en' => 'Unity in Diversity',
                'description' => 'Membangun sikap toleransi, menghargai keberagaman, dan memperkuat persatuan dalam perbedaan.',
                'icon' => 'bi-people-fill',
                'color' => '#ef4444',
                'order' => 3,
            ],
            [
                'code' => 'suara_demokrasi',
                'name' => 'Suara Demokrasi',
                'name_en' => 'Voice of Democracy',
                'description' => 'Melatih kemampuan berpartisipasi dalam proses demokrasi, menyampaikan pendapat, dan menghargai keputusan bersama.',
                'icon' => 'bi-megaphone',
                'color' => '#3b82f6',
                'order' => 4,
            ],
            [
                'code' => 'rekayasa_teknologi',
                'name' => 'Rekayasa dan Teknologi',
                'name_en' => 'Engineering and Technology',
                'description' => 'Mengembangkan kemampuan berpikir kritis dan kreatif dalam menciptakan solusi berbasis teknologi.',
                'icon' => 'bi-cpu',
                'color' => '#8b5cf6',
                'order' => 5,
            ],
            [
                'code' => 'kewirausahaan',
                'name' => 'Kewirausahaan',
                'name_en' => 'Entrepreneurship',
                'description' => 'Menumbuhkan jiwa wirausaha, kemandirian, dan kemampuan menciptakan nilai tambah dari potensi sekitar.',
                'icon' => 'bi-briefcase',
                'color' => '#ec4899',
                'order' => 6,
            ],
            [
                'code' => 'bekerja_sama',
                'name' => 'Bekerja Sama',
                'name_en' => 'Collaboration',
                'description' => 'Membangun kemampuan kolaborasi, komunikasi efektif, dan sinergi dalam mencapai tujuan bersama.',
                'icon' => 'bi-people',
                'color' => '#14b8a6',
                'order' => 7,
            ],
        ];

        foreach ($themes as $theme) {
            P5Theme::updateOrCreate(
                ['code' => $theme['code']],
                $theme
            );
        }

        $this->command->info('✅ 7 tema P5 berhasil di-seed.');
    }
}
