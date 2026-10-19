<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Models\ReportDataSource;

class ReportDataSourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            [
                'key' => 'academic_scores',
                'name' => 'Nilai Pelajaran (Akademik)',
                'category' => 'academic',
                'description' => 'Data rekapitulasi nilai mata pelajaran santri (Nilai Tugas, UTS, UAS, Nilai Akhir, Predikat, dan Deskripsi Capaian Kompetensi).',
                'is_system' => true,
                'is_active' => true,
                'order' => 1,
                'fields' => [
                    ['field' => 'index', 'label' => 'No', 'type' => 'number', 'default_width' => '6%', 'default_align' => 'center'],
                    ['field' => 'subject.name', 'label' => 'Mata Pelajaran', 'type' => 'string', 'default_width' => '34%', 'default_align' => 'left'],
                    ['field' => 'assignment_score', 'label' => 'Nilai Tugas', 'type' => 'number', 'default_width' => '12%', 'default_align' => 'center'],
                    ['field' => 'midterm_score', 'label' => 'Nilai UTS', 'type' => 'number', 'default_width' => '12%', 'default_align' => 'center'],
                    ['field' => 'final_score', 'label' => 'Nilai UAS', 'type' => 'number', 'default_width' => '12%', 'default_align' => 'center'],
                    ['field' => 'numeric_score', 'label' => 'Nilai Akhir', 'type' => 'number', 'default_width' => '12%', 'default_align' => 'center'],
                    ['field' => 'letter', 'label' => 'Huruf Mutu', 'type' => 'string', 'default_width' => '10%', 'default_align' => 'center'],
                    ['field' => 'predicate', 'label' => 'Predikat', 'type' => 'string', 'default_width' => '14%', 'default_align' => 'center'],
                    ['field' => 'description', 'label' => 'Capaian Kompetensi', 'type' => 'string', 'default_width' => '40%', 'default_align' => 'left'],
                ],
                'sample_data' => [
                    ['index' => 1, 'subject' => ['name' => 'Fiqh Ibadah'], 'assignment_score' => 88, 'midterm_score' => 85, 'final_score' => 90, 'numeric_score' => 88, 'letter' => 'A', 'predicate' => 'Jayyid Jiddan', 'description' => 'Memahami kaidah fiqih ibadah dengan sangat baik.'],
                    ['index' => 2, 'subject' => ['name' => 'Nahwu (Alfiyah)'], 'assignment_score' => 78, 'midterm_score' => 80, 'final_score' => 82, 'numeric_score' => 80, 'letter' => 'B', 'predicate' => 'Jayyid', 'description' => 'Mampu menganalisis kaidah nahwu dan i\'rab secara tepat.'],
                    ['index' => 3, 'subject' => ['name' => 'Tauhid & Aqidah'], 'assignment_score' => 92, 'midterm_score' => 94, 'final_score' => 95, 'numeric_score' => 94, 'letter' => 'A+', 'predicate' => 'Mumtaz', 'description' => 'Menguasai konsep aqidah ahlussunnah secara istimewa.'],
                ],
            ],
            [
                'key' => 'sorogan',
                'name' => 'Kajian Kitab (Sorogan & Bandongan)',
                'category' => 'pesantren',
                'description' => 'Data evaluasi capaian kajian kitab kuning metode sorogan santri (Kelancaran Bacaan, Terjemah, dan Pemahaman).',
                'is_system' => true,
                'is_active' => true,
                'order' => 2,
                'fields' => [
                    ['field' => 'index', 'label' => 'No', 'type' => 'number', 'default_width' => '8%', 'default_align' => 'center'],
                    ['field' => 'book.title', 'label' => 'Nama Kitab', 'type' => 'string', 'default_width' => '42%', 'default_align' => 'left'],
                    ['field' => 'reading_score', 'label' => 'Nilai Bacaan', 'type' => 'number', 'default_width' => '25%', 'default_align' => 'center'],
                    ['field' => 'translation_score', 'label' => 'Pemahaman Makna', 'type' => 'number', 'default_width' => '25%', 'default_align' => 'center'],
                    ['field' => 'comprehension_score', 'label' => 'Pemahaman Konsep', 'type' => 'number', 'default_width' => '25%', 'default_align' => 'center'],
                ],
                'sample_data' => [
                    ['index' => 1, 'book' => ['title' => 'Fathul Qarib'], 'reading_score' => 85, 'translation_score' => 82, 'comprehension_score' => 84],
                    ['index' => 2, 'book' => ['title' => 'Safinatun Najah'], 'reading_score' => 90, 'translation_score' => 88, 'comprehension_score' => 89],
                ],
            ],
            [
                'key' => 'memorization',
                'name' => 'Tahfidz & Hafalan Al-Qur\'an',
                'category' => 'tahfidz',
                'description' => 'Data capaian setoran hafalan Al-Qur\'an santri (Juz, Surat, Kelancaran, Tajwid, dan Adab Tilawah).',
                'is_system' => true,
                'is_active' => true,
                'order' => 3,
                'fields' => [
                    ['field' => 'index', 'label' => 'No', 'type' => 'number', 'default_width' => '8%', 'default_align' => 'center'],
                    ['field' => 'type', 'label' => 'Program / Kategori', 'type' => 'string', 'default_width' => '22%', 'default_align' => 'left'],
                    ['field' => 'portion', 'label' => 'Juz / Capaian Surat', 'type' => 'string', 'default_width' => '30%', 'default_align' => 'left'],
                    ['field' => 'fluency_score', 'label' => 'Kelancaran', 'type' => 'number', 'default_width' => '13%', 'default_align' => 'center'],
                    ['field' => 'tajweed_score', 'label' => 'Tajwid', 'type' => 'number', 'default_width' => '13%', 'default_align' => 'center'],
                    ['field' => 'etiquette_score', 'label' => 'Adab', 'type' => 'number', 'default_width' => '14%', 'default_align' => 'center'],
                ],
                'sample_data' => [
                    ['index' => 1, 'type' => 'Quran Juz 30', 'portion' => 'An-Naba - An-Nas', 'fluency_score' => 92, 'tajweed_score' => 90, 'etiquette_score' => 95],
                    ['index' => 2, 'type' => 'Quran Juz 29', 'portion' => 'Al-Mulk - Al-Mursalat', 'fluency_score' => 88, 'tajweed_score' => 86, 'etiquette_score' => 90],
                ],
            ],
            [
                'key' => 'pesantren_scores',
                'name' => 'Nilai Karakter & Kepesantrenan',
                'category' => 'pesantren',
                'description' => 'Data penilaian aspek akhlak karimah, adab santri, kedisiplinan asrama, dan ibadah praktis.',
                'is_system' => true,
                'is_active' => true,
                'order' => 4,
                'fields' => [
                    ['field' => 'index', 'label' => 'No', 'type' => 'number', 'default_width' => '8%', 'default_align' => 'center'],
                    ['field' => 'aspect', 'label' => 'Aspek Penilaian', 'type' => 'string', 'default_width' => '52%', 'default_align' => 'left'],
                    ['field' => 'score', 'label' => 'Nilai', 'type' => 'number', 'default_width' => '20%', 'default_align' => 'center'],
                    ['field' => 'predicate', 'label' => 'Predikat', 'type' => 'string', 'default_width' => '20%', 'default_align' => 'center'],
                ],
                'sample_data' => [
                    ['index' => 1, 'aspect' => 'Akhlak & Adab Keseharian', 'score' => 95, 'predicate' => 'Mumtaz'],
                    ['index' => 2, 'aspect' => 'Kedisiplinan Sholat Berjamaah', 'score' => 90, 'predicate' => 'Jayyid Jiddan'],
                    ['index' => 3, 'aspect' => 'Kebersihan & Kerapian Kamar', 'score' => 85, 'predicate' => 'Jayyid'],
                ],
            ],
            [
                'key' => 'attendance_summary',
                'name' => 'Rekapitulasi Kehadiran & Ketidakhadiran',
                'category' => 'attendance',
                'description' => 'Data rekap kehadiran santri dalam satu semester (Sakit, Izin, dan Tanpa Keterangan).',
                'is_system' => false,
                'is_active' => true,
                'order' => 5,
                'fields' => [
                    ['field' => 'index', 'label' => 'No', 'type' => 'number', 'default_width' => '10%', 'default_align' => 'center'],
                    ['field' => 'category', 'label' => 'Kategori Ketidakhadiran', 'type' => 'string', 'default_width' => '60%', 'default_align' => 'left'],
                    ['field' => 'count', 'label' => 'Jumlah Hari', 'type' => 'number', 'default_width' => '30%', 'default_align' => 'center'],
                ],
                'sample_data' => [
                    ['index' => 1, 'category' => 'Sakit', 'count' => 2],
                    ['index' => 2, 'category' => 'Izin', 'count' => 1],
                    ['index' => 3, 'category' => 'Tanpa Keterangan (Alpa)', 'count' => 0],
                ],
            ],
            [
                'key' => 'extracurricular_scores',
                'name' => 'Kegiatan Ekstrakurikuler & Organisasi',
                'category' => 'custom',
                'description' => 'Data partisipasi dan predikat kegiatan ekstrakurikuler serta kepengurusan organisasi santri.',
                'is_system' => false,
                'is_active' => true,
                'order' => 6,
                'fields' => [
                    ['field' => 'index', 'label' => 'No', 'type' => 'number', 'default_width' => '8%', 'default_align' => 'center'],
                    ['field' => 'activity_name', 'label' => 'Nama Kegiatan', 'type' => 'string', 'default_width' => '42%', 'default_align' => 'left'],
                    ['field' => 'predicate', 'label' => 'Predikat / Nilai', 'type' => 'string', 'default_width' => '25%', 'default_align' => 'center'],
                    ['field' => 'description', 'label' => 'Keterangan', 'type' => 'string', 'default_width' => '25%', 'default_align' => 'left'],
                ],
                'sample_data' => [
                    ['index' => 1, 'activity_name' => 'Pramuka / Hizbul Wathan', 'predicate' => 'Sangat Baik', 'description' => 'Aktif dalam latihan dan kepemimpinan regu.'],
                    ['index' => 2, 'activity_name' => 'Khitobah / Muhadharah 3 Bahasa', 'predicate' => 'Baik', 'description' => 'Percaya diri membawakan pidato Bahasa Arab.'],
                ],
            ],
        ];

        foreach ($sources as $source) {
            ReportDataSource::updateOrCreate(
                ['key' => $source['key']],
                $source
            );
        }
    }
}
