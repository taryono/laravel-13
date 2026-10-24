<?php

namespace App\Modules\UnitOfMeasurement\Controllers;

use App\DataTables\UnitOfMeasurementDataTable;
use App\Http\Controllers\MainController;
use Models\UnitOfMeasurement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class UnitOfMeasurementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new UnitOfMeasurement());
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
        UnitOfMeasurementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /unit-of-measurement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'UnitOfMeasurement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $unitOfMeasurement = new UnitOfMeasurement();
        $result = $this->getForm($unitOfMeasurement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'UnitOfMeasurement::create',
            compact('unitOfMeasurement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return UnitOfMeasurement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data UnitOfMeasurement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan UnitOfMeasurement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data UnitOfMeasurement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $unitOfMeasurement = $this->model->findOrFail($id);
        $result = $this->getForm($unitOfMeasurement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'UnitOfMeasurement::edit',
            compact('unitOfMeasurement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $unitOfMeasurement = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $unitOfMeasurement,
                $validated
            ) {
                $unitOfMeasurement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data UnitOfMeasurement berhasil diperbarui.',
                $unitOfMeasurement
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui UnitOfMeasurement.',
                [
                    'exception' => $e,
                    'UnitOfMeasurement_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data UnitOfMeasurement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $unitOfMeasurement = $this->model->findOrFail($id);

            DB::transaction(function () use ($unitOfMeasurement) {
                $unitOfMeasurement->delete();
            });

            return $this->successResponse(
                $request,
                'Data UnitOfMeasurement berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $unitOfMeasurement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus UnitOfMeasurement.',
                [
                    'exception' => $e,
                    'UnitOfMeasurement_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data UnitOfMeasurement gagal dihapus.'
            );
        }
    } 
}
