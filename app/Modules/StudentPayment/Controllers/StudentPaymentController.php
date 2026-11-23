<?php

namespace App\Modules\StudentPayment\Controllers;

use App\DataTables\StudentPaymentDataTable;
use App\Http\Controllers\MainController;
use Models\StudentPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentPaymentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentPayment());
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
        StudentPaymentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-payment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentPayment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentPayment = new StudentPayment();
        $result = $this->getForm($studentPayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentPayment::create',
            compact('studentPayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'payment_proof_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verification_date' => ['required', 'string', 'max:255'],
            'refund_date' => ['required', 'string', 'max:255'],
            'refund_reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentPayment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentPayment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentPayment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentPayment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentPayment = $this->model->findOrFail($id);
        $result = $this->getForm($studentPayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentPayment::edit',
            compact('studentPayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'payment_proof_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verification_date' => ['required', 'string', 'max:255'],
            'refund_date' => ['required', 'string', 'max:255'],
            'refund_reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentPayment = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentPayment, $validated) {
                $studentPayment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentPayment berhasil diperbarui.',
                $studentPayment
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentPayment.',
                [
                    'exception' => $e,
                    'StudentPayment_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentPayment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentPayment = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentPayment) {
                $studentPayment->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentPayment berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentPayment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentPayment.',
                [
                    'exception' => $e,
                    'StudentPayment_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentPayment gagal dihapus.'
            );
        }
    }
}
