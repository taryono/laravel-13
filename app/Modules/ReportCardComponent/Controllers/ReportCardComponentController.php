<?php

namespace App\Modules\ReportCardComponent\Controllers;

use App\DataTables\ReportCardComponentDataTable;
use App\Http\Controllers\MainController;
use Models\ReportCardComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReportCardComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportCardComponent());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     *_Normal page request
     *_Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        ReportCardComponentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-card-component?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportCardComponent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reportCardComponent = new ReportCardComponent();
        $result = $this->getForm($reportCardComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardComponent::create',
            compact('reportCardComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_card_template_section_id' => ['required', 'integer'],
            'component_type' => ['required', 'string', 'max:255'],
            'field_key' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportCardComponent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardComponent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportCardComponent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardComponent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportCardComponent = $this->model->findOrFail($id);
        $result = $this->getForm($reportCardComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardComponent::edit',
            compact('reportCardComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'report_card_template_section_id' => ['required', 'integer'],
            'component_type' => ['required', 'string', 'max:255'],
            'field_key' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $reportCardComponent = $this->model->findOrFail($id);
            DB::transaction(function () use ($reportCardComponent, $validated) {
                $reportCardComponent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardComponent berhasil diperbarui.',
                $reportCardComponent
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ReportCardComponent.',
                [
                    'exception' => $e,
                    'ReportCardComponent_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardComponent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportCardComponent = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportCardComponent) {
                $reportCardComponent->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportCardComponent berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $reportCardComponent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportCardComponent.',
                [
                    'exception' => $e,
                    'ReportCardComponent_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardComponent gagal dihapus.'
            );
        }
    }
}
