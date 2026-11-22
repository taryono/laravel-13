<?php

namespace App\Modules\StudentInstallment\Controllers;

use App\DataTables\StudentInstallmentDataTable;
use App\Http\Controllers\MainController;
use Models\StudentInstallment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentInstallmentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentInstallment());
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
        StudentInstallmentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-installment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentInstallment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentInstallment = new StudentInstallment();
        $result = $this->getForm($studentInstallment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentInstallment::create',
            compact('studentInstallment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'integer'],
            'installment_number' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'is_paid' => ['required', 'string', 'max:255'],
            'paid_date' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'payment_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentInstallment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentInstallment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentInstallment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentInstallment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentInstallment = $this->model->findOrFail($id);
        $result = $this->getForm($studentInstallment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentInstallment::edit',
            compact('studentInstallment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'integer'],
            'installment_number' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'is_paid' => ['required', 'string', 'max:255'],
            'paid_date' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'payment_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentInstallment = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentInstallment, $validated) {
                $studentInstallment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentInstallment berhasil diperbarui.',
                $studentInstallment
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentInstallment.',
                [
                    'exception' => $e,
                    'StudentInstallment_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentInstallment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentInstallment = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentInstallment) {
                $studentInstallment->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentInstallment berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentInstallment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentInstallment.',
                [
                    'exception' => $e,
                    'StudentInstallment_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentInstallment gagal dihapus.'
            );
        }
    }
}
