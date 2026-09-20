<?php

namespace App\Modules\PromotionCriteria\Controllers;

use App\DataTables\PromotionCriteriaDataTable;
use App\Http\Controllers\MainController;
use Models\PromotionCriteria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PromotionCriteriaController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PromotionCriteria());
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
        PromotionCriteriaDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /promotion-criterion?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PromotionCriteria::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $promotionCriterion = new PromotionCriteria();
        $result = $this->getForm($promotionCriterion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PromotionCriteria::create',
            compact('promotionCriterion', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'min_final_score' => ['required', 'string', 'max:255'],
            'min_attendance_rate' => ['required', 'string', 'max:255'],
            'min_behavior_score' => ['required', 'string', 'max:255'],
            'max_failed_subjects' => ['required', 'string', 'max:255'],
            'auto_promote' => ['required', 'string', 'max:255'],
            'allow_manual_override' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PromotionCriteria::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PromotionCriteria berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PromotionCriteria.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionCriteria gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $promotionCriterion = $this->model->findOrFail($id);
        $result = $this->getForm($promotionCriterion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PromotionCriteria::edit',
            compact('promotionCriterion', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'min_final_score' => ['required', 'string', 'max:255'],
            'min_attendance_rate' => ['required', 'string', 'max:255'],
            'min_behavior_score' => ['required', 'string', 'max:255'],
            'max_failed_subjects' => ['required', 'string', 'max:255'],
            'auto_promote' => ['required', 'string', 'max:255'],
            'allow_manual_override' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $promotionCriterion = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $promotionCriterion,
                $validated
            ) {
                $promotionCriterion->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PromotionCriteria berhasil diperbarui.',
                $promotionCriterion
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PromotionCriteria.',
                [
                    'exception' => $e,
                    'PromotionCriteria_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionCriteria gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $promotionCriterion = $this->model->findOrFail($id);

            DB::transaction(function () use ($promotionCriterion) {
                $promotionCriterion->delete();
            });

            return $this->successResponse(
                $request,
                'Data PromotionCriteria berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $promotionCriterion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PromotionCriteria.',
                [
                    'exception' => $e,
                    'PromotionCriteria_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionCriteria gagal dihapus.'
            );
        }
    }
}
