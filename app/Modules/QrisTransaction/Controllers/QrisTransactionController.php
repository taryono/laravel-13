<?php

namespace App\Modules\QrisTransaction\Controllers;

use App\DataTables\QrisTransactionDataTable;
use App\Http\Controllers\MainController;
use Models\QrisTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QrisTransactionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QrisTransaction());
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
        QrisTransactionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /qris-transaction?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'QrisTransaction::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $qrisTransaction = new QrisTransaction();
        $result = $this->getForm($qrisTransaction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QrisTransaction::create',
            compact('qrisTransaction', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'payment_transaction_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'issuer' => ['required', 'string', 'max:255'],
            'acquirer' => ['required', 'string', 'max:255'],
            'qris_string' => ['required', 'string', 'max:255'],
            'qris_url' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'expiry_time' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'raw_response' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return QrisTransaction::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data QrisTransaction berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QrisTransaction.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QrisTransaction gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $qrisTransaction = $this->model->findOrFail($id);
        $result = $this->getForm($qrisTransaction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QrisTransaction::edit',
            compact('qrisTransaction', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'payment_transaction_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'issuer' => ['required', 'string', 'max:255'],
            'acquirer' => ['required', 'string', 'max:255'],
            'qris_string' => ['required', 'string', 'max:255'],
            'qris_url' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'expiry_time' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'raw_response' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $qrisTransaction = $this->model->findOrFail($id);
            DB::transaction(function () use ($qrisTransaction, $validated) {
                $qrisTransaction->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data QrisTransaction berhasil diperbarui.',
                $qrisTransaction
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui QrisTransaction.',
                [
                    'exception' => $e,
                    'QrisTransaction_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QrisTransaction gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $qrisTransaction = $this->model->findOrFail($id);

            DB::transaction(function () use ($qrisTransaction) {
                $qrisTransaction->delete();
            });

            return $this->successResponse(
                $request,
                'Data QrisTransaction berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $qrisTransaction->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QrisTransaction.',
                [
                    'exception' => $e,
                    'QrisTransaction_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QrisTransaction gagal dihapus.'
            );
        }
    }
}
