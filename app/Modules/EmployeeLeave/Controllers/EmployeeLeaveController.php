<?php

namespace App\Modules\EmployeeLeave\Controllers;

use App\DataTables\EmployeeLeaveDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeLeave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmployeeLeaveController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeLeave());
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
        EmployeeLeaveDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /employee-leave?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EmployeeLeave::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employeeLeave = new EmployeeLeave();
        $result = $this->getForm($employeeLeave);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeLeave::create',
            compact('employeeLeave', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'leave_type' => ['required', 'string', 'max:255'],
            'employee_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EmployeeLeave::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeLeave berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EmployeeLeave.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeLeave gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $employeeLeave = $this->model->findOrFail($id);
        $result = $this->getForm($employeeLeave);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeLeave::edit',
            compact('employeeLeave', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'leave_type' => ['required', 'string', 'max:255'],
            'employee_id' => ['required', 'integer'],
        ]);

        try {
            $employeeLeave = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $employeeLeave,
                $validated
            ) {
                $employeeLeave->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeLeave berhasil diperbarui.',
                $employeeLeave
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $employeeLeave->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $employeeLeave belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui EmployeeLeave.',
                [
                    'exception' => $e,
                    'EmployeeLeave_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeLeave gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employeeLeave = $this->model->findOrFail($id);

            DB::transaction(function () use ($employeeLeave) {
                $employeeLeave->delete();
            });

            return $this->successResponse(
                $request,
                'Data EmployeeLeave berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $employeeLeave->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EmployeeLeave.',
                [
                    'exception' => $e,
                    'EmployeeLeave_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeLeave gagal dihapus.'
            );
        }
    } 
}
