<?php

namespace App\Modules\ReportBuilder\Controllers;

use App\Http\Controllers\MainController;
use App\Models\ReportTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use App\Services\Report\ReportPdfService;
use App\Services\ReportCompilerService;
use App\Services\ReportRenderService;

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
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(Request $request)
    {
        $query = ReportTemplate::with(['creator', 'curriculums', 'signatories'])
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

        return view('ReportBuilderComponent::index', compact('templates', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reportBuilderComponent = new ReportBuilderComponent();
        $categories = ReportBuilderComponent::distinct()->pluck('category');
        $availableBindings = config('report.available_bindings', []);
        $curriculums = Curriculum::active()->orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('start_year')->get();

        return view(
            'ReportBuilderComponent::create',
            compact('reportBuilderComponent', 'categories', 'availableBindings', 'curriculums', 'academicYears')
        );
    }

    /**
     * Store a newly created resource.
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

        // Pastikan page config tersinkronisasi lengkap untuk snapshot cetak
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

        // Sync dimensi kertas (mm) ke config JSON juga agar mandiri (self-contained) saat dicetak PDF
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

        if (!empty($data['id'])) {
            $template = ReportTemplate::findOrFail($data['id']);
            $template->update($data);
        } else {
            $template = ReportTemplate::create(array_merge($data, [
                'created_by' => auth()->id(),
            ]));
        }

        // Sync / simpan relasi ke tabel report_template_curriculums
        if (!empty($data['curriculum_id'])) {
            $templateCurriculum = ReportTemplateCurriculum::where('report_template_id', $template->id)->first();
            if ($templateCurriculum) {
                $templateCurriculum->update([
                    'curriculum_id'    => $data['curriculum_id'],
                    'academic_year_id' => $data['academic_year_id'] ?: null,
                    'is_active'        => true,
                    'updated_by'       => auth()->id(),
                ]);
            } else {
                ReportTemplateCurriculum::create([
                    'report_template_id' => $template->id,
                    'curriculum_id'      => $data['curriculum_id'],
                    'academic_year_id'   => $data['academic_year_id'] ?: null,
                    'is_active'          => true,
                    'created_by'         => auth()->id(),
                    'updated_by'         => auth()->id(),
                ]);
            }
        } else {
            ReportTemplateCurriculum::where('report_template_id', $template->id)->delete();
        }

        $compiled = $this->compiler->compile($config, $template);
        $template->update([
            'compiled_html' => $compiled['html'],
            'compiled_css' => $compiled['css'],
        ]);

        $lastVersion = $template->versions()->max('version') ?? 0;
        ReportTemplateVersion::create([
            'template_id' => $template->id,
            'version' => $lastVersion + 1,
            'configuration' => $config,
            'created_by' => auth()->id(),
        ]);

        $this->syncSignatories($template, $config);
        try {
            $model = DB::transaction(function () use ($validated, $template, $config) {
                $lastVersion = $template->versions()->max('version') ?? 0;
                ReportTemplateVersion::create([
                    'template_id' => $template->id,
                    'version' => $lastVersion + 1,
                    'configuration' => $config,
                    'created_by' => auth()->id(),
                ]);

                $this->syncSignatories($template, $config);
            });

            return $this->successResponse(
                $request,
                'Data ReportBuilderComponent berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportBuilderComponent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportBuilderComponent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportBuilderComponent = $this->model->findOrFail($id);
        $result = $this->getForm($reportBuilderComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportBuilderComponent::edit',
            compact('reportBuilderComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        try {
            $reportBuilderComponent = $this->model->findOrFail($id);
            $validated = $request->validate([
                'name'             => 'required|string|max:100',
                'type'             => 'required|string|max:50',
                'category'         => 'required|string|max:50',
                'icon'             => 'nullable|string|max:20',
                'description'      => 'nullable|string|max:255',
                'order'            => 'required|integer|min:0',
                'curriculum_id'    => 'nullable|exists:curriculums,id',
                'academic_year_id' => 'nullable|exists:academic_years,id',
                'is_active'        => 'boolean',
                'default_config'   => 'nullable',
            ]);

            $validated['is_active'] = $request->has('is_active');

            if (!empty($validated['default_config'])) {
                if (is_string($validated['default_config'])) {
                    $decoded = json_decode($validated['default_config'], true);
                    $validated['default_config'] = $decoded ?? ['type' => $validated['type']];
                }
            } else {
                $validated['default_config'] = $this->buildDefaultConfig($validated['type'], $validated['name']);
            }

            DB::transaction(function () use (
                $reportBuilderComponent,
                $validated
            ) {
                $reportBuilderComponent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportBuilderComponent berhasil diperbarui.',
                $reportBuilderComponent
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ReportBuilderComponent.',
                [
                    'exception' => $e,
                    'ReportBuilderComponent_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportBuilderComponent gagal diperbarui.'
            );
        }
    }

    protected function buildDefaultConfig(string $type, string $name): array
    {
        switch ($type) {
            case 'text':
            case 'header':
            case 'footer':
                return [
                    'type'    => 'text',
                    'content' => $name,
                    'style'   => [
                        'fontSize'   => 12,
                        'fontWeight' => 'normal',
                        'textAlign'  => 'left',
                        'color'      => '#000000',
                        'fontFamily' => 'Times New Roman',
                    ]
                ];
            case 'table':
                return [
                    'type'       => 'table',
                    'title'      => strtoupper($name),
                    'dataSource' => 'academic_scores',
                    'columns'    => [
                        ['header' => 'No', 'field' => 'index', 'width' => '10%', 'align' => 'center'],
                        ['header' => 'Keterangan', 'field' => 'name', 'width' => '50%', 'align' => 'left'],
                        ['header' => 'Nilai', 'field' => 'score', 'width' => '40%', 'align' => 'center'],
                    ],
                    'style'      => [
                        'fontSize'   => 10,
                        'textAlign'  => 'left',
                        'color'      => '#000000',
                        'fontFamily' => 'Times New Roman',
                    ],
                    'showFooter' => false,
                ];
            case 'keyvalue':
            case 'identity':
                return [
                    'type'   => 'keyvalue',
                    'fields' => [
                        ['label' => 'Nama', 'binding' => 'student.full_name'],
                        ['label' => 'NIS', 'binding' => 'student.student_number'],
                    ],
                    'style'  => [
                        'fontSize'   => 12,
                        'textAlign'  => 'left',
                        'color'      => '#000000',
                        'fontFamily' => 'Times New Roman',
                    ]
                ];
            case 'text-block':
                return [
                    'type'    => 'text-block',
                    'title'   => $name,
                    'binding' => 'report_card.homeroom_notes',
                    'style'   => [
                        'fontSize'   => 12,
                        'fontStyle'  => 'italic',
                        'textAlign'  => 'left',
                        'color'      => '#000000',
                        'fontFamily' => 'Times New Roman',
                    ]
                ];
            case 'signature':
                return [
                    'type'    => 'signature',
                    'columns' => [
                        [
                            'role'               => 'homeroom_teacher',
                            'label'              => 'Wali Kelas',
                            'source'             => 'automatic',
                            'showImage'          => true,
                            'showEmployeeNumber' => true,
                        ],
                        [
                            'role'               => 'headmaster',
                            'label'              => 'Kepala Sekolah / Pesantren',
                            'source'             => 'automatic',
                            'showImage'          => true,
                            'showEmployeeNumber' => false,
                        ],
                    ],
                    'style'   => [
                        'fontSize'   => 11,
                        'textAlign'  => 'center',
                        'color'      => '#000000',
                        'fontFamily' => 'Times New Roman',
                    ]
                ];
            case 'divider':
                return ['type' => 'divider', 'style' => []];
            case 'pagebreak':
                return ['type' => 'pagebreak', 'style' => []];
            default:
                return [
                    'type'    => $type,
                    'content' => $name,
                    'style'   => ['fontSize' => 12, 'textAlign' => 'left', 'color' => '#000000']
                ];
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportBuilderComponent = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportBuilderComponent) {
                $reportBuilderComponent->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportBuilderComponent berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $reportBuilderComponent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportBuilderComponent.',
                [
                    'exception' => $e,
                    'ReportBuilderComponent_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportBuilderComponent gagal dihapus.'
            );
        }
    }

    public function uploadAsset(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'type' => 'required|in:logo,signature,stamp,ornament,background,watermark',
        ]);

        $path = $request->file('file')->store(config('report.asset_path'), config('report.asset_disk'));

        $asset = TemplateAsset::create([
            'template_id' => $request->input('template_id'),
            'name' => $request->file('file')->getClientOriginalName(),
            'type' => $request->input('type'),
            'file_path' => $path,
            'mime_type' => $request->file('file')->getMimeType(),
            'file_size' => $request->file('file')->getSize(),
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

    public function duplicate(ReportTemplate $template)
    {
        $new = $template->replicate();
        $new->name = $template->name . ' (Copy)';
        $new->is_default = false;
        $new->created_by = auth()->id();
        $new->save();

        return redirect()->route('admin.report-builder.builder', $new->id)
            ->with('success', 'Template duplicated successfully.');
    }

    public function toggleStatus(ReportTemplate $template)
    {
        $template->update(['is_active' => !$template->is_active]);
        $status = $template->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Template '{$template->name}' berhasil {$status}.");
    }

    public function setDefault(ReportTemplate $template)
    {
        ReportTemplate::where('id', '!=', $template->id)->update(['is_default' => false]);
        $template->update(['is_default' => true, 'is_active' => true]);
        return back()->with('success', "Template '{$template->name}' berhasil ditetapkan sebagai template bawaan.");
    }

    public function previewTemplate(ReportTemplate $template, Request $request)
    {
        $config = $template->configuration ?? $this->defaultConfiguration();
        $compiled = $this->compiler->compile($config, $template);

        $studentId = $request->input('student_id');
        $student = $studentId
            ? Student::with(['activeClassroom.homeroomTeacher', 'dormitory'])->find($studentId)
            : Student::with(['activeClassroom.homeroomTeacher', 'dormitory'])->first();

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
     * Preview cetak PDF langsung dari builder admin menggunakan ReportPdfService (sama seperti ParentPortal).
     */
    public function previewPdf(ReportTemplate $template, Request $request)
    {
        $config = $template->configuration ?? $this->defaultConfiguration();
        $compiled = $this->compiler->compile($config, $template);

        $studentId = $request->input('student_id');
        $student = $studentId
            ? Student::with(['activeClassroom.homeroomTeacher', 'dormitory'])->find($studentId)
            : Student::with(['activeClassroom.homeroomTeacher', 'dormitory'])->first();

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

        // Inject size, margin, dan dimensi ke config page
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

        // ============================================================
        // 1. COMPILE → HTML + CSS template dengan placeholder [[ ]]
        // ============================================================
        $compiled = $this->compiler->compile($config, $template);

        // ============================================================
        // 2. BIND DATA → render placeholder dengan data santri
        // ============================================================
        $studentId = $request->input('student_id', config('report.preview_dummy_student_id', 1));
        $student = Student::with(['activeClassroom.homeroomTeacher', 'dormitory'])->find($studentId);

        if ($student) {
            $academicYearId = $request->input(
                'academic_year_id',
                AcademicYear::where('is_active', true)->value('id') ?? 1
            );

            $data = $this->renderer->buildData($student, $academicYearId, $template);
            $html = $this->renderer->bindData($compiled['full'], $data);
        } else {
            // Fallback: render dengan data dummy minimal supaya placeholder terisi
            $dummyData = $this->dummyData();
            $html = $this->renderer->bindData($compiled['full'], $dummyData);
        }

        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Data dummy untuk preview saat tidak ada student.
     */
    protected function dummyData(): array
    {
        return [
            'student' => [
                'full_name' => 'Ahmad Fauzi (Sample)',
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
                'building' => 'Building A',
            ],
            'academic_year' => [
                'start_year' => 2025,
                'end_year' => 2026,
                'semester' => 'odd',
            ],
            'report_card' => [
                'ranking' => 3,
                'homeroom_notes' => 'Sample homeroom notes.',
                'headmaster_notes' => 'Sample headmaster notes.',
            ],
            'academic_scores' => [
                [
                    'index' => 1,
                    'subject' => ['name' => 'Fiqh'],
                    'assignment_score' => 85,
                    'midterm_score' => 84,
                    'final_score' => 86,
                    'numeric_score' => 85,
                    'letter' => 'A',
                    'predicate' => 'Jayyid Jiddan',
                    'description' => 'Memahami kaidah fiqih ibadah dan muamalah dengan sangat baik.',
                ],
                [
                    'index' => 2,
                    'subject' => ['name' => 'Nahwu (Alfiyah)'],
                    'assignment_score' => 75,
                    'midterm_score' => 78,
                    'final_score' => 80,
                    'numeric_score' => 78,
                    'letter' => 'B',
                    'predicate' => 'Jayyid',
                    'description' => 'Mampu menganalisis kaidah nahwu dan i\'rab dengan baik.',
                ],
                [
                    'index' => 3,
                    'subject' => ['name' => 'Tauhid'],
                    'assignment_score' => 90,
                    'midterm_score' => 92,
                    'final_score' => 94,
                    'numeric_score' => 92,
                    'letter' => 'A+',
                    'predicate' => 'Mumtaz',
                    'description' => 'Menguasai konsep aqidah dan dalil tauhid secara istimewa.',
                ],
            ],
            'sorogan' => [
                ['index' => 1, 'book' => ['title' => 'Fathul Qarib'], 'reading_score' => 85, 'translation_score' => 80, 'comprehension_score' => 82],
                ['index' => 2, 'book' => ['title' => 'Alfiyah'], 'reading_score' => 78, 'translation_score' => 75, 'comprehension_score' => 77],
            ],
            'memorization' => [
                ['index' => 1, 'type' => 'Quran Juz 30', 'portion' => 'Juz 30', 'fluency_score' => 90, 'tajweed_score' => 88, 'etiquette_score' => 90],
            ],
            'pesantren_scores' => [
                ['index' => 1, 'aspect' => 'Etiquette & Morals', 'score' => 92, 'predicate' => 'Mumtaz'],
                ['index' => 2, 'aspect' => 'Discipline', 'score' => 85, 'predicate' => 'Jayyid Jiddan'],
            ],
            'total_score' => 255.0,
            'average_score' => 85.0,
            'average_predicate' => 'Jayyid Jiddan',
            'average_letter' => 'A',
            'summary' => [
                'total_score' => 255.0,
                'average_score' => 85.0,
                'average_predicate' => 'Jayyid Jiddan',
                'average_letter' => 'A',
                'print_date' => now()->format('d F Y'),
            ],
            'signatures' => [
                'homeroom_teacher' => [
                    'name' => 'Ust. Muhammad Ridwan, S.Pd.I',
                    'employee_number' => '19850101',
                    'image' => '',
                    'label' => 'Homeroom Teacher',
                ],
                'headmaster' => [
                    'name' => 'KH. Abdullah Syafi\'i',
                    'employee_number' => '',
                    'image' => '',
                    'label' => 'Headmaster',
                ],
            ],
            'settings' => [
                'pesantren_name' => 'Pondok Pesantren Al-Hikmah (Sample)',
                'headmaster_name' => 'KH. Abdullah Syafi\'i',
            ],
            'print_date' => now()->format('d F Y'),
        ];
    }
}
