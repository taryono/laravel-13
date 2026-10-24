<?php

namespace App\Modules\FeeStructure\Controllers;

use App\DataTables\FeeStructureDataTable;
use App\Http\Controllers\MainController;
use Models\FeeStructure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FeeStructureController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FeeStructure());
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
        FeeStructureDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /fee-structure?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FeeStructure::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $feeStructure = new FeeStructure();
        $result = $this->getForm($feeStructure);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FeeStructure::create',
            compact('feeStructure', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'fee_master_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'due_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FeeStructure::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FeeStructure berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FeeStructure.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FeeStructure gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $feeStructure = $this->model->findOrFail($id);
        $result = $this->getForm($feeStructure);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FeeStructure::edit',
            compact('feeStructure', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'fee_master_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'due_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $feeStructure = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $feeStructure,
                $validated
            ) {
                $feeStructure->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FeeStructure berhasil diperbarui.',
                $feeStructure
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui FeeStructure.',
                [
                    'exception' => $e,
                    'FeeStructure_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FeeStructure gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $feeStructure = $this->model->findOrFail($id);

            DB::transaction(function () use ($feeStructure) {
                $feeStructure->delete();
            });

            return $this->successResponse(
                $request,
                'Data FeeStructure berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $feeStructure->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FeeStructure.',
                [
                    'exception' => $e,
                    'FeeStructure_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FeeStructure gagal dihapus.'
            );
        }
    } 
}
