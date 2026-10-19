<?php

namespace App\Modules\ReportBuilder\Controllers;

use App\Http\Controllers\MainController;
use Models\ReportTemplate;
use Models\ReportBuilderComponent;
use Models\ReportTemplateCurriculum;
use Models\ReportTemplateVersion;
use Models\ReportTemplateSignatory;
use Models\TemplateAsset;
use Models\Curriculum;
use Models\AcademicYear;
use Models\ReportDataSource;
use Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;
use App\Services\Report\ReportPdfService;
use App\Services\ReportCompilerService;
use App\Services\ReportRenderService;
use Illuminate\Support\Facades\Auth;

class ReportBuilderController extends MainController
{
    public function __construct(
        protected ReportCompilerService $compiler,
        protected ReportRenderService $renderer,
        protected ReportPdfService $pdfService
    ) {
        parent::__construct(new ReportTemplate());
    }

    /**
     * Display a listing of report templates.
     */
    public function index(Request $request): View
    {
        $query = ReportTemplate::with(['creator', 'curriculum', 'academicYear', 'curriculums', 'signatories'])
            ->withCount(['versions', 'assets', 'signatories']);

        // Search by name or description
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($type = $request->input('type')) {
            if ($type !== 'all') {
                $query->where('type', $type);
            }
        }

        // Filter by status
        if ($request->has('status') && $request->input('status') !== null && $request->input('status') !== 'all') {
            $isActive = in_array($request->input('status'), ['1', 'active', 'true'], true);
            $query->where('is_active', $isActive);
        }

        // Filter by paper size
        if ($paperSize = $request->input('paper_size')) {
            if ($paperSize !== 'all') {
                $query->where('paper_size', $paperSize);
            }
        }

        $templates = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total' => ReportTemplate::count(),
            'active' => ReportTemplate::where('is_active', true)->count(),
            'inactive' => ReportTemplate::where('is_active', false)->count(),
            'default' => ReportTemplate::where('is_default', true)->count(),
            'types' => [
                'academic' => ReportTemplate::where('type', 'academic')->count(),
                'religious' => ReportTemplate::where('type', 'religious')->count(),
                'memorization' => ReportTemplate::where('type', 'memorization')->count(),
                'pesantren' => ReportTemplate::where('type', 'pesantren')->count(),
                'combined' => ReportTemplate::where('type', 'combined')->count(),
            ],
            'paper_sizes' => ReportTemplate::select('paper_size')->distinct()->pluck('paper_size')->toArray(),
        ];

