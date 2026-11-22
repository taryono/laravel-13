<?php

namespace App\Modules\TaxType\Controllers;

use App\DataTables\TaxTypeDataTable;
use App\Http\Controllers\MainController;
use Models\TaxType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TaxTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TaxType());
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
        TaxTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /tax-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TaxType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $taxType = new TaxType();
        $result = $this->getForm($taxType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TaxType::create',
            compact('taxType', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TaxType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TaxType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TaxType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TaxType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $taxType = $this->model->findOrFail($id);
        $result = $this->getForm($taxType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TaxType::edit',
            compact('taxType', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $taxType = $this->model->findOrFail($id);
            DB::transaction(function () use ($taxType, $validated) {
                $taxType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TaxType berhasil diperbarui.',
                $taxType
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui TaxType.',
                [
                    'exception' => $e,
                    'TaxType_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TaxType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $taxType = $this->model->findOrFail($id);

            DB::transaction(function () use ($taxType) {
                $taxType->delete();
            });

            return $this->successResponse(
                $request,
                'Data TaxType berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $taxType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TaxType.',
                [
                    'exception' => $e,
                    'TaxType_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TaxType gagal dihapus.'
            );
        }
    }
}
