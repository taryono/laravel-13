<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\MainController;
use Models\Deduction; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Deduction());
    }

    public function index(Request $request)
    {    
        $now  = \Carbon\Carbon::now('Asia/Jakarta')->locale('id');
        $hour = $now->hour;

        $greeting = match (true) {
            $hour < 11 => 'Selamat Pagi',
            $hour < 15 => 'Selamat Siang',
            $hour < 18 => 'Selamat Sore',
            default    => 'Selamat Malam',
        };

        // ---------- Dummy data (object) ----------
        $app = (object) [
            'name'       => 'EduPortal OS',
            'short_name' => 'EduPortal',
        ];

        $school = (object) [
            'name' => 'SMA Nusantara Smart School',
        ];

        $user = (object) [
            'name'                 => 'Ibu Sarah',
            'initial'              => 'S',
            'role'                 => 'Administrator / Kepala Sekolah',
            'unread_notifications' => 3,
        ];

        $attendance = (object) [
            'academic_year' => 'TA 2024/2025 Genap',
            'percentage'    => 96.8,
            'target'        => 95,
            'present'       => 1180,
            'permit'        => 24,
            'absent'        => 12,
        ];

        $menus = collect([
            ['title' => 'Keuangan',      'subtitle' => 'SPP & Bayar',   'icon' => 'account_balance_wallet', 'tone' => 'emerald', 'url' => '#'],
            ['title' => 'Data Akademik', 'subtitle' => 'Kurikulum',     'icon' => 'school',                 'tone' => 'blue',    'url' => '#'],
            ['title' => 'Fasilitas',     'subtitle' => 'Inventaris Lab', 'icon' => 'apartment',             'tone' => 'amber',   'url' => '#'],
            ['title' => 'Antar Jemput',  'subtitle' => 'Rute Bus',      'icon' => 'directions_bus',         'tone' => 'sky',     'url' => '#'],
            ['title' => 'CBT',           'subtitle' => 'Ujian & Bank',  'icon' => 'laptop_chromebook',      'tone' => 'purple',  'url' => '#'],
            ['title' => 'PPDB Baru',     'subtitle' => 'Pendaftaran',   'icon' => 'how_to_reg',             'tone' => 'rose',    'url' => '#'],
            ['title' => 'Absensi',       'subtitle' => 'Presensi QR',   'icon' => 'fact_check',             'tone' => 'indigo',  'url' => '#'],
            ['title' => 'Karyawan',      'subtitle' => 'SDM & Guru',    'icon' => 'badge',                  'tone' => 'cyan',    'url' => '#'],
            ['title' => 'Akuntansi',     'subtitle' => 'Buku Besar',    'icon' => 'receipt_long',           'tone' => 'teal',    'url' => '#'],
            ['title' => 'Budgeting',     'subtitle' => 'RAPBS 2025',    'icon' => 'pie_chart',              'tone' => 'orange',  'url' => '#'],
        ])->map(fn (array $item) => (object) $item);

        $agendas = collect([
            [
                'tone'        => 'purple',
                'icon'        => 'edit_calendar',
                'badge'       => 'Ujian CBT',
                'time'        => '08:00 - 10:30 WIB',
                'title'       => 'Simulasi Matematika Peminatan XII MIPA',
                'description' => 'Laboratorium Multimedia 1 & 2 • 72 Peserta terdaftar',
            ],
            [
                'tone'        => 'amber',
                'icon'        => 'notification_important',
                'badge'       => 'Batas Pembayaran',
                'time'        => 'Sisa 3 Hari',
                'title'       => 'Jatuh Tempo SPP Periode Maret 2025',
                'description' => '84 Siswa terdata belum menyelesaikan administrasi SPP.',
            ],
            [
                'tone'        => 'sky',
                'icon'        => 'commute',
                'badge'       => 'Armada Siaga',
                'time'        => 'Pukul 14:30 WIB',
                'title'       => 'Rute Penjemputan Sore Siswa Aktif',
                'description' => '4 Unit Bus Sekolah siap di Shelter Gerbang Timur.',
            ],
        ])->map(fn (array $item) => (object) $item);

        $navItems = collect([
            ['label' => 'Beranda',  'icon' => 'dashboard',              'url' => '#', 'active' => true],
            ['label' => 'Akademik', 'icon' => 'school',                 'url' => '#', 'active' => false],
            ['label' => 'Keuangan', 'icon' => 'account_balance_wallet', 'url' => '#', 'active' => false],
            ['label' => 'Pesan',    'icon' => 'chat',                   'url' => '#', 'active' => false],
            ['label' => 'Profil',   'icon' => 'person',                 'url' => '#', 'active' => false],
        ])->map(fn (array $item) => (object) $item);
        $today = $now->translatedFormat('l, d F Y');
        return view('Dashboard::index', compact('app', 'school', 'user', 'greeting', 'today', 'attendance', 'menus', 'agendas', 'navItems'));
    }

}
