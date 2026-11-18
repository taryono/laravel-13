<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\MainController;
use Models\Deduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Str;

class DashboardController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Deduction());
    }

    public function index(Request $request)
    {
        $now = \Carbon\Carbon::now('Asia/Jakarta')->locale('id');

        $greeting = greetings();

        // ---------- Dummy data (object) ----------
        $app = (object) [
            'name' => 'EduPortal OS',
            'short_name' => 'EduPortal',
        ];

        $school = (object) [
            'name' => 'SMA Nusantara Smart School',
        ];

        $user = (object) [
            'name' => 'Ibu Sarah',
            'initial' => 'S',
            'role' => 'Administrator / Kepala Sekolah',
            'unread_notifications' => 3,
        ];

        $attendance = (object) [
            'academic_year' => 'TA 2024/2025 Genap',
            'percentage' => 96.8,
            'target' => 95,
            'present' => 1180,
            'permit' => 24,
            'absent' => 12,
        ];

        $authUser = auth()->user();
        $organizationId = session('active_organization_id')
            ?? $authUser?->active_organization_id
            ?? $authUser?->current_organization_id;

        $menuQuery = \Models\MenuCategory::query()
            ->active()
            ->whereNull('parent_id')
            ->orderBy('sort_order');

        if ($organizationId) {
            $menuQuery->forOrganization($organizationId);
        }

        // Filter untuk pengguna trial: hanya card kategori modul yang dipilih & sesuai role
        $trialResolver = new \App\Core\Menu\TrialMenuResolver();
        if ($authUser && $trialResolver->isTrialUser($authUser)) {
            $allowedCatCodes = $trialResolver->getAllowedCategoryCodes($authUser);
            if (!empty($allowedCatCodes)) {
                $menuQuery->whereIn('code', $allowedCatCodes);
            } else {
                $menuQuery->whereRaw('0 = 1');
            }
        }

        // Batasi kemunculan card menu secara proporsional (default 8 per halaman)
        $perPage = (int) $request->input('per_page', 10);
        $page = (int) $request->input('page', 1);
        $paginatedCategories = $menuQuery->paginate($perPage, ['*'], 'page', $page);

        $menus = $paginatedCategories->getCollection()->map(fn(\Models\MenuCategory $category) => (object) [
            'id' => $category->id,
            'title' => $category->name,
            'subtitle' => $category->description ?? '',
            'icon' => $category->icon ?? 'apps',
            'tone' => $category->color ?? 'blue',
            // Pakai route "{code}.index" kalau sudah ada, sementara '#'
            // Contoh: route 'keuangan.index', 'cbt.index', dst.
            'url' => Route::has("{$category->code}.index")
                ? route("{$category->code}.index")
                : (Route::has($category->code) ? route($category->code) : '#'),
        ]);

        $paginatedCategories->setCollection($menus);
        $menuPagination = $paginatedCategories;

        // Jika request via AJAX, kirimkan partial view dan data pagination dalam bentuk JSON
        if ($request->ajax() || $request->wantsJson() || $request->has('ajax_menus')) {
            $html = view('Dashboard::partials.menu_cards', ['menus' => $menus])->render();

            return response()->json([
                'status' => 'success',
                'html' => $html,
                'pagination' => [
                    'current_page' => $menuPagination->currentPage(),
                    'last_page' => $menuPagination->lastPage(),
                    'per_page' => $menuPagination->perPage(),
                    'total' => $menuPagination->total(),
                    'has_more' => $menuPagination->hasMorePages(),
                    'has_prev' => $menuPagination->currentPage() > 1,
                    'from' => $menuPagination->firstItem() ?? 0,
                    'to' => $menuPagination->lastItem() ?? 0,
                ],
            ]);
        }

        $agendas = collect([
            [
                'tone' => 'purple',
                'icon' => 'edit_calendar',
                'badge' => 'Ujian CBT',
                'time' => '08:00 - 10:30 WIB',
                'title' => 'Simulasi Matematika Peminatan XII MIPA',
                'description' => 'Laboratorium Multimedia 1 & 2 • 72 Peserta terdaftar',
            ],
            [
                'tone' => 'amber',
                'icon' => 'notification_important',
                'badge' => 'Batas Pembayaran',
                'time' => 'Sisa 3 Hari',
                'title' => 'Jatuh Tempo SPP Periode Maret 2025',
                'description' => '84 Siswa terdata belum menyelesaikan administrasi SPP.',
            ],
            [
                'tone' => 'sky',
                'icon' => 'commute',
                'badge' => 'Armada Siaga',
                'time' => 'Pukul 14:30 WIB',
                'title' => 'Rute Penjemputan Sore Siswa Aktif',
                'description' => '4 Unit Bus Sekolah siap di Shelter Gerbang Timur.',
            ],
        ])->map(fn(array $item) => (object) $item);

        $navItems = collect([
            ['label' => 'Beranda', 'icon' => 'dashboard', 'url' => '#', 'active' => true],
            ['label' => 'Akademik', 'icon' => 'school', 'url' => '#', 'active' => false],
            ['label' => 'Keuangan', 'icon' => 'account_balance_wallet', 'url' => '#', 'active' => false],
            ['label' => 'Pesan', 'icon' => 'chat', 'url' => '#', 'active' => false],
            ['label' => 'Profil', 'icon' => 'person', 'url' => '#', 'active' => false],
        ])->map(fn(array $item) => (object) $item);
        $today = $now->translatedFormat('l, d F Y');
        // return view('Dashboard::index', compact('app', 'school', 'user', 'greeting', 'today', 'attendance', 'menus', 'menuPagination', 'agendas', 'navItems'));
        if (!session()->has('active_menu_category_id')) {
            return view('Dashboard::index', compact('app', 'school', 'user', 'greeting', 'today', 'attendance', 'menus', 'menuPagination', 'agendas', 'navItems'));
        } else {
            return $this->selectCategoryResolver(session('active_menu_category_id'), $request);
        }
    }

    /**
     * Simpan menu_category_id yang dipilih ke session dan redirect/response.
     */
    public function selectCategory(Request $request, $id = null)
    {
        $categoryId = $id ?? $request->input('menu_category_id');

        if ($categoryId && $categoryId !== 'all' && (int) $categoryId > 0) {
            session(['active_menu_category_id' => (int) $categoryId]);
        } else {
            session()->forget('active_menu_category_id');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'active_menu_category_id' => session('active_menu_category_id'),
                'message' => 'Kategori menu berhasil disimpan.',
            ]);
        }

        $redirect = $request->input('redirect');
        if ($redirect && $redirect !== '#' && !str_starts_with($redirect, 'javascript:')) {
            return redirect($redirect);
        }

        return redirect()->back();
    }

    /**
     * Kembali ke beranda dashboard utama dan hapus kategori menu aktif dari session.
     */
    public function backToHome(Request $request)
    {
        if (session()->has('active_menu_category_id')) {
            session()->forget('active_menu_category_id');
        }

        return redirect('/dashboard');
    }

    private function selectCategoryResolver($categoryId, $request)
    {
        $category = \Models\MenuCategory::findOrFail($categoryId);
        $view = str_replace('-', '', \Illuminate\Support\Str::camel($category->code));
        return $this->{$view . "View"}($request);
    }

    private function financeView($request)
    {
        return view('Dashboard::dashboard.finance.index');
    }

    private function academicView($request)
    {
        return view('Dashboard::dashboard.academic.index');
    }

    private function absenceView($request)
    {
        return view('Dashboard::dashboard.absence.index');
    }

    private function accountingView($request)
    {
        return view('Dashboard::dashboard.accounting.index');
    }

    private function cbtView($request)
    {
        return view('Dashboard::dashboard.cbt.index');
    }

    private function transportView($request)
    {
        return view('Dashboard::dashboard.transport.index');
    }

    private function alumniView($request)
    {
        return view('Dashboard::dashboard.alumni.index');
    }

    private function settingsView($request)
    {
        return view('Dashboard::dashboard.settings.index');
    }

    private function assessmentView($request)
    {
        return view('Dashboard::dashboard.assessment.index');
    }

    private function budgetingView($request)
    {
        return view('Dashboard::dashboard.budgeting.index');
    }

    private function canteenView($request)
    {
        return view('Dashboard::dashboard.canteen.index');
    }

    private function employeeView($request)
    {
        return view('Dashboard::dashboard.employee.index');
    }

    private function inventoryView($request)
    {
        return view('Dashboard::dashboard.inventory.index');
    }

    private function libraryView($request)
    {
        return view('Dashboard::dashboard.library.index');
    }

    private function menuManagmentView($request)
    {
        return view('Dashboard::dashboard.menu-managment.index');
    }

    private function parentView($request)
    {
        return view('Dashboard::dashboard.parent.index');
    }

    private function ppdbView($request)
    {
        return view('Dashboard::dashboard.ppdb.index');
    }

    private function recruitmentView($request)
    {
        return view('Dashboard::dashboard.recruitment.index');
    }
    private function studentView($request)
    {
        return view('Dashboard::dashboard.student.index');
    }
    private function proctoringView($request)
    {
        return view('Dashboard::dashboard.proctoring.index');
    }

    private function shuttleServiceView($request)
    {
        return view('Dashboard::dashboard.shuttle-service.index');
    }
}
