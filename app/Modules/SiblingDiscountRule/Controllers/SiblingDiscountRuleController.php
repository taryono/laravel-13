<?php

namespace App\Modules\SiblingDiscountRule\Controllers;

use App\DataTables\SiblingDiscountRuleDataTable;
use App\Http\Controllers\MainController;
use Models\SiblingDiscountRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SiblingDiscountRuleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SiblingDiscountRule());
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
        SiblingDiscountRuleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /sibling-discount-rule?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SiblingDiscountRule::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $siblingDiscountRule = new SiblingDiscountRule();
        $result = $this->getForm($siblingDiscountRule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SiblingDiscountRule::create',
            compact('siblingDiscountRule', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'sibling_discount_program_id' => ['required', 'integer'],
            'min_sibling_count' => ['required', 'string', 'max:255'],
            'max_sibling_count' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'string', 'max:255'],
            'discount_value' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return SiblingDiscountRule::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountRule berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SiblingDiscountRule.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountRule gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $siblingDiscountRule = $this->model->findOrFail($id);
        $result = $this->getForm($siblingDiscountRule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SiblingDiscountRule::edit',
            compact('siblingDiscountRule', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'sibling_discount_program_id' => ['required', 'integer'],
            'min_sibling_count' => ['required', 'string', 'max:255'],
            'max_sibling_count' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'string', 'max:255'],
            'discount_value' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $siblingDiscountRule = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $siblingDiscountRule,
                $validated
            ) {
                $siblingDiscountRule->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountRule berhasil diperbarui.',
                $siblingDiscountRule
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui SiblingDiscountRule.',
                [
                    'exception' => $e,
                    'SiblingDiscountRule_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountRule gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $siblingDiscountRule = $this->model->findOrFail($id);

            DB::transaction(function () use ($siblingDiscountRule) {
                $siblingDiscountRule->delete();
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountRule berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $siblingDiscountRule->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SiblingDiscountRule.',
                [
                    'exception' => $e,
                    'SiblingDiscountRule_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountRule gagal dihapus.'
            );
        }
    } 
}
