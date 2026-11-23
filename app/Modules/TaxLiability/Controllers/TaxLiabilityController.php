<?php

namespace App\Modules\TaxLiability\Controllers;

use App\DataTables\TaxLiabilityDataTable;
use App\Http\Controllers\MainController;
use Models\TaxLiability;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TaxLiabilityController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TaxLiability());
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
        TaxLiabilityDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /tax-liability?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TaxLiability::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $taxLiability = new TaxLiability();
        $result = $this->getForm($taxLiability);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TaxLiability::create',
            compact('taxLiability', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'tax_type' => ['required', 'string', 'max:255'],
            'tax_period' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'source_type' => ['required', 'string', 'max:255'],
            'source_id' => ['required', 'integer'],
            'journal_entry_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TaxLiability::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TaxLiability berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TaxLiability.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TaxLiability gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $taxLiability = $this->model->findOrFail($id);
        $result = $this->getForm($taxLiability);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TaxLiability::edit',
            compact('taxLiability', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'tax_type' => ['required', 'string', 'max:255'],
            'tax_period' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'source_type' => ['required', 'string', 'max:255'],
            'source_id' => ['required', 'integer'],
            'journal_entry_id' => ['required', 'integer'],
        ]);

        try {
            $taxLiability = $this->model->findOrFail($id);
            DB::transaction(function () use ($taxLiability, $validated) {
                $taxLiability->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TaxLiability berhasil diperbarui.',
                $taxLiability
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui TaxLiability.',
                [
                    'exception' => $e,
                    'TaxLiability_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TaxLiability gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $taxLiability = $this->model->findOrFail($id);

            DB::transaction(function () use ($taxLiability) {
                $taxLiability->delete();
            });

            return $this->successResponse(
                $request,
                'Data TaxLiability berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $taxLiability->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TaxLiability.',
                [
                    'exception' => $e,
                    'TaxLiability_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TaxLiability gagal dihapus.'
            );
        }
    }
}
