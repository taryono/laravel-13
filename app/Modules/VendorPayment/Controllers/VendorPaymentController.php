<?php

namespace App\Modules\VendorPayment\Controllers;

use App\DataTables\VendorPaymentDataTable;
use App\Http\Controllers\MainController;
use Models\VendorPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class VendorPaymentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new VendorPayment());
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
        VendorPaymentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /vendor-payment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'VendorPayment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vendorPayment = new VendorPayment();
        $result = $this->getForm($vendorPayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'VendorPayment::create',
            compact('vendorPayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'vendor_invoice_id' => ['required', 'integer'],
            'bank_account_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'payment_number' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'pph_amount' => ['required', 'string', 'max:255'],
            'functional_amount' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'posted_journal_entry_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return VendorPayment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data VendorPayment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan VendorPayment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VendorPayment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $vendorPayment = $this->model->findOrFail($id);
        $result = $this->getForm($vendorPayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'VendorPayment::edit',
            compact('vendorPayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'vendor_invoice_id' => ['required', 'integer'],
            'bank_account_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'payment_number' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'pph_amount' => ['required', 'string', 'max:255'],
            'functional_amount' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'posted_journal_entry_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $vendorPayment = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $vendorPayment,
                $validated
            ) {
                $vendorPayment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data VendorPayment berhasil diperbarui.',
                $vendorPayment
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui VendorPayment.',
                [
                    'exception' => $e,
                    'VendorPayment_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VendorPayment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $vendorPayment = $this->model->findOrFail($id);

            DB::transaction(function () use ($vendorPayment) {
                $vendorPayment->delete();
            });

            return $this->successResponse(
                $request,
                'Data VendorPayment berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $vendorPayment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus VendorPayment.',
                [
                    'exception' => $e,
                    'VendorPayment_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VendorPayment gagal dihapus.'
            );
        }
    } 
}
