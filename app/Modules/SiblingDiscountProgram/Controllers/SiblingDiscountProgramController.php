<?php

namespace App\Modules\SiblingDiscountProgram\Controllers;

use App\DataTables\SiblingDiscountProgramDataTable;
use App\Http\Controllers\MainController;
use Models\SiblingDiscountProgram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SiblingDiscountProgramController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SiblingDiscountProgram());
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
        SiblingDiscountProgramDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /sibling-discount-program?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SiblingDiscountProgram::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $siblingDiscountProgram = new SiblingDiscountProgram();
        $result = $this->getForm($siblingDiscountProgram);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SiblingDiscountProgram::create',
            compact('siblingDiscountProgram', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'min_sibling_count' => ['required', 'string', 'max:255'],
            'calculation_basis' => ['required', 'string', 'max:255'],
            'allocation_method' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return SiblingDiscountProgram::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountProgram berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SiblingDiscountProgram.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountProgram gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $siblingDiscountProgram = $this->model->findOrFail($id);
        $result = $this->getForm($siblingDiscountProgram);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SiblingDiscountProgram::edit',
            compact('siblingDiscountProgram', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'min_sibling_count' => ['required', 'string', 'max:255'],
            'calculation_basis' => ['required', 'string', 'max:255'],
            'allocation_method' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $siblingDiscountProgram = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $siblingDiscountProgram,
                $validated
            ) {
                $siblingDiscountProgram->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountProgram berhasil diperbarui.',
                $siblingDiscountProgram
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui SiblingDiscountProgram.',
                [
                    'exception' => $e,
                    'SiblingDiscountProgram_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountProgram gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $siblingDiscountProgram = $this->model->findOrFail($id);

            DB::transaction(function () use ($siblingDiscountProgram) {
                $siblingDiscountProgram->delete();
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountProgram berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $siblingDiscountProgram->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SiblingDiscountProgram.',
                [
                    'exception' => $e,
                    'SiblingDiscountProgram_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountProgram gagal dihapus.'
            );
        }
    } 
}
