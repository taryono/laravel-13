<?php

namespace App\Modules\SalaryStructureComponent\Controllers;

use App\DataTables\SalaryStructureComponentDataTable;
use App\Http\Controllers\MainController;
use Models\SalaryStructureComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SalaryStructureComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SalaryStructureComponent());
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
        SalaryStructureComponentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /salary-structure-component?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SalaryStructureComponent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $salaryStructureComponent = new SalaryStructureComponent();
        $result = $this->getForm($salaryStructureComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SalaryStructureComponent::create',
            compact('salaryStructureComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'salary_structurename' => ['required', 'string', 'max:255'],
            'salary_componentname' => ['required', 'string', 'max:255'],
            'calculation_type' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'calculation_base' => ['required', 'string', 'max:255'],
            'calculation_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return SalaryStructureComponent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SalaryStructureComponent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SalaryStructureComponent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SalaryStructureComponent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $salaryStructureComponent = $this->model->findOrFail($id);
        $result = $this->getForm($salaryStructureComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SalaryStructureComponent::edit',
            compact('salaryStructureComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'salary_structurename' => ['required', 'string', 'max:255'],
            'salary_componentname' => ['required', 'string', 'max:255'],
            'calculation_type' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'calculation_base' => ['required', 'string', 'max:255'],
            'calculation_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $salaryStructureComponent = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $salaryStructureComponent,
                $validated
            ) {
                $salaryStructureComponent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SalaryStructureComponent berhasil diperbarui.',
                $salaryStructureComponent
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui SalaryStructureComponent.',
                [
                    'exception' => $e,
                    'SalaryStructureComponent_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SalaryStructureComponent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $salaryStructureComponent = $this->model->findOrFail($id);

            DB::transaction(function () use ($salaryStructureComponent) {
                $salaryStructureComponent->delete();
            });

            return $this->successResponse(
                $request,
                'Data SalaryStructureComponent berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $salaryStructureComponent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SalaryStructureComponent.',
                [
                    'exception' => $e,
                    'SalaryStructureComponent_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SalaryStructureComponent gagal dihapus.'
            );
        }
    } 
}
