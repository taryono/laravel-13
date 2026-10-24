<?php

namespace App\Modules\ExtracurricularFee\Controllers;

use App\DataTables\ExtracurricularFeeDataTable;
use App\Http\Controllers\MainController;
use Models\ExtracurricularFee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExtracurricularFeeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExtracurricularFee());
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
        ExtracurricularFeeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /extracurricular-fee?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExtracurricularFee::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $extracurricularFee = new ExtracurricularFee();
        $result = $this->getForm($extracurricularFee);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExtracurricularFee::create',
            compact('extracurricularFee', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'extracurricular_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'fee_type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'billing_period' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_refundable' => ['required', 'string', 'max:255'],
            'late_fee' => ['required', 'string', 'max:255'],
            'late_fee_days' => ['required', 'string', 'max:255'],
            'effective_from' => ['required', 'string', 'max:255'],
            'effective_until' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExtracurricularFee::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularFee berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExtracurricularFee.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularFee gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $extracurricularFee = $this->model->findOrFail($id);
        $result = $this->getForm($extracurricularFee);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExtracurricularFee::edit',
            compact('extracurricularFee', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'extracurricular_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'fee_type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'billing_period' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_refundable' => ['required', 'string', 'max:255'],
            'late_fee' => ['required', 'string', 'max:255'],
            'late_fee_days' => ['required', 'string', 'max:255'],
            'effective_from' => ['required', 'string', 'max:255'],
            'effective_until' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $extracurricularFee = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $extracurricularFee,
                $validated
            ) {
                $extracurricularFee->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularFee berhasil diperbarui.',
                $extracurricularFee
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ExtracurricularFee.',
                [
                    'exception' => $e,
                    'ExtracurricularFee_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularFee gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $extracurricularFee = $this->model->findOrFail($id);

            DB::transaction(function () use ($extracurricularFee) {
                $extracurricularFee->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularFee berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $extracurricularFee->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExtracurricularFee.',
                [
                    'exception' => $e,
                    'ExtracurricularFee_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularFee gagal dihapus.'
            );
        }
    } 
}
