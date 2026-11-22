<?php

namespace App\Modules\StudentInvoice\Controllers;

use App\DataTables\StudentInvoiceDataTable;
use App\Http\Controllers\MainController;
use Models\StudentInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentInvoiceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentInvoice());
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
        StudentInvoiceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-invoice?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentInvoice::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentInvoice = new StudentInvoice();
        $result = $this->getForm($studentInvoice);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentInvoice::create',
            compact('studentInvoice', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'fee_master_id' => ['required', 'integer'],
            'student_classroom_id' => ['required', 'integer'],
            'invoice_number' => ['required', 'string', 'max:255'],
            'period_id' => ['required', 'integer'],
            'invoice_date' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'penalty_amount' => ['required', 'string', 'max:255'],
            'final_amount' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentInvoice::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentInvoice berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentInvoice.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentInvoice gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentInvoice = $this->model->findOrFail($id);
        $result = $this->getForm($studentInvoice);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentInvoice::edit',
            compact('studentInvoice', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'fee_master_id' => ['required', 'integer'],
            'student_classroom_id' => ['required', 'integer'],
            'invoice_number' => ['required', 'string', 'max:255'],
            'period_id' => ['required', 'integer'],
            'invoice_date' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'penalty_amount' => ['required', 'string', 'max:255'],
            'final_amount' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentInvoice = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentInvoice, $validated) {
                $studentInvoice->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentInvoice berhasil diperbarui.',
                $studentInvoice
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentInvoice.',
                [
                    'exception' => $e,
                    'StudentInvoice_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentInvoice gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentInvoice = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentInvoice) {
                $studentInvoice->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentInvoice berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentInvoice->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentInvoice.',
                [
                    'exception' => $e,
                    'StudentInvoice_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentInvoice gagal dihapus.'
            );
        }
    }
}
