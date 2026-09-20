<?php

namespace App\Modules\ExamPackage\Controllers;

use App\DataTables\ExamPackageDataTable;
use App\Http\Controllers\MainController;
use Models\ExamPackage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExamPackageController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExamPackage());
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
        ExamPackageDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /exam-package?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExamPackage::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $examPackage = new ExamPackage();
        $result = $this->getForm($examPackage);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExamPackage::create',
            compact('examPackage', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'total_questions' => ['required', 'string', 'max:255'],
            'passing_score' => ['required', 'string', 'max:255'],
            'is_randomized' => ['required', 'string', 'max:255'],
            'shuffle_options' => ['required', 'string', 'max:255'],
            'show_results' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExamPackage::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExamPackage berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExamPackage.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExamPackage gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $examPackage = $this->model->findOrFail($id);
        $result = $this->getForm($examPackage);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExamPackage::edit',
            compact('examPackage', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'total_questions' => ['required', 'string', 'max:255'],
            'passing_score' => ['required', 'string', 'max:255'],
            'is_randomized' => ['required', 'string', 'max:255'],
            'shuffle_options' => ['required', 'string', 'max:255'],
            'show_results' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $examPackage = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $examPackage,
                $validated
            ) {
                $examPackage->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExamPackage berhasil diperbarui.',
                $examPackage
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ExamPackage.',
                [
                    'exception' => $e,
                    'ExamPackage_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExamPackage gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $examPackage = $this->model->findOrFail($id);

            DB::transaction(function () use ($examPackage) {
                $examPackage->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExamPackage berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $examPackage->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExamPackage.',
                [
                    'exception' => $e,
                    'ExamPackage_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExamPackage gagal dihapus.'
            );
        }
    } 
}
