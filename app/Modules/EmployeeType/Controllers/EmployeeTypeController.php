<?php

namespace App\Modules\EmployeeType\Controllers;

use App\DataTables\EmployeeTypeDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmployeeTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeType());
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
        EmployeeTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /employee-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EmployeeType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employeeType = new EmployeeType();
        $result = $this->getForm($employeeType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeType::create',
            compact('employeeType', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EmployeeType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EmployeeType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $employeeType = $this->model->findOrFail($id);
        $result = $this->getForm($employeeType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeType::edit',
            compact('employeeType', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $employeeType = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $employeeType,
                $validated
            ) {
                $employeeType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeType berhasil diperbarui.',
                $employeeType
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui EmployeeType.',
                [
                    'exception' => $e,
                    'EmployeeType_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employeeType = $this->model->findOrFail($id);

            DB::transaction(function () use ($employeeType) {
                $employeeType->delete();
            });

            return $this->successResponse(
                $request,
                'Data EmployeeType berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $employeeType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EmployeeType.',
                [
                    'exception' => $e,
                    'EmployeeType_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeType gagal dihapus.'
            );
        }
    } 
}
