<?php

namespace App\Modules\StudentPaymentBatch\Controllers;

use App\DataTables\StudentPaymentBatchDataTable;
use App\Http\Controllers\MainController;
use Models\StudentPaymentBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentPaymentBatchController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentPaymentBatch());
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
        StudentPaymentBatchDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-payment-batch?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentPaymentBatch::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentPaymentBatch = new StudentPaymentBatch();
        $result = $this->getForm($studentPaymentBatch);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentPaymentBatch::create',
            compact('studentPaymentBatch', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'batch_number' => ['required', 'string', 'max:255'],
            'academic_year_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'description' => ['required', 'string', 'max:255'],
            'total_invoices' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'processed_by' => ['required', 'string', 'max:255'],
            'processed_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentPaymentBatch::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentPaymentBatch berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentPaymentBatch.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentPaymentBatch gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentPaymentBatch = $this->model->findOrFail($id);
        $result = $this->getForm($studentPaymentBatch);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentPaymentBatch::edit',
            compact('studentPaymentBatch', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'batch_number' => ['required', 'string', 'max:255'],
            'academic_year_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'description' => ['required', 'string', 'max:255'],
            'total_invoices' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'processed_by' => ['required', 'string', 'max:255'],
            'processed_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentPaymentBatch = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentPaymentBatch, $validated) {
                $studentPaymentBatch->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentPaymentBatch berhasil diperbarui.',
                $studentPaymentBatch
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentPaymentBatch.',
                [
                    'exception' => $e,
                    'StudentPaymentBatch_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentPaymentBatch gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentPaymentBatch = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentPaymentBatch) {
                $studentPaymentBatch->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentPaymentBatch berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentPaymentBatch->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentPaymentBatch.',
                [
                    'exception' => $e,
                    'StudentPaymentBatch_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentPaymentBatch gagal dihapus.'
            );
        }
    }
}
