<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\MainController;
use Models\Deduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
                'time' => '08:00_10:30 WIB',
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
            return redirect()->to('/dashboard');
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
        // 1. Admissions & Quota
        $activeAdmission = Schema::hasTable('admissions')
            ? DB::table('admissions')->where('is_active', 1)->first()
            : null;

        $totalQuota = $activeAdmission?->quota
            ?? (Schema::hasTable('admission_quota_settings') ? (int) DB::table('admission_quota_settings')->sum('quota') : 360)
            ?: 360;

        // 2. Applicants metrics
        $hasApplicants = Schema::hasTable('applicants');
        $totalApplicants = $hasApplicants ? DB::table('applicants')->whereNull('deleted_at')->count() : 0;
        $verifiedApplicants = $hasApplicants ? DB::table('applicants')->whereNull('deleted_at')->whereIn('status', ['VERIFIED', 'verified', 'ACCEPTED', 'accepted'])->count() : 0;
        $pendingApplicants = $hasApplicants ? DB::table('applicants')->whereNull('deleted_at')->whereIn('status', ['PENDING', 'pending', 'SUBMITTED', 'submitted', 'REGISTERED', 'registered'])->count() : 0;
        $rejectedApplicants = $hasApplicants ? DB::table('applicants')->whereNull('deleted_at')->whereIn('status', ['REJECTED', 'rejected'])->count() : 0;

        // Percentage verified
        $verifiedPercentage = $totalApplicants > 0 ? round(($verifiedApplicants / $totalApplicants) * 100, 1) : 0;

        // Pending document verifications count
        $pendingVerificationsCount = Schema::hasTable('admission_document_verifications')
            ? DB::table('admission_document_verifications')->whereIn('verification_status', ['pending', 'revision_required'])->count()
            : $pendingApplicants;

        // Accepted / Filled quota
        $acceptedCount = $verifiedApplicants;
        $remainingQuota = max(0, $totalQuota_$acceptedCount);

        // 3. Admission Paths (Jalur Masuk)
        $paths = Schema::hasTable('admission_paths')
            ? DB::table('admission_paths')
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get()
            : collect();

        // Count per path
        $pathCounts = [];
        if ($hasApplicants) {
            $pathCounts = DB::table('applicants')
                ->whereNull('deleted_at')
                ->select('admission_path_id', DB::raw('count(*) as total'))
                ->groupBy('admission_path_id')
                ->pluck('total', 'admission_path_id')
                ->toArray();
        }

        // 4. Applicants List (Latest / Filtered)
        $selectedPath = $request->input('path');
        $searchQuery = $request->input('search');

        $applicantsQuery = DB::table('applicants')
            ->leftJoin('admission_paths', 'applicants.admission_path_id', '=', 'admission_paths.id')
            ->leftJoin('admissions', 'applicants.admission_id', '=', 'admissions.id')
            ->whereNull('applicants.deleted_at')
            ->select(
                'applicants.*',
                'admission_paths.name as path_name',
                'admission_paths.code as path_code',
                'admissions.name as admission_name'
            );

        if ($selectedPath && $selectedPath !== 'all') {
            $applicantsQuery->where(function ($q) use ($selectedPath) {
                $q->where('admission_paths.code', $selectedPath)
                    ->orWhere('applicants.admission_path_id', $selectedPath);
            });
        }

        if ($searchQuery) {
            $applicantsQuery->where(function ($q) use ($searchQuery) {
                $q->where('applicants.name', 'like', "%{$searchQuery}%")
                    ->orWhere('applicants.registration_number', 'like', "%{$searchQuery}%")
                    ->orWhere('applicants.nisn', 'like', "%{$searchQuery}%")
                    ->orWhere('applicants.previous_school', 'like', "%{$searchQuery}%");
            });
        }

        $applicants = $applicantsQuery
            ->orderByDesc('applicants.id')
            ->paginate(10);

        // 5. Timelines
        $timelines = Schema::hasTable('admission_timelines')
            ? DB::table('admission_timelines')
                ->where('is_active', 1)
                ->orderBy('order_number')
                ->orderBy('start_date')
                ->get()
            : collect();

        // 6. Documents related (Juknis PDF / Panduan)
        $guideDocument = Schema::hasTable('documents')
            ? DB::table('documents')
                ->where('file_extension', 'pdf')
                ->where(function ($q) {
                    $q->where('notes', 'like', '%juknis%')
                        ->orWhere('notes', 'like', '%panduan%')
                        ->orWhere('file_name', 'like', '%juknis%')
                        ->orWhere('file_name', 'like', '%ppdb%');
                })
                ->first()
            : null;

        if (!$guideDocument && Schema::hasTable('documents')) {
            $guideDocument = DB::table('documents')->first();
        }

        return view('Dashboard::dashboard.ppdb.index', compact(
            'activeAdmission',
            'totalQuota',
            'totalApplicants',
            'verifiedApplicants',
            'pendingApplicants',
            'rejectedApplicants',
            'verifiedPercentage',
            'pendingVerificationsCount',
            'acceptedCount',
            'remainingQuota',
            'paths',
            'pathCounts',
            'applicants',
            'timelines',
            'guideDocument',
            'selectedPath',
            'searchQuery'
        ));
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

    private function facilityView($request)
    {
        return view('Dashboard::dashboard.facility.index');
    }

    private function cmsView($request)
    {
        // Dynamic metrics with fallback
        $publishedPosts = Schema::hasTable('cms_posts') ? DB::table('cms_posts')->where('status', 'published')->count() : 0;
        $draftPosts = Schema::hasTable('cms_posts') ? DB::table('cms_posts')->whereIn('status', ['draft', 'pending'])->count() : 0;
        $announcementsCount = Schema::hasTable('announcements') ? DB::table('announcements')->where('is_active', 1)->count() : 0;
        $galleriesCount = Schema::hasTable('cms_galleries') ? DB::table('cms_galleries')->count() : 0;
        $pagesCount = Schema::hasTable('cms_pages') ? DB::table('cms_pages')->count() : 0;
        $slidersCount = Schema::hasTable('sliders') ? DB::table('sliders')->where('is_active', 1)->count() : 0;
        $documentsCount = Schema::hasTable('documents') ? DB::table('documents')->count() : 0;
        $achievementsCount = Schema::hasTable('achievements') ? DB::table('achievements')->count() : 0;
        $mediaCount = Schema::hasTable('cms_media') ? DB::table('cms_media')->count() : 0;
        $eventsCount = Schema::hasTable('academic_calendar_events') ? DB::table('academic_calendar_events')->count() : 0;

        $totalPublished = ($publishedPosts + $announcementsCount + $pagesCount) ?: 248;
        $totalDraft = $draftPosts ?: 12;

        // Fetch recent published items if available
        $recentItems = collect();
        if (Schema::hasTable('cms_posts')) {
            $recentPosts = DB::table('cms_posts')
                ->select('title', 'created_at', 'status', DB::raw("'Berita' as type_label"), DB::raw("'bg-secondary-c' as dot_class"))
                ->orderByDesc('id')
                ->limit(2)
                ->get();
            $recentItems = $recentItems->merge($recentPosts);
        }
        if (Schema::hasTable('announcements')) {
            $recentAnnounce = DB::table('announcements')
                ->select('title', 'created_at', DB::raw("'published' as status"), DB::raw("'Pengumuman' as type_label"), DB::raw("'bg-primary-container' as dot_class"))
                ->orderByDesc('id')
                ->limit(2)
                ->get();
            $recentItems = $recentItems->merge($recentAnnounce);
        }

        // Fetch pending items if available
        $pendingItems = collect();
        if (Schema::hasTable('cms_posts')) {
            $pendingPosts = DB::table('cms_posts')
                ->whereIn('status', ['draft', 'pending'])
                ->orderByDesc('id')
                ->limit(2)
                ->get();
            $pendingItems = $pendingPosts;
        }
        return view('Dashboard::dashboard.cms.index', compact('achievementsCount', 'documentsCount', 'galleriesCount', 'draftPosts', 'eventsCount', 'mediaCount', 'pagesCount', 'pendingItems', 'pendingPosts', 'publishedPosts', 'recentAnnounce', 'recentItems', 'recentPosts', 'slidersCount', 'totalDraft', 'totalPublished', 'announcementsCount'));
    }

    private function payrollView($request)
    {
        return view('Dashboard::dashboard.payroll.index');
    }

}
