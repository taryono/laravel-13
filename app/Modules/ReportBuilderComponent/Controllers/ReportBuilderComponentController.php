<?php

namespace App\Modules\ReportBuilderComponent\Controllers;

use App\DataTables\ReportBuilderComponentDataTable;
use App\Http\Controllers\MainController;
use Models\ReportBuilderComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\AcademicYear;
use Models\Curriculum;
use Throwable;

class ReportBuilderComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportBuilderComponent());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        ReportBuilderComponentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-builder-component?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportBuilderComponent::index'
        );
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

        // Handle default_config (JSON string or array)
        if (!empty($validated['default_config'])) {
            if (is_string($validated['default_config'])) {
                $decoded = json_decode($validated['default_config'], true);
                $validated['default_config'] = $decoded ?? ['type' => $validated['type']];
            }
        } else {
            $validated['default_config'] = $this->buildDefaultConfig($validated['type'], $validated['name']);
        }



        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportBuilderComponent::create($validated);
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

    public function toggleStatus(Request $request, ReportBuilderComponent $component)
    {
        $component->is_active = !$component->is_active;
        $component->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'is_active' => $component->is_active,
                'message'   => 'Status komponen diperbarui.'
            ]);
        }

        return back()->with('success', 'Status komponen "' . $component->name . '" diperbarui.');
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
}
