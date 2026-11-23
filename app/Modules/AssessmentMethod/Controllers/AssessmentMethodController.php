<?php

namespace App\Modules\AssessmentMethod\Controllers;

use App\DataTables\AssessmentMethodDataTable;
use App\Http\Controllers\MainController;
use Models\AssessmentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssessmentMethodController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AssessmentMethod());
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
        AssessmentMethodDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /assessment-method?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AssessmentMethod::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assessmentMethod = new AssessmentMethod();
        $result = $this->getForm($assessmentMethod);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssessmentMethod::create',
            compact('assessmentMethod', 'forms', 'groupedForms')
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
            'scale_type' => ['required', 'string', 'max:255'],
            'scale_config' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AssessmentMethod::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssessmentMethod berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AssessmentMethod.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentMethod gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assessmentMethod = $this->model->findOrFail($id);
        $result = $this->getForm($assessmentMethod);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssessmentMethod::edit',
            compact('assessmentMethod', 'forms', 'groupedForms')
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
            'scale_type' => ['required', 'string', 'max:255'],
            'scale_config' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assessmentMethod = $this->model->findOrFail($id);
            DB::transaction(function () use ($assessmentMethod, $validated) {
                $assessmentMethod->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssessmentMethod berhasil diperbarui.',
                $assessmentMethod
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AssessmentMethod.',
                [
                    'exception' => $e,
                    'AssessmentMethod_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentMethod gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assessmentMethod = $this->model->findOrFail($id);

            DB::transaction(function () use ($assessmentMethod) {
                $assessmentMethod->delete();
            });

            return $this->successResponse(
                $request,
                'Data AssessmentMethod berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $assessmentMethod->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AssessmentMethod.',
                [
                    'exception' => $e,
                    'AssessmentMethod_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentMethod gagal dihapus.'
            );
        }
    }
}
