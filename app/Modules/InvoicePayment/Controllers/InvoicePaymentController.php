<?php

namespace App\Modules\InvoicePayment\Controllers;

use App\DataTables\InvoicePaymentDataTable;
use App\Http\Controllers\MainController;
use Models\InvoicePayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class InvoicePaymentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new InvoicePayment());
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
        InvoicePaymentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /invoice-payment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'InvoicePayment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $invoicePayment = new InvoicePayment();
        $result = $this->getForm($invoicePayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'InvoicePayment::create',
            compact('invoicePayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'payment_number' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'bank_account_id' => ['required', 'integer'],
            'reference_number' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return InvoicePayment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data InvoicePayment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan InvoicePayment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InvoicePayment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $invoicePayment = $this->model->findOrFail($id);
        $result = $this->getForm($invoicePayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'InvoicePayment::edit',
            compact('invoicePayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'payment_number' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'bank_account_id' => ['required', 'integer'],
            'reference_number' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $invoicePayment = $this->model->findOrFail($id);
            DB::transaction(function () use ($invoicePayment, $validated) {
                $invoicePayment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data InvoicePayment berhasil diperbarui.',
                $invoicePayment
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui InvoicePayment.',
                [
                    'exception' => $e,
                    'InvoicePayment_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InvoicePayment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $invoicePayment = $this->model->findOrFail($id);

            DB::transaction(function () use ($invoicePayment) {
                $invoicePayment->delete();
            });

            return $this->successResponse(
                $request,
                'Data InvoicePayment berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $invoicePayment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus InvoicePayment.',
                [
                    'exception' => $e,
                    'InvoicePayment_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InvoicePayment gagal dihapus.'
            );
        }
    }
}
