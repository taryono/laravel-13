<?php

namespace App\Modules\PromotionBatch\Controllers;

use App\DataTables\PromotionBatchDataTable;
use App\Http\Controllers\MainController;
use Models\PromotionBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Illuminate\Support\Facades\Auth;

class PromotionBatchController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PromotionBatch());
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
        PromotionBatchDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /promotion-batch?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PromotionBatch::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $promotionBatch = new PromotionBatch();
        $result = $this->getForm($promotionBatch);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PromotionBatch::create',
            compact('promotionBatch', 'forms', 'groupedForms')
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
            'from_academic_year_id' => ['required', 'integer'],
            'to_academic_year_id' => ['required', 'integer'],
            'total_students' => ['required', 'string', 'max:255'],
            'processed_count' => ['required', 'string', 'max:255'],
            'promoted_count' => ['required', 'string', 'max:255'],
            'retained_count' => ['required', 'string', 'max:255'],
            'graduated_count' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'started_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PromotionBatch::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PromotionBatch berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PromotionBatch.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionBatch gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $promotionBatch = $this->model->findOrFail($id);
        $result = $this->getForm($promotionBatch);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PromotionBatch::edit',
            compact('promotionBatch', 'forms', 'groupedForms')
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
            'from_academic_year_id' => ['required', 'integer'],
            'to_academic_year_id' => ['required', 'integer'],
            'total_students' => ['required', 'string', 'max:255'],
            'processed_count' => ['required', 'string', 'max:255'],
            'promoted_count' => ['required', 'string', 'max:255'],
            'retained_count' => ['required', 'string', 'max:255'],
            'graduated_count' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'started_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $promotionBatch = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $promotionBatch,
                $validated
            ) {
                $promotionBatch->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PromotionBatch berhasil diperbarui.',
                $promotionBatch
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PromotionBatch.',
                [
                    'exception' => $e,
                    'PromotionBatch_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionBatch gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $promotionBatch = $this->model->findOrFail($id);

            DB::transaction(function () use ($promotionBatch) {
                $promotionBatch->delete();
            });

            return $this->successResponse(
                $request,
                'Data PromotionBatch berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $promotionBatch->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PromotionBatch.',
                [
                    'exception' => $e,
                    'PromotionBatch_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionBatch gagal dihapus.'
            );
        }
    }
}
