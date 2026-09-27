<?php

namespace App\Modules\AssessmentType\Controllers;

use App\DataTables\AssessmentTypeDataTable;
use App\Http\Controllers\MainController;
use Models\AssessmentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssessmentTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AssessmentType());
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
        AssessmentTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /assessment-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AssessmentType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assessmentType = new AssessmentType();
        $result = $this->getForm($assessmentType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssessmentType::create',
            compact('assessmentType', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:255'],
            'weight_default' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AssessmentType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssessmentType berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AssessmentType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assessmentType = $this->model->findOrFail($id);
        $result = $this->getForm($assessmentType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssessmentType::edit',
            compact('assessmentType', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:255'],
            'weight_default' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assessmentType = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $assessmentType,
                $validated
            ) {
                $assessmentType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssessmentType berhasil diperbarui.',
                $assessmentType
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AssessmentType.',
                [
                    'exception' => $e,
                    'AssessmentType_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assessmentType = $this->model->findOrFail($id);

            DB::transaction(function () use ($assessmentType) {
                $assessmentType->delete();
            });

            return $this->successResponse(
                $request,
                'Data AssessmentType berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $assessmentType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AssessmentType.',
                [
                    'exception' => $e,
                    'AssessmentType_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentType gagal dihapus.'
            );
        }
    }
}
