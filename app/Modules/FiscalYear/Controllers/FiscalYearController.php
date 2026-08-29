<?php

namespace App\Modules\FiscalYear\Controllers;

use App\DataTables\FiscalYearDataTable;
use App\Http\Controllers\MainController;
use Models\FiscalYear;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FiscalYearController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FiscalYear());
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
        FiscalYearDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /fiscal-year?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FiscalYear::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $fiscalYear = new FiscalYear();
        $result = $this->getForm($fiscalYear);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FiscalYear::create',
            compact('fiscalYear', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FiscalYear::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FiscalYear berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FiscalYear.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FiscalYear gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $fiscalYear = $this->model->findOrFail($id);
        $result = $this->getForm($fiscalYear);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FiscalYear::edit',
            compact('fiscalYear', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'year' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
        ]);

        try {
            $fiscalYear = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $fiscalYear,
                $validated
            ) {
                $fiscalYear->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FiscalYear berhasil diperbarui.',
                $fiscalYear
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $fiscalYear->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $fiscalYear belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui FiscalYear.',
                [
                    'exception' => $e,
                    'FiscalYear_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FiscalYear gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $fiscalYear = $this->model->findOrFail($id);

            DB::transaction(function () use ($fiscalYear) {
                $fiscalYear->delete();
            });

            return $this->successResponse(
                $request,
                'Data FiscalYear berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $fiscalYear->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FiscalYear.',
                [
                    'exception' => $e,
                    'FiscalYear_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FiscalYear gagal dihapus.'
            );
        }
    } 
}
