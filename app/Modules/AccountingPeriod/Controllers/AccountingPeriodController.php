<?php

namespace App\Modules\AccountingPeriod\Controllers;

use App\DataTables\AccountingPeriodDataTable;
use App\Http\Controllers\MainController;
use Models\AccountingPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AccountingPeriodController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AccountingPeriod());
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
        AccountingPeriodDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /accounting-period?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AccountingPeriod::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $accountingPeriod = new AccountingPeriod();
        $result = $this->getForm($accountingPeriod);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AccountingPeriod::create',
            compact('accountingPeriod', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organizationname' => ['required', 'string', 'max:255'],
            'fiscal_yearyear' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AccountingPeriod::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AccountingPeriod berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AccountingPeriod.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AccountingPeriod gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $accountingPeriod = $this->model->findOrFail($id);
        $result = $this->getForm($accountingPeriod);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AccountingPeriod::edit',
            compact('accountingPeriod', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organizationname' => ['required', 'string', 'max:255'],
            'fiscal_yearyear' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:255'],
        ]);

        try {
            $accountingPeriod = $this->model->findOrFail($id);
            DB::transaction(function () use ($accountingPeriod, $validated) {
                $accountingPeriod->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AccountingPeriod berhasil diperbarui.',
                $accountingPeriod
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AccountingPeriod.',
                [
                    'exception' => $e,
                    'AccountingPeriod_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AccountingPeriod gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $accountingPeriod = $this->model->findOrFail($id);

            DB::transaction(function () use ($accountingPeriod) {
                $accountingPeriod->delete();
            });

            return $this->successResponse(
                $request,
                'Data AccountingPeriod berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $accountingPeriod->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AccountingPeriod.',
                [
                    'exception' => $e,
                    'AccountingPeriod_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AccountingPeriod gagal dihapus.'
            );
        }
    }
}
