<?php

namespace App\Modules\AttitudeAssessment\Controllers;

use App\DataTables\AttitudeAssessmentDataTable;
use App\Http\Controllers\MainController;
use Models\AttitudeAssessment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AttitudeAssessmentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AttitudeAssessment());
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
        AttitudeAssessmentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /attitude-assessment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AttitudeAssessment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $attitudeAssessment = new AttitudeAssessment();
        $result = $this->getForm($attitudeAssessment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AttitudeAssessment::create',
            compact('attitudeAssessment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AttitudeAssessment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AttitudeAssessment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AttitudeAssessment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttitudeAssessment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $attitudeAssessment = $this->model->findOrFail($id);
        $result = $this->getForm($attitudeAssessment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AttitudeAssessment::edit',
            compact('attitudeAssessment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $attitudeAssessment = $this->model->findOrFail($id);
            DB::transaction(function () use ($attitudeAssessment, $validated) {
                $attitudeAssessment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AttitudeAssessment berhasil diperbarui.',
                $attitudeAssessment
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AttitudeAssessment.',
                [
                    'exception' => $e,
                    'AttitudeAssessment_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttitudeAssessment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $attitudeAssessment = $this->model->findOrFail($id);

            DB::transaction(function () use ($attitudeAssessment) {
                $attitudeAssessment->delete();
            });

            return $this->successResponse(
                $request,
                'Data AttitudeAssessment berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $attitudeAssessment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AttitudeAssessment.',
                [
                    'exception' => $e,
                    'AttitudeAssessment_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttitudeAssessment gagal dihapus.'
            );
        }
    }
}
