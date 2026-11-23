<?php

namespace App\Modules\EmploymentStatus\Controllers;

use App\DataTables\EmploymentStatusDataTable;
use App\Http\Controllers\MainController;
use Models\EmploymentStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmploymentStatusController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmploymentStatus());
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
        EmploymentStatusDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /employment-status?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EmploymentStatus::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employmentStatus = new EmploymentStatus();
        $result = $this->getForm($employmentStatus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmploymentStatus::create',
            compact('employmentStatus', 'forms', 'groupedForms')
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
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EmploymentStatus::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmploymentStatus berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EmploymentStatus.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmploymentStatus gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $employmentStatus = $this->model->findOrFail($id);
        $result = $this->getForm($employmentStatus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmploymentStatus::edit',
            compact('employmentStatus', 'forms', 'groupedForms')
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
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $employmentStatus = $this->model->findOrFail($id);
            DB::transaction(function () use ($employmentStatus, $validated) {
                $employmentStatus->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmploymentStatus berhasil diperbarui.',
                $employmentStatus
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui EmploymentStatus.',
                [
                    'exception' => $e,
                    'EmploymentStatus_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmploymentStatus gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employmentStatus = $this->model->findOrFail($id);

            DB::transaction(function () use ($employmentStatus) {
                $employmentStatus->delete();
            });

            return $this->successResponse(
                $request,
                'Data EmploymentStatus berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $employmentStatus->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EmploymentStatus.',
                [
                    'exception' => $e,
                    'EmploymentStatus_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmploymentStatus gagal dihapus.'
            );
        }
    }
}
