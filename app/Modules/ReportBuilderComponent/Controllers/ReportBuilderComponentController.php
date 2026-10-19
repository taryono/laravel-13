<?php

namespace App\Modules\ReportBuilderComponent\Controllers;

use App\Http\Controllers\MainController;
use Models\ReportBuilderComponent;
use Models\ReportDataSource;
use Models\AcademicYear;
use Models\Curriculum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class ReportBuilderComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportBuilderComponent());
    }

    /**
     * Display a listing of palette components.
     */
    public function index(Request $request): View
    {
        $category = $request->input('category');
        $query = ReportBuilderComponent::with(['curriculum', 'academicYear'])->orderBy('order');

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $components = $query->paginate(20)->withQueryString();
        $categories = ReportBuilderComponent::distinct()->whereNotNull('category')->pluck('category')->filter()->values()->toArray();

        $stats = [
            'total' => ReportBuilderComponent::count(),
            'active' => ReportBuilderComponent::where('is_active', true)->count(),
            'inactive' => ReportBuilderComponent::where('is_active', false)->count(),
        ];

        return view('ReportBuilder::components.index', compact('components', 'categories', 'category', 'stats'));
    }

    /**
     * Show the form for creating a new component.
     */
    public function create(): View
    {
        $component = new ReportBuilderComponent([
            'order' => (ReportBuilderComponent::max('order') ?? 0) + 1,
            'is_active' => true,
            'category' => 'blocks',
            'type' => 'table',
        ]);
        $categories = ReportBuilderComponent::distinct()->whereNotNull('category')->pluck('category')->filter()->values()->toArray();
        $availableBindings = config('report.available_bindings', []);
        $curriculums = Curriculum::active()->orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $dataSources = ReportDataSource::active()->orderBy('order')->orderBy('name')->get();

        return view('ReportBuilder::components.create', compact('component', 'categories', 'availableBindings', 'curriculums', 'academicYears', 'dataSources'));
    }

    /**
     * Store a newly created component.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'type'             => 'required|string|max:50',
            'category'         => 'required|string|max:50',
            'icon'             => 'nullable|string|max:20',
            'description'      => 'nullable|string|max:255',
            'order'            => 'required|integer|min:0',
            'curriculum_id'    => 'nullable|exists:curriculums,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'is_active'        => 'nullable',
            'default_config'   => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Handle default_config (JSON string or array)
        if (!empty($validated['default_config'])) {
            if (is_string($validated['default_config'])) {
                $decoded = json_decode($validated['default_config'], true);
                $validated['default_config'] = $decoded ?? ['type' => $validated['type']];
            }
        } else {
            $validated['default_config'] = $this->buildDefaultConfig($validated['type'], $validated['name']);
        }

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportBuilderComponent::create($validated);
            });

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Komponen Report Builder berhasil ditambahkan.',
                    'component' => $model,
                ]);
            }

            return redirect()->route('admin.report-builder.components.index')
                ->with('success', "Komponen '{$model->name}' berhasil ditambahkan ke palette.");
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan ReportBuilderComponent.', [
                'exception' => $e,
                'data' => $validated,
                'user_id' => Auth::id(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan komponen: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal menambahkan komponen: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified component.
     */
    public function edit($component): View
    {
        if (!($component instanceof ReportBuilderComponent)) {
            $component = ReportBuilderComponent::findOrFail($component);
        }

        $categories = ReportBuilderComponent::distinct()->whereNotNull('category')->pluck('category')->filter()->values()->toArray();
        $availableBindings = config('report.available_bindings', []);
        $curriculums = Curriculum::active()->orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $dataSources = ReportDataSource::active()->orderBy('order')->orderBy('name')->get();

        return view('ReportBuilder::components.edit', compact('component', 'categories', 'availableBindings', 'curriculums', 'academicYears', 'dataSources'));
    }

    /**
     * Update the specified component.
     */
    public function update(Request $request, $component)
    {
        if (!($component instanceof ReportBuilderComponent)) {
            $component = ReportBuilderComponent::findOrFail($component);
        }

        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'type'             => 'required|string|max:50',
            'category'         => 'required|string|max:50',
            'icon'             => 'nullable|string|max:20',
            'description'      => 'nullable|string|max:255',
            'order'            => 'required|integer|min:0',
            'curriculum_id'    => 'nullable|exists:curriculums,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'is_active'        => 'nullable',
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

        $validated['updated_by'] = Auth::id();

        try {
            DB::transaction(function () use ($component, $validated) {
                $component->update($validated);
            });

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Komponen berhasil diperbarui.',
                    'component' => $component,
                ]);
            }

            return redirect()->route('admin.report-builder.components.index')
                ->with('success', "Komponen '{$component->name}' berhasil diperbarui.");
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui ReportBuilderComponent.', [
                'exception' => $e,
                'id' => $component->id,
                'data' => $validated,
                'user_id' => Auth::id(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui komponen: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal memperbarui komponen: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified component.
     */
    public function destroy(Request $request, $component)
    {
        if (!($component instanceof ReportBuilderComponent)) {
            $component = ReportBuilderComponent::findOrFail($component);
        }

        $name = $component->name;
        $component->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Komponen '{$name}' berhasil dihapus.",
            ]);
        }

        return redirect()->route('admin.report-builder.components.index')
            ->with('success', "Komponen '{$name}' berhasil dihapus.");
    }

    /**
     * Toggle component active status in palette.
     */
    public function toggleStatus(Request $request, $component)
    {
        if (!($component instanceof ReportBuilderComponent)) {
            $component = ReportBuilderComponent::findOrFail($component);
        }

        $component->update(['is_active' => !$component->is_active]);
        $status = $component->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $component->is_active,
                'message' => "Komponen '{$component->name}' berhasil {$status}.",
            ]);
        }

        return back()->with('success', "Komponen '{$component->name}' berhasil {$status}.");
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
                        ['header' => 'Mata Pelajaran', 'field' => 'subject.name', 'width' => '50%', 'align' => 'left'],
                        ['header' => 'Nilai', 'field' => 'numeric_score', 'width' => '20%', 'align' => 'center'],
                        ['header' => 'Predikat', 'field' => 'predicate', 'width' => '20%', 'align' => 'center'],
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
                        ['label' => 'Nama Santri', 'binding' => 'student.full_name'],
                        ['label' => 'NIS / NISN', 'binding' => 'student.student_number'],
                        ['label' => 'Kelas', 'binding' => 'classroom.name'],
                        ['label' => 'Asrama', 'binding' => 'dormitory.name'],
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
}
