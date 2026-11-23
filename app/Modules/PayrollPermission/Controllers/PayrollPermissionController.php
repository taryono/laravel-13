<?php

namespace App\Modules\PayrollPermission\Controllers;

use App\DataTables\PayrollPermissionDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PayrollPermissionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PayrollPermission());
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
        PayrollPermissionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /payroll-permission?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PayrollPermission::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $payrollPermission = new PayrollPermission();
        $result = $this->getForm($payrollPermission);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PayrollPermission::create',
            compact('payrollPermission', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'module' => ['required', 'string', 'max:255'],
            'permission' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PayrollPermission::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PayrollPermission berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PayrollPermission.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollPermission gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $payrollPermission = $this->model->findOrFail($id);
        $result = $this->getForm($payrollPermission);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PayrollPermission::edit',
            compact('payrollPermission', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'module' => ['required', 'string', 'max:255'],
            'permission' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
        ]);

        try {
            $payrollPermission = $this->model->findOrFail($id);

            DB::transaction(function () use ($payrollPermission, $validated) {
                $payrollPermission->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PayrollPermission berhasil diperbarui.',
                $payrollPermission
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $payrollPermission->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $payrollPermission belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui PayrollPermission.',
                [
                    'exception' => $e,
                    'PayrollPermission_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollPermission gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $payrollPermission = $this->model->findOrFail($id);

            DB::transaction(function () use ($payrollPermission) {
                $payrollPermission->delete();
            });

            return $this->successResponse(
                $request,
                'Data PayrollPermission berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $payrollPermission->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PayrollPermission.',
                [
                    'exception' => $e,
                    'PayrollPermission_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollPermission gagal dihapus.'
            );
        }
    }
}
