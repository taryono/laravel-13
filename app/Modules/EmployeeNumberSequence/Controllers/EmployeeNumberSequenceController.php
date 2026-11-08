<?php

namespace App\Modules\EmployeeNumberSequence\Controllers;

use App\DataTables\EmployeeNumberSequenceDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeNumberSequence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmployeeNumberSequenceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeNumberSequence());
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
        EmployeeNumberSequenceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /employee-number-sequence?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EmployeeNumberSequence::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employeeNumberSequence = new EmployeeNumberSequence();
        $result = $this->getForm($employeeNumberSequence);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeNumberSequence::create',
            compact('employeeNumberSequence', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'last_number' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EmployeeNumberSequence::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeNumberSequence berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EmployeeNumberSequence.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeNumberSequence gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $employeeNumberSequence = $this->model->findOrFail($id);
        $result = $this->getForm($employeeNumberSequence);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeNumberSequence::edit',
            compact('employeeNumberSequence', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'last_number' => ['required', 'string', 'max:255'],
        ]);

        try {
            $employeeNumberSequence = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $employeeNumberSequence,
                $validated
            ) {
                $employeeNumberSequence->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeNumberSequence berhasil diperbarui.',
                $employeeNumberSequence
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui EmployeeNumberSequence.',
                [
                    'exception' => $e,
                    'EmployeeNumberSequence_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeNumberSequence gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employeeNumberSequence = $this->model->findOrFail($id);

            DB::transaction(function () use ($employeeNumberSequence) {
                $employeeNumberSequence->delete();
            });

            return $this->successResponse(
                $request,
                'Data EmployeeNumberSequence berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $employeeNumberSequence->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EmployeeNumberSequence.',
                [
                    'exception' => $e,
                    'EmployeeNumberSequence_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeNumberSequence gagal dihapus.'
            );
        }
    } 
}
