<?php

namespace App\Modules\EmployeeBankAccount\Controllers;

use App\DataTables\EmployeeBankAccountDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeBankAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmployeeBankAccountController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeBankAccount());
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
        EmployeeBankAccountDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /employee-bank-account?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EmployeeBankAccount::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employeeBankAccount = new EmployeeBankAccount();
        $result = $this->getForm($employeeBankAccount);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeBankAccount::create',
            compact('employeeBankAccount', 'forms', 'groupedForms')
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
            'bank_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EmployeeBankAccount::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeBankAccount berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EmployeeBankAccount.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeBankAccount gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $employeeBankAccount = $this->model->findOrFail($id);
        $result = $this->getForm($employeeBankAccount);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeBankAccount::edit',
            compact('employeeBankAccount', 'forms', 'groupedForms')
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
            'bank_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'string', 'max:255'],
        ]);

        try {
            $employeeBankAccount = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $employeeBankAccount,
                $validated
            ) {
                $employeeBankAccount->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeBankAccount berhasil diperbarui.',
                $employeeBankAccount
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui EmployeeBankAccount.',
                [
                    'exception' => $e,
                    'EmployeeBankAccount_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeBankAccount gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employeeBankAccount = $this->model->findOrFail($id);

            DB::transaction(function () use ($employeeBankAccount) {
                $employeeBankAccount->delete();
            });

            return $this->successResponse(
                $request,
                'Data EmployeeBankAccount berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $employeeBankAccount->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EmployeeBankAccount.',
                [
                    'exception' => $e,
                    'EmployeeBankAccount_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeBankAccount gagal dihapus.'
            );
        }
    } 
}
