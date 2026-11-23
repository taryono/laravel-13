<?php

namespace App\Modules\PayrollDetailComponent\Controllers;

use App\DataTables\PayrollDetailComponentDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollDetailComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PayrollDetailComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PayrollDetailComponent());
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
        PayrollDetailComponentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /payroll-detail-component?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PayrollDetailComponent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $payrollDetailComponent = new PayrollDetailComponent();
        $result = $this->getForm($payrollDetailComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PayrollDetailComponent::create',
            compact('payrollDetailComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payroll_detail_id' => ['required', 'integer'],
            'salary_component_id' => ['required', 'integer'],
            'component_code' => ['required', 'string', 'max:255'],
            'component_name' => ['required', 'string', 'max:255'],
            'component_type' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'string', 'max:255'],
            'rate' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'calculation_source' => ['required', 'string', 'max:255'],
            'metadata' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PayrollDetailComponent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PayrollDetailComponent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PayrollDetailComponent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollDetailComponent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $payrollDetailComponent = $this->model->findOrFail($id);
        $result = $this->getForm($payrollDetailComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PayrollDetailComponent::edit',
            compact('payrollDetailComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'payroll_detail_id' => ['required', 'integer'],
            'salary_component_id' => ['required', 'integer'],
            'component_code' => ['required', 'string', 'max:255'],
            'component_name' => ['required', 'string', 'max:255'],
            'component_type' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'string', 'max:255'],
            'rate' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'calculation_source' => ['required', 'string', 'max:255'],
            'metadata' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $payrollDetailComponent = $this->model->findOrFail($id);
            DB::transaction(function () use ($payrollDetailComponent, $validated) {
                $payrollDetailComponent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PayrollDetailComponent berhasil diperbarui.',
                $payrollDetailComponent
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PayrollDetailComponent.',
                [
                    'exception' => $e,
                    'PayrollDetailComponent_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollDetailComponent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $payrollDetailComponent = $this->model->findOrFail($id);

            DB::transaction(function () use ($payrollDetailComponent) {
                $payrollDetailComponent->delete();
            });

            return $this->successResponse(
                $request,
                'Data PayrollDetailComponent berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $payrollDetailComponent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PayrollDetailComponent.',
                [
                    'exception' => $e,
                    'PayrollDetailComponent_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollDetailComponent gagal dihapus.'
            );
        }
    }
}
