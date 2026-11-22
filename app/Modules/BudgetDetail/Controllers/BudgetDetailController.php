<?php

namespace App\Modules\BudgetDetail\Controllers;

use App\DataTables\BudgetDetailDataTable;
use App\Http\Controllers\MainController;
use Models\BudgetDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BudgetDetailController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BudgetDetail());
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
        BudgetDetailDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /budget-detail?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BudgetDetail::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $budgetDetail = new BudgetDetail();
        $result = $this->getForm($budgetDetail);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BudgetDetail::create',
            compact('budgetDetail', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'budget_id' => ['required', 'integer'],
            'accounting_period_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'cost_center_id' => ['required', 'integer'],
            'budget_amount' => ['required', 'string', 'max:255'],
            'actual_amount' => ['required', 'string', 'max:255'],
            'variance_amount' => ['required', 'string', 'max:255'],
            'variance_percentage' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BudgetDetail::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BudgetDetail berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BudgetDetail.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BudgetDetail gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $budgetDetail = $this->model->findOrFail($id);
        $result = $this->getForm($budgetDetail);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BudgetDetail::edit',
            compact('budgetDetail', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'budget_id' => ['required', 'integer'],
            'accounting_period_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'cost_center_id' => ['required', 'integer'],
            'budget_amount' => ['required', 'string', 'max:255'],
            'actual_amount' => ['required', 'string', 'max:255'],
            'variance_amount' => ['required', 'string', 'max:255'],
            'variance_percentage' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $budgetDetail = $this->model->findOrFail($id);
            DB::transaction(function () use ($budgetDetail, $validated) {
                $budgetDetail->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BudgetDetail berhasil diperbarui.',
                $budgetDetail
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BudgetDetail.',
                [
                    'exception' => $e,
                    'BudgetDetail_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BudgetDetail gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $budgetDetail = $this->model->findOrFail($id);

            DB::transaction(function () use ($budgetDetail) {
                $budgetDetail->delete();
            });

            return $this->successResponse(
                $request,
                'Data BudgetDetail berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $budgetDetail->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BudgetDetail.',
                [
                    'exception' => $e,
                    'BudgetDetail_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BudgetDetail gagal dihapus.'
            );
        }
    }
}
