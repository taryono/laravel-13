<?php

namespace App\Modules\StudentInvoiceBatch\Controllers;

use App\DataTables\StudentInvoiceBatchDataTable;
use App\Http\Controllers\MainController;
use Models\StudentInvoiceBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentInvoiceBatchController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentInvoiceBatch());
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
        StudentInvoiceBatchDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-invoice-batch?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentInvoiceBatch::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentInvoiceBatch = new StudentInvoiceBatch();
        $result = $this->getForm($studentInvoiceBatch);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentInvoiceBatch::create',
            compact('studentInvoiceBatch', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'invoice_id' => ['required', 'integer'],
            'batch_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentInvoiceBatch::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentInvoiceBatch berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentInvoiceBatch.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentInvoiceBatch gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentInvoiceBatch = $this->model->findOrFail($id);
        $result = $this->getForm($studentInvoiceBatch);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentInvoiceBatch::edit',
            compact('studentInvoiceBatch', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'invoice_id' => ['required', 'integer'],
            'batch_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentInvoiceBatch = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $studentInvoiceBatch,
                $validated
            ) {
                $studentInvoiceBatch->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentInvoiceBatch berhasil diperbarui.',
                $studentInvoiceBatch
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StudentInvoiceBatch.',
                [
                    'exception' => $e,
                    'StudentInvoiceBatch_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentInvoiceBatch gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentInvoiceBatch = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentInvoiceBatch) {
                $studentInvoiceBatch->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentInvoiceBatch berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $studentInvoiceBatch->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentInvoiceBatch.',
                [
                    'exception' => $e,
                    'StudentInvoiceBatch_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentInvoiceBatch gagal dihapus.'
            );
        }
    } 
}
