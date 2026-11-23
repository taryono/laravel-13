<?php

namespace App\Modules\EmployeeDeduction\Controllers;

use App\DataTables\EmployeeDeductionDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeDeduction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmployeeDeductionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeDeduction());
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
        EmployeeDeductionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /employee-deduction?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EmployeeDeduction::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employeeDeduction = new EmployeeDeduction();
        $result = $this->getForm($employeeDeduction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeDeduction::create',
            compact('employeeDeduction', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'deduction_number' => ['required', 'string', 'max:255'],
            'deduction_date' => ['required', 'string', 'max:255'],
            'deduction_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'recurring' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'payroll_detail_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EmployeeDeduction::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeDeduction berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EmployeeDeduction.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeDeduction gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $employeeDeduction = $this->model->findOrFail($id);
        $result = $this->getForm($employeeDeduction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeDeduction::edit',
            compact('employeeDeduction', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'deduction_number' => ['required', 'string', 'max:255'],
            'deduction_date' => ['required', 'string', 'max:255'],
            'deduction_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'recurring' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'payroll_detail_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $employeeDeduction = $this->model->findOrFail($id);
            DB::transaction(function () use ($employeeDeduction, $validated) {
                $employeeDeduction->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeDeduction berhasil diperbarui.',
                $employeeDeduction
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui EmployeeDeduction.',
                [
                    'exception' => $e,
                    'EmployeeDeduction_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeDeduction gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employeeDeduction = $this->model->findOrFail($id);

            DB::transaction(function () use ($employeeDeduction) {
                $employeeDeduction->delete();
            });

            return $this->successResponse(
                $request,
                'Data EmployeeDeduction berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $employeeDeduction->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EmployeeDeduction.',
                [
                    'exception' => $e,
                    'EmployeeDeduction_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeDeduction gagal dihapus.'
            );
        }
    }
}
