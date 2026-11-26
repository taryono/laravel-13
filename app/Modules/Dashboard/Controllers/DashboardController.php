<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\MainController;
use App\Http\Resources\BosDashboardResource;
use App\Services\Finance\BosDashboardService;
use Models\Deduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Models\BosPeriod;

class DashboardController extends MainController
{
    public function __construct(protected BosDashboardService $bosDashboardService)
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

        $authUser = request()->user();
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
        if (method_exists($this, $view . "View")) {
            return $this->{$view . "View"}($request);
        }
        return redirect()->to('/dashboard/default-view')->with('error', 'View not found');
    }

    private function financeView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.finance.index', []);
    }

    private function academicView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.academic.index', []);
    }

    private function absenceView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.absence.index', []);
    }

    private function accountingView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.accounting.index', []);
    }

    private function cbtView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.cbt.index', []);
    }

    private function transportView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.transport.index', []);
    }

    private function alumniView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.alumni.index', []);
    }

    private function settingsView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.settings.index', []);
    }

    private function assessmentView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.assessment.index', []);
    }

    private function budgetingView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.budgeting.index', []);
    }

    private function canteenView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.canteen.index', []);
    }

    private function employeeView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.employee.index', []);
    }

    private function inventoryView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.inventory.index', []);
    }

    private function libraryView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.library.index', []);
    }

    private function menuManagmentView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.menu-managment.index', []);
    }

    private function parentView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.parent.index', []);
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
        $remainingQuota = max(0, $totalQuota - $acceptedCount);

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
        return $this->viewResolver('Dashboard::dashboard.recruitment.index', []);
    }
    private function studentView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.student.index', []);
    }
    private function proctoringView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.proctoring.index', []);
    }

    private function shuttleServiceView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.shuttle-service.index', []);
    }

    private function facilityView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.facility.index', []);
    }

    public function cmsDirectView(Request $request)
    {
        $cmsCategory = Schema::hasTable('menu_categories')
            ? DB::table('menu_categories')->where('code', 'cms')->first()
            : null;

        if ($cmsCategory) {
            session(['active_menu_category_id' => $cmsCategory->id]);
        }

        return $this->cmsView($request);
    }

    private function cmsView($request)
    {
        // 1. Konten Terbit / Draft (cms_posts & cms_pages)
        $publishedPosts = Schema::hasTable('cms_posts')
            ? DB::table('cms_posts')->where('status', 'published')->whereNull('deleted_at')->count()
            : 0;
        $publishedPages = Schema::hasTable('cms_pages')
            ? DB::table('cms_pages')->where('status', 'published')->whereNull('deleted_at')->count()
            : 0;
        $totalPublished = $publishedPosts + $publishedPages;

        $draftPosts = Schema::hasTable('cms_posts')
            ? DB::table('cms_posts')->whereIn('status', ['draft', 'pending', 'pending_review'])->whereNull('deleted_at')->count()
            : 0;
        $draftPages = Schema::hasTable('cms_pages')
            ? DB::table('cms_pages')->whereIn('status', ['draft', 'pending_review'])->whereNull('deleted_at')->count()
            : 0;
        $totalDraft = $draftPosts + $draftPages;

        $pendingReviewCount = Schema::hasTable('cms_posts')
            ? DB::table('cms_posts')->whereIn('status', ['pending', 'pending_review'])->whereNull('deleted_at')->count()
            : 0;

        // 2. Pengunjung Bulan Ini (cms_visitor_stats)
        $now = Carbon::now();
        $currentMonthVisitors = Schema::hasTable('cms_visitor_stats')
            ? (int) DB::table('cms_visitor_stats')->whereYear('date', $now->year)->whereMonth('date', $now->month)->sum('page_views')
            : 0;
        $lastMonthVisitors = Schema::hasTable('cms_visitor_stats')
            ? (int) DB::table('cms_visitor_stats')->whereYear('date', $now->copy()->subMonth()->year)->whereMonth('date', $now->copy()->subMonth()->month)->sum('page_views')
            : 0;

        if ($currentMonthVisitors === 0 && Schema::hasTable('cms_posts')) {
            $currentMonthVisitors = (int) DB::table('cms_posts')->sum('view_count');
        }

        $visitorGrowthPercent = $lastMonthVisitors > 0
            ? round((($currentMonthVisitors - $lastMonthVisitors) / $lastMonthVisitors) * 100)
            : 12;

        // 3. Penyimpanan Media (cms_media)
        $mediaCount = Schema::hasTable('cms_media')
            ? DB::table('cms_media')->whereNull('deleted_at')->count()
            : 0;
        $mediaTotalBytes = Schema::hasTable('cms_media')
            ? (int) DB::table('cms_media')->whereNull('deleted_at')->sum('size')
            : 0;
        $mediaQuotaBytes = 10 * 1024 * 1024 * 1024; // 10 GB
        $mediaPercentage = $mediaQuotaBytes > 0 ? round(($mediaTotalBytes / $mediaQuotaBytes) * 100, 1) : 0;

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($mediaTotalBytes, 0);
        $pow = $bytes > 0 ? floor(log($bytes) / log(1024)) : 0;
        $pow = min($pow, count($units) - 1);
        $mediaFormattedSize = round($bytes / (1024 ** $pow), 1) . ' ' . $units[$pow];

        // 4. Modul Konten counts
        $announcementsCount = Schema::hasTable('cms_posts')
            ? DB::table('cms_posts')
                ->where('status', 'published')
                ->whereNull('deleted_at')
                ->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('cms_categories')
                        ->whereColumn('cms_categories.id', 'cms_posts.cms_category_id')
                        ->where('cms_categories.slug', 'pengumuman');
                })->count()
            : 0;
        if ($announcementsCount === 0 && Schema::hasTable('announcements')) {
            $announcementsCount = DB::table('announcements')->where('is_active', 1)->count();
        }

        $galleriesCount = Schema::hasTable('cms_galleries')
            ? DB::table('cms_galleries')->where('is_active', 1)->whereNull('deleted_at')->count()
            : 0;

        $eventsCount = Schema::hasTable('academic_calendar_events')
            ? DB::table('academic_calendar_events')->whereNull('deleted_at')->count()
            : 0;

        $pagesCount = Schema::hasTable('cms_pages')
            ? DB::table('cms_pages')->where('status', 'published')->whereNull('deleted_at')->count()
            : 0;

        $bannersCount = Schema::hasTable('cms_banners')
            ? DB::table('cms_banners')->where('is_active', 1)->whereNull('deleted_at')->count()
            : 0;
        if ($bannersCount === 0 && Schema::hasTable('sliders')) {
            $bannersCount = DB::table('sliders')->where('is_active', 1)->count();
        }

        $documentsCount = Schema::hasTable('cms_media')
            ? DB::table('cms_media')->whereNotNull('document_category')->whereNull('deleted_at')->count()
            : 0;
        if ($documentsCount === 0 && Schema::hasTable('documents')) {
            $documentsCount = DB::table('documents')->whereNull('deleted_at')->count();
        }

        $achievementsCount = Schema::hasTable('achievements')
            ? DB::table('achievements')->whereNull('deleted_at')->count()
            : 0;
        if (Schema::hasTable('cms_testimonials')) {
            $achievementsCount += DB::table('cms_testimonials')->where('is_active', 1)->count();
        }

        // 5. Status Website Online (cms_settings)
        $siteStatus = 'Online';
        if (Schema::hasTable('cms_settings')) {
            $statusSetting = DB::table('cms_settings')
                ->whereIn('key', ['site_status', 'maintenance_mode', 'website_status'])
                ->whereNull('deleted_at')
                ->get()
                ->keyBy('key');

            if (isset($statusSetting['maintenance_mode']) && in_array($statusSetting['maintenance_mode']->value, ['1', 'true', 1])) {
                $siteStatus = 'Maintenance';
            } elseif (isset($statusSetting['site_status']) && !empty($statusSetting['site_status']->value)) {
                $siteStatus = ucfirst($statusSetting['site_status']->value);
            } elseif (isset($statusSetting['website_status']) && !empty($statusSetting['website_status']->value)) {
                $siteStatus = ucfirst($statusSetting['website_status']->value);
            }
        }

        // 6. Konten Terbaru (cms_posts)
        $recentItems = collect();
        if (Schema::hasTable('cms_posts')) {
            $recentItems = DB::table('cms_posts')
                ->leftJoin('cms_categories', 'cms_posts.cms_category_id', '=', 'cms_categories.id')
                ->select(
                    'cms_posts.id',
                    'cms_posts.title',
                    'cms_posts.slug',
                    'cms_posts.status',
                    'cms_posts.view_count',
                    'cms_posts.published_at',
                    'cms_posts.created_at',
                    'cms_categories.name as category_name',
                    'cms_categories.slug as category_slug'
                )
                ->where('cms_posts.status', 'published')
                ->whereNull('cms_posts.deleted_at')
                ->orderByDesc('cms_posts.published_at')
                ->orderByDesc('cms_posts.id')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $cat = strtolower($item->category_name ?? '');
                    if (str_contains($cat, 'pengumuman')) {
                        $item->dot_class = 'bg-primary-container';
                        $item->type_label = 'Pengumuman';
                    } elseif (str_contains($cat, 'prestasi')) {
                        $item->dot_class = 'bg-gold';
                        $item->type_label = 'Prestasi';
                    } elseif (str_contains($cat, 'kegiatan')) {
                        $item->dot_class = 'bg-on-primary-fixed-variant';
                        $item->type_label = 'Galeri & Kegiatan';
                    } else {
                        $item->dot_class = 'bg-secondary-c';
                        $item->type_label = $item->category_name ?: 'Berita';
                    }
                    $views = (int) ($item->view_count ?? 0);
                    $item->views_formatted = $views > 0 ? number_format($views, 0, ',', '.') . ' dilihat' : 'Aktif';
                    return $item;
                });
        }

        // 7. Menunggu Review (cms_posts dengan status draft/pending)
        $pendingItems = collect();
        if (Schema::hasTable('cms_posts')) {
            $pendingItems = DB::table('cms_posts')
                ->leftJoin('cms_categories', 'cms_posts.cms_category_id', '=', 'cms_categories.id')
                ->leftJoin('users', 'cms_posts.user_id', '=', 'users.id')
                ->select(
                    'cms_posts.id',
                    'cms_posts.title',
                    'cms_posts.slug',
                    'cms_posts.excerpt',
                    'cms_posts.content',
                    'cms_posts.status',
                    'cms_posts.created_at',
                    'cms_categories.name as category_name',
                    'users.name as author_name'
                )
                ->whereIn('cms_posts.status', ['pending_review', 'pending', 'draft'])
                ->whereNull('cms_posts.deleted_at')
                ->orderByRaw("CASE WHEN cms_posts.status IN ('pending_review', 'pending') THEN 1 ELSE 2 END")
                ->orderByDesc('cms_posts.id')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $item->category_label = $item->category_name ?: 'Berita / Artikel';
                    $item->author_name = $item->author_name ?: 'Admin / Staf';
                    $item->snippet = !empty($item->excerpt) ? $item->excerpt : Str::limit(strip_tags($item->content ?? ''), 75);
                    return $item;
                });
        }

        return view('Dashboard::dashboard.cms.index', compact(
            'totalPublished',
            'publishedPosts',
            'publishedPages',
            'totalDraft',
            'draftPosts',
            'draftPages',
            'pendingReviewCount',
            'currentMonthVisitors',
            'lastMonthVisitors',
            'visitorGrowthPercent',
            'mediaCount',
            'mediaTotalBytes',
            'mediaFormattedSize',
            'mediaPercentage',
            'announcementsCount',
            'galleriesCount',
            'eventsCount',
            'pagesCount',
            'bannersCount',
            'documentsCount',
            'achievementsCount',
            'siteStatus',
            'recentItems',
            'pendingItems'
        ));
    }

    private function payrollView($request)
    {
        return $this->viewResolver('Dashboard::dashboard.payroll.index', []);
    }

    private function bosView(Request $request)
    {
        $schoolId = $request->user()->school_id ?? session('active_school_id');

        // 1. Ambil daftar periode BOS sekolah untuk dropdown filter periode di header
        $periods = BosPeriod::where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderByDesc('academic_year_id')
            ->orderByDesc('period_number')
            ->get();

        // 2. Tentukan periode aktif (dari query string ?period_id=... atau periode terbaru)
        $activePeriodId = $request->integer('period_id') ?: $periods->first()?->id;

        // Jika sekolah belum memiliki periode BOS sama sekali, kirim state kosong ke view
        if (!$activePeriodId) {
            return $this->viewResolver('Dashboard::dashboard.bos.index', [
                'periods' => $periods,
                'dashboard' => null,
            ]);
        }

        // 3. Ambil data mentah dari BosDashboardService
        $rawDashboardData = $this->bosDashboardService->getDashboardData(
            bosPeriodId: $activePeriodId,
            filters: $request->only(['category', 'search', 'per_page', 'page'])
        );

        // 4. Transformasi via BosDashboardResource (otomatis membungkus BosTransactionResource)
        // Gunakan ->resolve($request) agar menjadi array PHP bersih untuk Blade
        $dashboard = (new BosDashboardResource($rawDashboardData))->resolve($request);

        // Jika request berasal dari AJAX/Fetch (misal saat klik tab filter atau pagination tabel)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($dashboard);
        }

        return $this->viewResolver('Dashboard::dashboard.bos.index', [
            'periods' => $periods,
            'dashboard' => $dashboard,
        ]);
    }

    private function viewResolver($viewName, $data = [])
    {
        if (view()->exists($viewName)) {
            return view($viewName, $data);
        }
    }

    public function defaultView(Request $request)
    {
        return view('Dashboard::blank', []);
    }
}
