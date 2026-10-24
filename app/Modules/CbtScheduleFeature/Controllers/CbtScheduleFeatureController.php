<?php

namespace App\Modules\CbtScheduleFeature\Controllers;

use App\DataTables\CbtScheduleFeatureDataTable;
use App\Http\Controllers\MainController;
use Models\CbtScheduleFeature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CbtScheduleFeatureController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CbtScheduleFeature());
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
        CbtScheduleFeatureDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cbt-schedule-feature?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CbtScheduleFeature::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cbtScheduleFeature = new CbtScheduleFeature();
        $result = $this->getForm($cbtScheduleFeature);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtScheduleFeature::create',
            compact('cbtScheduleFeature', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'schedule_id' => ['required', 'integer'],
            'feature_id' => ['required', 'integer'],
            'is_enabled' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CbtScheduleFeature::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtScheduleFeature berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CbtScheduleFeature.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtScheduleFeature gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cbtScheduleFeature = $this->model->findOrFail($id);
        $result = $this->getForm($cbtScheduleFeature);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtScheduleFeature::edit',
            compact('cbtScheduleFeature', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'schedule_id' => ['required', 'integer'],
            'feature_id' => ['required', 'integer'],
            'is_enabled' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cbtScheduleFeature = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cbtScheduleFeature,
                $validated
            ) {
                $cbtScheduleFeature->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtScheduleFeature berhasil diperbarui.',
                $cbtScheduleFeature
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CbtScheduleFeature.',
                [
                    'exception' => $e,
                    'CbtScheduleFeature_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtScheduleFeature gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cbtScheduleFeature = $this->model->findOrFail($id);

            DB::transaction(function () use ($cbtScheduleFeature) {
                $cbtScheduleFeature->delete();
            });

            return $this->successResponse(
                $request,
                'Data CbtScheduleFeature berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cbtScheduleFeature->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CbtScheduleFeature.',
                [
                    'exception' => $e,
                    'CbtScheduleFeature_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtScheduleFeature gagal dihapus.'
            );
        }
    } 
}
