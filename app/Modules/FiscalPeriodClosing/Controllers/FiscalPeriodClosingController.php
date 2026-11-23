<?php

namespace App\Modules\FiscalPeriodClosing\Controllers;

use App\DataTables\FiscalPeriodClosingDataTable;
use App\Http\Controllers\MainController;
use Models\FiscalPeriodClosing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FiscalPeriodClosingController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FiscalPeriodClosing());
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
        FiscalPeriodClosingDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /fiscal-period-closing?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FiscalPeriodClosing::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $fiscalPeriodClosing = new FiscalPeriodClosing();
        $result = $this->getForm($fiscalPeriodClosing);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FiscalPeriodClosing::create',
            compact('fiscalPeriodClosing', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fiscal_period_id' => ['required', 'integer'],
            'closing_type' => ['required', 'string', 'max:255'],
            'closing_date' => ['required', 'string', 'max:255'],
            'total_debit' => ['required', 'string', 'max:255'],
            'total_credit' => ['required', 'string', 'max:255'],
            'net_income' => ['required', 'string', 'max:255'],
            'retained_earnings' => ['required', 'string', 'max:255'],
            'snapshot_data' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FiscalPeriodClosing::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FiscalPeriodClosing berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FiscalPeriodClosing.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FiscalPeriodClosing gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $fiscalPeriodClosing = $this->model->findOrFail($id);
        $result = $this->getForm($fiscalPeriodClosing);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FiscalPeriodClosing::edit',
            compact('fiscalPeriodClosing', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'fiscal_period_id' => ['required', 'integer'],
            'closing_type' => ['required', 'string', 'max:255'],
            'closing_date' => ['required', 'string', 'max:255'],
            'total_debit' => ['required', 'string', 'max:255'],
            'total_credit' => ['required', 'string', 'max:255'],
            'net_income' => ['required', 'string', 'max:255'],
            'retained_earnings' => ['required', 'string', 'max:255'],
            'snapshot_data' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $fiscalPeriodClosing = $this->model->findOrFail($id);
            DB::transaction(function () use ($fiscalPeriodClosing, $validated) {
                $fiscalPeriodClosing->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FiscalPeriodClosing berhasil diperbarui.',
                $fiscalPeriodClosing
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui FiscalPeriodClosing.',
                [
                    'exception' => $e,
                    'FiscalPeriodClosing_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FiscalPeriodClosing gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $fiscalPeriodClosing = $this->model->findOrFail($id);

            DB::transaction(function () use ($fiscalPeriodClosing) {
                $fiscalPeriodClosing->delete();
            });

            return $this->successResponse(
                $request,
                'Data FiscalPeriodClosing berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $fiscalPeriodClosing->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FiscalPeriodClosing.',
                [
                    'exception' => $e,
                    'FiscalPeriodClosing_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FiscalPeriodClosing gagal dihapus.'
            );
        }
    }
}
