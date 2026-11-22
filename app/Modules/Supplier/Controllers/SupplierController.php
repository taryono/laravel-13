<?php

namespace App\Modules\Supplier\Controllers;

use App\DataTables\SupplierDataTable;
use App\Http\Controllers\MainController;
use Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SupplierController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Supplier());
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
        SupplierDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /supplier?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Supplier::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $supplier = new Supplier();
        $result = $this->getForm($supplier);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Supplier::create',
            compact('supplier', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Supplier::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Supplier berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Supplier.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Supplier gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $supplier = $this->model->findOrFail($id);
        $result = $this->getForm($supplier);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Supplier::edit',
            compact('supplier', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
        ]);

        try {
            $supplier = $this->model->findOrFail($id);
            DB::transaction(function () use ($supplier, $validated) {
                $supplier->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Supplier berhasil diperbarui.',
                $supplier
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Supplier.',
                [
                    'exception' => $e,
                    'Supplier_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Supplier gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $supplier = $this->model->findOrFail($id);

            DB::transaction(function () use ($supplier) {
                $supplier->delete();
            });

            return $this->successResponse(
                $request,
                'Data Supplier berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $supplier->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Supplier.',
                [
                    'exception' => $e,
                    'Supplier_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Supplier gagal dihapus.'
            );
        }
    }
}
