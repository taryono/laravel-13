<?php

namespace App\Modules\AdmissionTimeline\Controllers;

use App\DataTables\AdmissionTimelineDataTable;
use App\Http\Controllers\MainController;
use Models\AdmissionTimeline;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionTimelineController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AdmissionTimeline());
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
        AdmissionTimelineDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission-timeline?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AdmissionTimeline::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admissionTimeline = new AdmissionTimeline();
        $result = $this->getForm($admissionTimeline);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionTimeline::create',
            compact('admissionTimeline', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'phase_name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'order_number' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AdmissionTimeline::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionTimeline berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AdmissionTimeline.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionTimeline gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admissionTimeline = $this->model->findOrFail($id);
        $result = $this->getForm($admissionTimeline);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionTimeline::edit',
            compact('admissionTimeline', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'phase_name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'order_number' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admissionTimeline = $this->model->findOrFail($id);
            DB::transaction(function () use ($admissionTimeline, $validated) {
                $admissionTimeline->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionTimeline berhasil diperbarui.',
                $admissionTimeline
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AdmissionTimeline.',
                [
                    'exception' => $e,
                    'AdmissionTimeline_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionTimeline gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admissionTimeline = $this->model->findOrFail($id);

            DB::transaction(function () use ($admissionTimeline) {
                $admissionTimeline->delete();
            });

            return $this->successResponse(
                $request,
                'Data AdmissionTimeline berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $admissionTimeline->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AdmissionTimeline.',
                [
                    'exception' => $e,
                    'AdmissionTimeline_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionTimeline gagal dihapus.'
            );
        }
    }
}
