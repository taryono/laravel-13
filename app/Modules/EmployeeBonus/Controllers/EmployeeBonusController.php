<?php

namespace App\Modules\EmployeeBonus\Controllers;

use App\DataTables\EmployeeBonusDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeBonus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmployeeBonusController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeBonus());
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
        EmployeeBonusDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /employee-bonus?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EmployeeBonus::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employeeBonus = new EmployeeBonus();
        $result = $this->getForm($employeeBonus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeBonus::create',
            compact('employeeBonus', 'forms', 'groupedForms')
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
            'bonus_number' => ['required', 'string', 'max:255'],
            'bonus_date' => ['required', 'string', 'max:255'],
            'bonus_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'payroll_detail_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EmployeeBonus::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeBonus berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EmployeeBonus.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeBonus gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $employeeBonus = $this->model->findOrFail($id);
        $result = $this->getForm($employeeBonus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeBonus::edit',
            compact('employeeBonus', 'forms', 'groupedForms')
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
            'bonus_number' => ['required', 'string', 'max:255'],
            'bonus_date' => ['required', 'string', 'max:255'],
            'bonus_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'payroll_detail_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $employeeBonus = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $employeeBonus,
                $validated
            ) {
                $employeeBonus->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeBonus berhasil diperbarui.',
                $employeeBonus
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui EmployeeBonus.',
                [
                    'exception' => $e,
                    'EmployeeBonus_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeBonus gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employeeBonus = $this->model->findOrFail($id);

            DB::transaction(function () use ($employeeBonus) {
                $employeeBonus->delete();
            });

            return $this->successResponse(
                $request,
                'Data EmployeeBonus berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $employeeBonus->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EmployeeBonus.',
                [
                    'exception' => $e,
                    'EmployeeBonus_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeBonus gagal dihapus.'
            );
        }
    } 
}
