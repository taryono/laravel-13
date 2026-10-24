<?php

namespace App\Modules\BosAllocation\Controllers;

use App\DataTables\BosAllocationDataTable;
use App\Http\Controllers\MainController;
use Models\BosAllocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BosAllocationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BosAllocation());
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
        BosAllocationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /bos-allocation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BosAllocation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bosAllocation = new BosAllocation();
        $result = $this->getForm($bosAllocation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosAllocation::create',
            compact('bosAllocation', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'bos_period_id' => ['required', 'integer'],
            'bos_component_id' => ['required', 'integer'],
            'allocation_amount' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BosAllocation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosAllocation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BosAllocation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosAllocation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bosAllocation = $this->model->findOrFail($id);
        $result = $this->getForm($bosAllocation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosAllocation::edit',
            compact('bosAllocation', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'bos_period_id' => ['required', 'integer'],
            'bos_component_id' => ['required', 'integer'],
            'allocation_amount' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bosAllocation = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $bosAllocation,
                $validated
            ) {
                $bosAllocation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosAllocation berhasil diperbarui.',
                $bosAllocation
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui BosAllocation.',
                [
                    'exception' => $e,
                    'BosAllocation_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosAllocation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bosAllocation = $this->model->findOrFail($id);

            DB::transaction(function () use ($bosAllocation) {
                $bosAllocation->delete();
            });

            return $this->successResponse(
                $request,
                'Data BosAllocation berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $bosAllocation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BosAllocation.',
                [
                    'exception' => $e,
                    'BosAllocation_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosAllocation gagal dihapus.'
            );
        }
    } 
}