        return view('ReportBuilder::index', compact('templates', 'stats'));
    }

    /**
     * Visual drag-and-drop studio builder page.
     */
    public function builder($template = null, ?Request $request = null): View
    {
        if ($template && !($template instanceof ReportTemplate)) {
            $template = ReportTemplate::with(['curriculums', 'signatories', 'assets'])->find($template);
        } elseif ($template instanceof ReportTemplate) {
            $template->loadMissing(['curriculums', 'signatories', 'assets']);
        }

        $configuration = $template?->configuration ?? $this->defaultConfiguration();
        $currentCurriculumId = $template?->curriculum_id ?? $template?->curriculums()->value('curriculum_id') ?? '';
        $currentAcademicYearId = $template?->academic_year_id ?? $template?->curriculums()->value('academic_year_id') ?? '';

        $curriculums = Curriculum::active()->orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $bindings = config('report.available_bindings', []);
        $assets = $template ? $template->assets : TemplateAsset::latest()->take(30)->get();
        $paperSizes = config('report.paper_sizes', []);
        $paletteComponents = ReportBuilderComponent::active()->orderBy('order')->get();
        $dataSources = ReportDataSource::active()->orderBy('order')->orderBy('name')->get();

        return view('ReportBuilder::builder', compact(
            'template',
            'configuration',
            'currentCurriculumId',
            'currentAcademicYearId',
            'curriculums',
            'academicYears',
            'bindings',
            'assets',
            'paperSizes',
            'paletteComponents',
            'dataSources'
        ));
    }

    /**
     * Store or update a report template.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'nullable|exists:report_templates,id',
            'name' => 'required|string|max:100',
            'type' => 'required|in:academic,religious,memorization,pesantren,combined',
            'curriculum_id' => 'nullable|exists:curriculums,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'paper_size' => 'required|in:A3,A4,A5,B5,F4,Letter,Legal,Executive,Custom',
            'custom_paper_width' => 'nullable|numeric|min:50|max:1000|required_if:paper_size,Custom',
            'custom_paper_height' => 'nullable|numeric|min:50|max:1000|required_if:paper_size,Custom',
            'orientation' => 'required|in:portrait,landscape',
            'margin_top' => 'required|numeric|min:0|max:10',
            'margin_bottom' => 'required|numeric|min:0|max:10',
            'margin_left' => 'required|numeric|min:0|max:10',
            'margin_right' => 'required|numeric|min:0|max:10',
            'configuration' => 'required|array',
        ]);

        $config = $data['configuration'];

        // Sinkronisasi page config
        if (!isset($config['page']) || !is_array($config['page'])) {
            $config['page'] = [];
        }

        $config['page']['size'] = $data['paper_size'];
        $config['page']['orientation'] = $data['orientation'];
        $config['page']['margin'] = [
            'top' => (float) $data['margin_top'],
            'right' => (float) $data['margin_right'],
            'bottom' => (float) $data['margin_bottom'],
            'left' => (float) $data['margin_left'],
        ];

        if ($data['paper_size'] === 'Custom') {
            $config['page']['width'] = (float) $data['custom_paper_width'];
            $config['page']['height'] = (float) $data['custom_paper_height'];
        } else {
            $preset = config("report.paper_sizes.{$data['paper_size']}");
            if ($preset && isset($preset['width'], $preset['height'])) {
                $config['page']['width'] = (float) $preset['width'];
                $config['page']['height'] = (float) $preset['height'];
            } else {
                unset($config['page']['width'], $config['page']['height']);
            }
        }

        $data['configuration'] = $config;

        try {
            $template = DB::transaction(function () use ($data, $config) {
                if (!empty($data['id'])) {
                    $template = ReportTemplate::findOrFail($data['id']);
                    $template->update(array_merge($data, [
                        'updated_by' => Auth::id(),
                    ]));
                } else {
                    $template = ReportTemplate::create(array_merge($data, [
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]));
                }

                // Sync / simpan relasi ke tabel report_template_curriculums
                if (!empty($data['curriculum_id'])) {
                    ReportTemplateCurriculum::updateOrCreate(
                        ['report_template_id' => $template->id],
                        [
                            'curriculum_id'    => $data['curriculum_id'],
                            'academic_year_id' => $data['academic_year_id'] ?: null,
                            'is_active'        => true,
                            'created_by'         => Auth::id(),
                            'updated_by'       => Auth::id(),
                        ]
                    );
                } else {
                    ReportTemplateCurriculum::where('report_template_id', $template->id)->delete();
                }

                // Compile template HTML dan CSS
                $compiled = $this->compiler->compile($config, $template);
                $template->update([
                    'compiled_html' => $compiled['html'],
                    'compiled_css' => $compiled['css'],
                ]);

                // Simpan riwayat versi template
                $lastVersion = $template->versions()->max('version') ?? 0;
                ReportTemplateVersion::create([
                    'template_id' => $template->id,
                    'version' => $lastVersion + 1,
                    'configuration' => $config,
                    'created_by' => Auth::id(),
                ]);

                // Sinkronisasi tanda tangan
                $this->syncSignatories($template, $config);

                return $template;
            });

            if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Template laporan berhasil disimpan.',
                    'template_id' => $template->id,
                    'template' => $template,
                ]);
            }

            return redirect()->route('admin.report-builder.builder', $template->id)
                ->with('success', 'Template laporan berhasil disimpan.');
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan ReportTemplate.', [
                'exception' => $e,
                'data' => $data,
                'user_id' => Auth::id(),
            ]);

            if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan template: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal menyimpan template: ' . $e->getMessage());
        }
    }

    /**
     * Preview template rendering dynamically.
     */
    public function preview(Request $request)
    {
        $config = $request->input('configuration');
        if (is_string($config)) {
            $config = json_decode($config, true) ?? [];
        }

        if (!isset($config['page']) || !is_array($config['page'])) {
            $config['page'] = [];
        }

        $paperSize = $request->input('paper_size', 'A4');
        $orientation = $request->input('orientation', 'portrait');
        $customWidth = $request->input('custom_paper_width');
        $customHeight = $request->input('custom_paper_height');
        $marginTop = (float) $request->input('margin_top', 2);
        $marginRight = (float) $request->input('margin_right', 2);
        $marginBottom = (float) $request->input('margin_bottom', 2);
        $marginLeft = (float) $request->input('margin_left', 2.5);

        $config['page']['size'] = $paperSize;
        $config['page']['orientation'] = $orientation;
        $config['page']['margin'] = [
            'top' => $marginTop,
            'right' => $marginRight,
            'bottom' => $marginBottom,
            'left' => $marginLeft,
        ];

        if ($paperSize === 'Custom') {
            $config['page']['width'] = (float) $customWidth;
            $config['page']['height'] = (float) $customHeight;
        } else {
            $preset = config("report.paper_sizes.{$paperSize}");
            if ($preset && isset($preset['width'], $preset['height'])) {
                $config['page']['width'] = (float) $preset['width'];
                $config['page']['height'] = (float) $preset['height'];
            } else {
                unset($config['page']['width'], $config['page']['height']);
            }
        }

        $template = new ReportTemplate([
            'configuration' => $config,
            'paper_size' => $paperSize,
            'custom_paper_width' => $customWidth,
            'custom_paper_height' => $customHeight,
            'orientation' => $orientation,
            'margin_top' => $marginTop,
            'margin_bottom' => $marginBottom,
            'margin_left' => $marginLeft,
            'margin_right' => $marginRight,
        ]);

        $compiled = $this->compiler->compile($config, $template);

        $studentId = $request->input('student_id', config('report.preview_dummy_student_id', 1));
        $student = null;
        if (class_exists(Student::class)) {
            try {
                $student = Student::with(['activeClassroom.homeroomTeacher', 'dormitory'])->find($studentId);
            } catch (Throwable $e) {
                $student = null;
            }
        }

        if ($student) {
            $academicYearId = $request->input(
                'academic_year_id',
                AcademicYear::where('is_active', true)->value('id') ?? 1
            );

            $data = $this->renderer->buildData($student, $academicYearId, $template);
            $html = $this->renderer->bindData($compiled['full'], $data);
        } else {
            $dummyData = $this->dummyData();
            $html = $this->renderer->bindData($compiled['full'], $dummyData);
        }

        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Upload an asset for report builder.
     */
    public function uploadAsset(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'type' => 'required|in:logo,signature,stamp,ornament,background,watermark',
        ]);

        $path = $request->file('file')->store(config('report.asset_path', 'report-assets'), config('report.asset_disk', 'public'));

        $asset = TemplateAsset::create([
            'template_id' => $request->input('template_id'),
            'name' => $request->file('file')->getClientOriginalName(),
            'type' => $request->input('type'),
            'file_path' => $path,
            'mime_type' => $request->file('file')->getMimeType(),
            'file_size' => $request->file('file')->getSize(),
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'asset' => [
                'id' => $asset->id,
                'name' => $asset->name,
                'url' => $asset->url,
                'type' => $asset->type,
            ],
        ]);
    }

    /**
     * Duplicate a report template.
     */
    public function duplicate($template): RedirectResponse
    {
        if (!($template instanceof ReportTemplate)) {
            $template = ReportTemplate::findOrFail($template);
        }

        $new = $template->replicate();
        $new->name = $template->name . ' (Salinan)';
        $new->is_default = false;
        $new->created_by = Auth::id();
        $new->updated_by = Auth::id();
        $new->save();

        if ($template->curriculums()->exists()) {
            foreach ($template->curriculums as $c) {
                $newCurriculum = $c->replicate();
                $newCurriculum->report_template_id = $new->id;
                $newCurriculum->save();
            }
        }

        if ($template->signatories()->exists()) {
            foreach ($template->signatories as $sig) {
                $newSig = $sig->replicate();
                $newSig->report_template_id = $new->id;
                $newSig->save();
            }
        }

        if ($template->versions()->exists()) {
            $lastVer = $template->versions()->latest('version')->first();
            if ($lastVer) {
                $newVer = $lastVer->replicate();
                $newVer->template_id = $new->id;
                $newVer->version = 1;
                $newVer->save();
            }
        }

        return redirect()->route('admin.report-builder.builder', $new->id)
            ->with('success', "Template '{$new->name}' berhasil diduplikasi.");
    }

    /**
     * Delete a report template.
     */
    public function destroy(Request $request, $template)
    {
        if (!($template instanceof ReportTemplate)) {
            $template = ReportTemplate::findOrFail($template);
        }

        $templateName = $template->name;
        $template->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Template '{$templateName}' berhasil dihapus.",
            ]);
        }

        return redirect()->route('admin.report-builder.index')
            ->with('success', "Template '{$templateName}' berhasil dihapus.");
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(Request $request, $template)
    {
        if (!($template instanceof ReportTemplate)) {
            $template = ReportTemplate::findOrFail($template);
        }

        $template->update(['is_active' => !$template->is_active]);
        $status = $template->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $template->is_active,
                'message' => "Template '{$template->name}' berhasil {$status}.",
            ]);
        }

        return back()->with('success', "Template '{$template->name}' berhasil {$status}.");
    }

    /**
     * Set a template as default.
     */
    public function setDefault($template): RedirectResponse
    {
        if (!($template instanceof ReportTemplate)) {
            $template = ReportTemplate::findOrFail($template);
        }

        ReportTemplate::where('id', '!=', $template->id)->update(['is_default' => false]);
        $template->update(['is_default' => true, 'is_active' => true]);

        return back()->with('success', "Template '{$template->name}' berhasil ditetapkan sebagai template bawaan.");
    }

    /**
     * Preview compiled template HTML.
     */
    public function previewTemplate($template, Request $request)
    {
        if (!($template instanceof ReportTemplate)) {
            $template = ReportTemplate::findOrFail($template);
        }

        $config = $template->configuration ?? $this->defaultConfiguration();
        $compiled = $this->compiler->compile($config, $template);

        $studentId = $request->input('student_id');
        $student = null;
        if (class_exists(Student::class)) {
            try {
                $student = $studentId
                    ? Student::with(['activeClassroom.homeroomTeacher', 'dormitory'])->find($studentId)
                    : Student::with(['activeClassroom.homeroomTeacher', 'dormitory'])->first();
            } catch (Throwable $e) {
                $student = null;
            }
        }

        if ($student) {
            $academicYearId = $request->input(
                'academic_year_id',
                AcademicYear::where('is_active', true)->value('id') ?? 1
            );
            $data = $this->renderer->buildData($student, $academicYearId, $template);
            $html = $this->renderer->bindData($compiled['full'], $data);
        } else {
            $html = $this->renderer->bindData($compiled['full'], $this->dummyData());
        }

        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Preview direct PDF streaming using ReportPdfService.
     */
    public function previewPdf($template, Request $request)
    {
        if (!($template instanceof ReportTemplate)) {
            $template = ReportTemplate::findOrFail($template);
        }

        $config = $template->configuration ?? $this->defaultConfiguration();
        $compiled = $this->compiler->compile($config, $template);

        $studentId = $request->input('student_id');
        $student = null;
        if (class_exists(Student::class)) {
            try {
                $student = $studentId
                    ? Student::with(['activeClassroom.homeroomTeacher', 'dormitory'])->find($studentId)
                    : Student::with(['activeClassroom.homeroomTeacher', 'dormitory'])->first();
            } catch (Throwable $e) {
                $student = null;
            }
        }

        if ($student) {
            $academicYearId = $request->input(
                'academic_year_id',
                AcademicYear::where('is_active', true)->value('id') ?? 1
            );
            $data = $this->renderer->buildData($student, $academicYearId, $template);
            $html = $this->renderer->bindData($compiled['full'], $data);
        } else {
            $html = $this->renderer->bindData($compiled['full'], $this->dummyData());
        }

        $pageConfig = $config['page'] ?? [];
        $pdf = $this->pdfService->make($html, $pageConfig);

        return $pdf->stream('preview-' . Str::slug($template->name) . '.pdf');
    }

    protected function syncSignatories(ReportTemplate $template, array $config): void
    {
        $signatures = collect($config['sections'] ?? [])
            ->where('type', 'signature')
            ->flatMap(fn($s) => $s['columns'] ?? [])
            ->all();

        $template->signatories()->delete();
        foreach ($signatures as $sig) {
            $template->signatories()->create([
                'role' => $sig['role'] ?? 'headmaster',
                'label' => $sig['label'] ?? null,
                'data_source' => $sig['source'] ?? 'automatic',
                'teacher_id' => $sig['teacher_id'] ?? null,
                'manual_name' => $sig['manual_name'] ?? null,
                'manual_employee_number' => $sig['manual_employee_number'] ?? null,
                'signature_image_id' => $sig['signature_image_id'] ?? null,
            ]);
        }
    }

    protected function defaultConfiguration(): array
    {
        return [
            'version' => '1.0',
            'page' => [
                'size' => 'A4',
                'orientation' => 'portrait',
                'margin' => ['top' => 2, 'right' => 2, 'bottom' => 2, 'left' => 2.5],
                'background' => '#ffffff',
            ],
            'globalStyle' => [
                'fontFamily' => 'Times New Roman',
                'fontSize' => 12,
                'color' => '#000000',
                'lineHeight' => 1.5,
            ],
            'sections' => [],
        ];
    }

    /**
     * Dummy data for preview rendering when student is not selected.
     */
    protected function dummyData(): array
    {
        return [
            'student' => [
                'full_name' => 'Ahmad Fauzi (Contoh)',
                'student_number' => '2025001',
                'national_student_number' => '0012345678',
                'gender' => 'male',
                'place_of_birth' => 'Malang',
                'date_of_birth' => '15/03/2012',
                'parent_name' => 'Bpk. Suryanto',
            ],
            'classroom' => [
                'name' => '2A',
                'grade' => '2',
                'homeroom_teacher' => [
                    'full_name' => 'Ust. Muhammad Ridwan, S.Pd.I',
                    'employee_number' => '19850101',
                ],
            ],
            'dormitory' => [
                'name' => 'Al-Ghazali 01',
                'building' => 'Gedung A',
            ],
            'academic_year' => [
                'start_year' => 2025,
                'end_year' => 2026,
                'semester' => 'ganjil',
            ],
            'report_card' => [
                'ranking' => 3,
                'homeroom_notes' => 'Tingkatkan terus prestasi belajar dan kedisiplinan beribadah.',
                'headmaster_notes' => 'Alhamdulillah, ananda menunjukkan perkembangan akhlak dan akademik yang sangat membanggakan.',
            ],
            'academic_scores' => [
                [
                    'index' => 1,
                    'subject' => ['name' => 'Fiqh Ibadah'],
                    'assignment_score' => 88,
                    'midterm_score' => 85,
                    'final_score' => 90,
                    'numeric_score' => 88,
                    'letter' => 'A',
                    'predicate' => 'Jayyid Jiddan',
                    'description' => 'Memahami kaidah fiqih ibadah dan muamalah dengan sangat baik.',
                ],
                [
                    'index' => 2,
                    'subject' => ['name' => 'Nahwu (Alfiyah)'],
                    'assignment_score' => 78,
                    'midterm_score' => 80,
                    'final_score' => 82,
                    'numeric_score' => 80,
                    'letter' => 'B',
                    'predicate' => 'Jayyid',
                    'description' => 'Mampu menganalisis kaidah nahwu dan i\'rab dengan baik.',
                ],
                [
                    'index' => 3,
                    'subject' => ['name' => 'Tauhid & Aqidah'],
                    'assignment_score' => 92,
                    'midterm_score' => 94,
                    'final_score' => 95,
                    'numeric_score' => 94,
                    'letter' => 'A+',
                    'predicate' => 'Mumtaz',
                    'description' => 'Menguasai konsep aqidah dan dalil tauhid secara istimewa.',
                ],
            ],
            'sorogan' => [
                ['index' => 1, 'book' => ['title' => 'Fathul Qarib'], 'reading_score' => 85, 'translation_score' => 82, 'comprehension_score' => 84],
                ['index' => 2, 'book' => ['title' => 'Safinatun Najah'], 'reading_score' => 90, 'translation_score' => 88, 'comprehension_score' => 89],
            ],
            'memorization' => [
                ['index' => 1, 'type' => 'Quran Juz 30', 'portion' => 'An-Naba - An-Nas', 'fluency_score' => 92, 'tajweed_score' => 90, 'etiquette_score' => 95],
            ],
            'pesantren_scores' => [
                ['index' => 1, 'aspect' => 'Akhlak & Adab Keseharian', 'score' => 95, 'predicate' => 'Mumtaz'],
                ['index' => 2, 'aspect' => 'Kedisiplinan Sholat Berjamaah', 'score' => 90, 'predicate' => 'Jayyid Jiddan'],
            ],
            'total_score' => 262.0,
            'average_score' => 87.3,
            'average_predicate' => 'Jayyid Jiddan',
            'average_letter' => 'A',
            'summary' => [
                'total_score' => 262.0,
                'average_score' => 87.3,
                'average_predicate' => 'Jayyid Jiddan',
                'average_letter' => 'A',
                'print_date' => now()->translatedFormat('d F Y'),
            ],
            'signatures' => [
                'homeroom_teacher' => [
                    'name' => 'Ust. Muhammad Ridwan, S.Pd.I',
                    'employee_number' => '19850101',
                    'image' => '',
                    'label' => 'Wali Kelas',
                ],
                'headmaster' => [
                    'name' => 'KH. Abdullah Syafi\'i',
                    'employee_number' => '',
                    'image' => '',
                    'label' => 'Pimpinan Pondok Pesantren',
                ],
            ],
            'settings' => [
                'pesantren_name' => 'Pondok Pesantren Al-Hikmah',
                'headmaster_name' => 'KH. Abdullah Syafi\'i',
            ],
            'print_date' => now()->translatedFormat('d F Y'),
        ];
    }
}
