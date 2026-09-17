<?php

namespace App\Modules\PromotionCriterion\Controllers;

use App\DataTables\PromotionCriterionDataTable;
use App\Http\Controllers\MainController;
use Models\PromotionCriterion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PromotionCriterionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PromotionCriterion());
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
        PromotionCriterionDataTable $dataTable
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
            'PromotionCriterion::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $promotionCriterion = new PromotionCriterion();
        $result = $this->getForm($promotionCriterion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PromotionCriterion::create',
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
                return PromotionCriterion::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PromotionCriterion berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PromotionCriterion.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionCriterion gagal ditambahkan.'
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
            'PromotionCriterion::edit',
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
                'Data PromotionCriterion berhasil diperbarui.',
                $promotionCriterion
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui PromotionCriterion.',
                [
                    'exception' => $e,
                    'PromotionCriterion_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionCriterion gagal diperbarui.'
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
                'Data PromotionCriterion berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $promotionCriterion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PromotionCriterion.',
                [
                    'exception' => $e,
                    'PromotionCriterion_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionCriterion gagal dihapus.'
            );
        }
    } 
}
