<?php

namespace App\Modules\SiblingDiscountAllocation\Controllers;

use App\DataTables\SiblingDiscountAllocationDataTable;
use App\Http\Controllers\MainController;
use Models\SiblingDiscountAllocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SiblingDiscountAllocationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SiblingDiscountAllocation());
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
        SiblingDiscountAllocationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /sibling-discount-allocation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SiblingDiscountAllocation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $siblingDiscountAllocation = new SiblingDiscountAllocation();
        $result = $this->getForm($siblingDiscountAllocation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SiblingDiscountAllocation::create',
            compact('siblingDiscountAllocation', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'sibling_discount_rule_id' => ['required', 'integer'],
            'target_type' => ['required', 'string', 'max:255'],
            'sibling_order_from' => ['required', 'string', 'max:255'],
            'sibling_order_to' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'string', 'max:255'],
            'discount_value' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return SiblingDiscountAllocation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountAllocation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SiblingDiscountAllocation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountAllocation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $siblingDiscountAllocation = $this->model->findOrFail($id);
        $result = $this->getForm($siblingDiscountAllocation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SiblingDiscountAllocation::edit',
            compact('siblingDiscountAllocation', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'sibling_discount_rule_id' => ['required', 'integer'],
            'target_type' => ['required', 'string', 'max:255'],
            'sibling_order_from' => ['required', 'string', 'max:255'],
            'sibling_order_to' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'string', 'max:255'],
            'discount_value' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $siblingDiscountAllocation = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $siblingDiscountAllocation,
                $validated
            ) {
                $siblingDiscountAllocation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountAllocation berhasil diperbarui.',
                $siblingDiscountAllocation
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui SiblingDiscountAllocation.',
                [
                    'exception' => $e,
                    'SiblingDiscountAllocation_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountAllocation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $siblingDiscountAllocation = $this->model->findOrFail($id);

            DB::transaction(function () use ($siblingDiscountAllocation) {
                $siblingDiscountAllocation->delete();
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountAllocation berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $siblingDiscountAllocation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SiblingDiscountAllocation.',
                [
                    'exception' => $e,
                    'SiblingDiscountAllocation_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountAllocation gagal dihapus.'
            );
        }
    } 
}
