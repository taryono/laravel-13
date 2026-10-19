<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Models\ReportBuilderComponent;
use Models\ReportTemplate;

class ReportBuilderSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Palette Components
        $components = [
            [
                'name' => 'Kop Pondok / Lembaga',
                'type' => 'text',
                'category' => 'text',
                'icon' => '🔝',
                'description' => 'Header nama pesantren/lembaga, alamat, dan kontak.',
                'order' => 1,
                'is_active' => true,
                'default_config' => [
                    'type' => 'text',
                    'content' => "YAYASAN PONDOK PESANTREN AL-HIKMAH\nLAPORAN HASIL BELAJAR SANTRI (RAPOR)\nTAHUN AJARAN [[ academic_year.start_year ]]/[[ academic_year.end_year ]] - SEMESTER [[ academic_year.semester ]]",
                    'style' => [
                        'fontSize' => 14,
                        'fontWeight' => 'bold',
                        'textAlign' => 'center',
                        'color' => '#000000',
                        'fontFamily' => 'Times New Roman',
                        'marginBottom' => 15,
                    ],
                ],
            ],
            [
                'name' => 'Identitas Santri (Biodata)',
                'type' => 'keyvalue',
                'category' => 'blocks',
                'icon' => '👤',
                'description' => 'Tabel ringkas nama santri, NIS, kelas, dan asrama.',
                'order' => 2,
                'is_active' => true,
                'default_config' => [
                    'type' => 'keyvalue',
                    'fields' => [
                        ['label' => 'Nama Santri', 'binding' => 'student.full_name'],
                        ['label' => 'NIS / NISN', 'binding' => 'student.student_number'],
                        ['label' => 'Kelas / Tingkat', 'binding' => 'classroom.name'],
                        ['label' => 'Kamar / Asrama', 'binding' => 'dormitory.name'],
                    ],
                    'style' => [
                        'fontSize' => 11,
                        'textAlign' => 'left',
                        'color' => '#000000',
                        'fontFamily' => 'Times New Roman',
                        'marginBottom' => 15,
                    ],
                ],
            ],
            [
                'name' => 'Tabel Capaian Akademik',
                'type' => 'table',
                'category' => 'blocks',
                'icon' => '📊',
                'description' => 'Daftar nilai mata pelajaran akademik, angka, predikat, dan deskripsi.',
                'order' => 3,
                'is_active' => true,
                'default_config' => [
                    'type' => 'table',
                    'title' => 'A. CAPAIAN KOMPETENSI AKADEMIK',
                    'dataSource' => 'academic_scores',
                    'columns' => [
                        ['header' => 'No', 'field' => 'index', 'width' => '6%', 'align' => 'center'],
                        ['header' => 'Mata Pelajaran', 'field' => 'subject.name', 'width' => '34%', 'align' => 'left'],
                        ['header' => 'Tugas', 'field' => 'assignment_score', 'width' => '10%', 'align' => 'center'],
                        ['header' => 'UTS', 'field' => 'midterm_score', 'width' => '10%', 'align' => 'center'],
                        ['header' => 'UAS', 'field' => 'final_score', 'width' => '10%', 'align' => 'center'],
                        ['header' => 'Akhir', 'field' => 'numeric_score', 'width' => '10%', 'align' => 'center'],
                        ['header' => 'Huruf', 'field' => 'letter', 'width' => '8%', 'align' => 'center'],
                        ['header' => 'Predikat', 'field' => 'predicate', 'width' => '12%', 'align' => 'center'],
                    ],
                    'style' => [
                        'fontSize' => 10,
                        'textAlign' => 'left',
                        'color' => '#000000',
                        'fontFamily' => 'Times New Roman',
                        'marginBottom' => 15,
                    ],
                    'showFooter' => true,
                    'footerLabel' => 'Rata-rata Nilai',
                    'footerField' => 'average_score',
                ],
            ],
            [
                'name' => 'Tabel Sorogan & Kitab Kuning',
                'type' => 'table',
                'category' => 'blocks',
                'icon' => '📖',
                'description' => 'Daftar capaian sorogan kitab kuning (makna & pemahaman).',
                'order' => 4,
                'is_active' => true,
                'default_config' => [
                    'type' => 'table',
                    'title' => 'B. CAPAIAN KAJIAN KITAB (SOROGAN)',
                    'dataSource' => 'sorogan',
                    'columns' => [
                        ['header' => 'No', 'field' => 'index', 'width' => '8%', 'align' => 'center'],
                        ['header' => 'Kitab yang Diujikan', 'field' => 'book.title', 'width' => '42%', 'align' => 'left'],
                        ['header' => 'Qiro\'ah (Baca)', 'field' => 'reading_score', 'width' => '16%', 'align' => 'center'],
                        ['header' => 'Tarjamah (Makna)', 'field' => 'translation_score', 'width' => '17%', 'align' => 'center'],
                        ['header' => 'Faham (Pemahaman)', 'field' => 'comprehension_score', 'width' => '17%', 'align' => 'center'],
                    ],
                    'style' => [
                        'fontSize' => 10,
                        'textAlign' => 'left',
                        'color' => '#000000',
                        'fontFamily' => 'Times New Roman',
                        'marginBottom' => 15,
                    ],
                    'showFooter' => false,
                ],
            ],
            [
                'name' => 'Tabel Tahfidz Al-Qur\'an',
                'type' => 'table',
                'category' => 'blocks',
                'icon' => '🕌',
                'description' => 'Daftar capaian hafalan juz, tajwid, dan adab.',
                'order' => 5,
                'is_active' => true,
                'default_config' => [
                    'type' => 'table',
                    'title' => 'C. CAPAIAN TAHFIDZUL QUR\'AN',
                    'dataSource' => 'memorization',
                    'columns' => [
                        ['header' => 'No', 'field' => 'index', 'width' => '8%', 'align' => 'center'],
                        ['header' => 'Juz / Surat', 'field' => 'portion', 'width' => '42%', 'align' => 'left'],
                        ['header' => 'Kelancaran', 'field' => 'fluency_score', 'width' => '16%', 'align' => 'center'],
                        ['header' => 'Tajwid', 'field' => 'tajweed_score', 'width' => '17%', 'align' => 'center'],
                        ['header' => 'Adab & Tartil', 'field' => 'etiquette_score', 'width' => '17%', 'align' => 'center'],
                    ],
                    'style' => [
                        'fontSize' => 10,
                        'textAlign' => 'left',
                        'color' => '#000000',
                        'fontFamily' => 'Times New Roman',
                        'marginBottom' => 15,
                    ],
                    'showFooter' => false,
                ],
            ],
            [
                'name' => 'Tabel Kepesantrenan & Karakter',
                'type' => 'table',
                'category' => 'blocks',
                'icon' => '⭐',
                'description' => 'Aspek kedisiplinan, akhlak, dan kepesantrenan.',
                'order' => 6,
                'is_active' => true,
                'default_config' => [
                    'type' => 'table',
                    'title' => 'D. PENILAIAN AKHLAK & KEPESANTRENAN',
                    'dataSource' => 'pesantren_scores',
                    'columns' => [
                        ['header' => 'No', 'field' => 'index', 'width' => '10%', 'align' => 'center'],
                        ['header' => 'Aspek Penilaian', 'field' => 'aspect', 'width' => '55%', 'align' => 'left'],
                        ['header' => 'Nilai', 'field' => 'score', 'width' => '15%', 'align' => 'center'],
                        ['header' => 'Predikat', 'field' => 'predicate', 'width' => '20%', 'align' => 'center'],
                    ],
                    'style' => [
                        'fontSize' => 10,
                        'textAlign' => 'left',
                        'color' => '#000000',
                        'fontFamily' => 'Times New Roman',
                        'marginBottom' => 15,
                    ],
                    'showFooter' => false,
                ],
            ],
            [
                'name' => 'Catatan Wali Kelas',
                'type' => 'text-block',
                'category' => 'blocks',
                'icon' => '💬',
                'description' => 'Kotak catatan evaluasi & motivasi dari wali kelas.',
                'order' => 7,
                'is_active' => true,
                'default_config' => [
                    'type' => 'text-block',
                    'title' => 'Catatan Wali Kelas:',
                    'binding' => 'report_card.homeroom_notes',
                    'style' => [
                        'fontSize' => 11,
                        'fontStyle' => 'italic',
                        'textAlign' => 'left',
                        'color' => '#000000',
                        'fontFamily' => 'Times New Roman',
                        'marginBottom' => 15,
                    ],
                ],
            ],
            [
                'name' => 'Tanda Tangan Pejabat (TTD)',
                'type' => 'signature',
                'category' => 'blocks',
                'icon' => '✍️',
                'description' => 'Blok tanda tangan wali kelas dan kepala sekolah/pengasuh.',
                'order' => 8,
                'is_active' => true,
                'default_config' => [
                    'type' => 'signature',
                    'columns' => [
                        [
                            'role' => 'homeroom_teacher',
                            'label' => 'Wali Kelas',
                            'source' => 'automatic',
                            'showImage' => true,
                            'showEmployeeNumber' => true,
                        ],
                        [
                            'role' => 'headmaster',
                            'label' => 'Pengasuh / Kepala Pesantren',
                            'source' => 'automatic',
                            'showImage' => true,
                            'showEmployeeNumber' => false,
                        ],
                    ],
                    'style' => [
                        'fontSize' => 11,
                        'textAlign' => 'center',
                        'color' => '#000000',
                        'fontFamily' => 'Times New Roman',
                        'marginTop' => 20,
                    ],
                ],
            ],
            [
                'name' => 'Garis Pemisah (Divider)',
                'type' => 'divider',
                'category' => 'text',
                'icon' => '➖',
                'description' => 'Garis horizontal pemisah antar bab atau section.',
                'order' => 9,
                'is_active' => true,
                'default_config' => [
                    'type' => 'divider',
                    'style' => [
                        'borderTop' => '2px solid #000000',
                        'margin' => '10px 0',
                    ],
                ],
            ],
            [
                'name' => 'Pindah Halaman (Page Break)',
                'type' => 'pagebreak',
                'category' => 'text',
                'icon' => '📄',
                'description' => 'Memaksa lembar cetak baru saat di-generate ke PDF.',
                'order' => 10,
                'is_active' => true,
                'default_config' => [
                    'type' => 'pagebreak',
                    'style' => [],
                ],
            ],
        ];

        foreach ($components as $comp) {
            ReportBuilderComponent::updateOrCreate(
                ['name' => $comp['name']],
                $comp
            );
        }

        // 2. Seed Default Standard Report Template if not exists
        if (ReportTemplate::count() === 0) {
            $defaultSections = [];
            $order = 1;
            foreach ($components as $c) {
                if ($c['type'] === 'pagebreak') continue;
                $sec = $c['default_config'];
                $sec['id'] = 'sec_seed_' . $order;
                $sec['order'] = $order++;
                $defaultSections[] = $sec;
            }

            $template = ReportTemplate::create([
                'name' => 'Template Rapor Standar Pesantren & Akademik',
                'description' => 'Template resmi pondok pesantren terpadu dengan capaian akademik, sorogan, tahfidz, dan adab santri.',
                'type' => 'combined',
                'paper_size' => 'A4',
                'orientation' => 'portrait',
                'margin_top' => 2.0,
                'margin_right' => 2.0,
                'margin_bottom' => 2.0,
                'margin_left' => 2.5,
                'is_active' => true,
                'is_default' => true,
                'configuration' => [
                    'version' => '1.0',
                    'page' => [
                        'size' => 'A4',
                        'orientation' => 'portrait',
                        'margin' => ['top' => 2.0, 'right' => 2.0, 'bottom' => 2.0, 'left' => 2.5],
                        'width' => 210,
                        'height' => 297,
                        'background' => '#ffffff',
                    ],
                    'globalStyle' => [
                        'fontFamily' => 'Times New Roman',
                        'fontSize' => 12,
                        'color' => '#000000',
                        'lineHeight' => 1.4,
                    ],
                    'sections' => $defaultSections,
                ],
            ]);

            // Sync version
            $template->versions()->create([
                'version' => 1,
                'configuration' => $template->configuration,
            ]);
        }
    }
}
