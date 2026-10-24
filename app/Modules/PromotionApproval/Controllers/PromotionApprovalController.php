<?php

namespace App\Modules\PromotionApproval\Controllers;

use App\DataTables\PromotionApprovalDataTable;
use App\Http\Controllers\MainController;
use Models\PromotionApproval;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PromotionApprovalController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PromotionApproval());
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
        PromotionApprovalDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /promotion-approval?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PromotionApproval::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $promotionApproval = new PromotionApproval();
        $result = $this->getForm($promotionApproval);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PromotionApproval::create',
            compact('promotionApproval', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'promotion_id' => ['required', 'integer'],
            'approver_id' => ['required', 'integer'],
            'approver_role' => ['required', 'string', 'max:255'],
            'approval_level' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PromotionApproval::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PromotionApproval berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PromotionApproval.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionApproval gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $promotionApproval = $this->model->findOrFail($id);
        $result = $this->getForm($promotionApproval);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PromotionApproval::edit',
            compact('promotionApproval', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'promotion_id' => ['required', 'integer'],
            'approver_id' => ['required', 'integer'],
            'approver_role' => ['required', 'string', 'max:255'],
            'approval_level' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $promotionApproval = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $promotionApproval,
                $validated
            ) {
                $promotionApproval->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PromotionApproval berhasil diperbarui.',
                $promotionApproval
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui PromotionApproval.',
                [
                    'exception' => $e,
                    'PromotionApproval_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionApproval gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $promotionApproval = $this->model->findOrFail($id);

            DB::transaction(function () use ($promotionApproval) {
                $promotionApproval->delete();
            });

            return $this->successResponse(
                $request,
                'Data PromotionApproval berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $promotionApproval->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PromotionApproval.',
                [
                    'exception' => $e,
                    'PromotionApproval_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PromotionApproval gagal dihapus.'
            );
        }
    } 
}
