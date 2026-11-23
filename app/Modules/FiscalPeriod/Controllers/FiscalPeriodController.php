<?php

namespace App\Modules\FiscalPeriod\Controllers;

use App\DataTables\FiscalPeriodDataTable;
use App\Http\Controllers\MainController;
use Models\FiscalPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FiscalPeriodController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FiscalPeriod());
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
        FiscalPeriodDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /fiscal-period?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FiscalPeriod::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $fiscalPeriod = new FiscalPeriod();
        $result = $this->getForm($fiscalPeriod);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FiscalPeriod::create',
            compact('fiscalPeriod', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'year' => ['required', 'string', 'max:255'],
            'month' => ['required', 'string', 'max:255'],
            'is_closed' => ['required', 'string', 'max:255'],
            'closed_at' => ['required', 'string', 'max:255'],
            'closed_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FiscalPeriod::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FiscalPeriod berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FiscalPeriod.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FiscalPeriod gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $fiscalPeriod = $this->model->findOrFail($id);
        $result = $this->getForm($fiscalPeriod);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FiscalPeriod::edit',
            compact('fiscalPeriod', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'year' => ['required', 'string', 'max:255'],
            'month' => ['required', 'string', 'max:255'],
            'is_closed' => ['required', 'string', 'max:255'],
            'closed_at' => ['required', 'string', 'max:255'],
            'closed_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $fiscalPeriod = $this->model->findOrFail($id);
            DB::transaction(function () use ($fiscalPeriod, $validated) {
                $fiscalPeriod->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FiscalPeriod berhasil diperbarui.',
                $fiscalPeriod
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui FiscalPeriod.',
                [
                    'exception' => $e,
                    'FiscalPeriod_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FiscalPeriod gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $fiscalPeriod = $this->model->findOrFail($id);

            DB::transaction(function () use ($fiscalPeriod) {
                $fiscalPeriod->delete();
            });

            return $this->successResponse(
                $request,
                'Data FiscalPeriod berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $fiscalPeriod->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FiscalPeriod.',
                [
                    'exception' => $e,
                    'FiscalPeriod_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FiscalPeriod gagal dihapus.'
            );
        }
    }
}
