<?php

namespace App\Modules\VendorInvoice\Controllers;

use App\DataTables\VendorInvoiceDataTable;
use App\Http\Controllers\MainController;
use Models\VendorInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class VendorInvoiceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new VendorInvoice());
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
        VendorInvoiceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /vendor-invoice?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'VendorInvoice::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vendorInvoice = new VendorInvoice();
        $result = $this->getForm($vendorInvoice);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'VendorInvoice::create',
            compact('vendorInvoice', 'forms', 'groupedForms')
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
            'fiscal_period_id' => ['required', 'integer'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'invoice_number' => ['required', 'string', 'max:255'],
            'internal_number' => ['required', 'string', 'max:255'],
            'invoice_date' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'received_date' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'tax_base' => ['required', 'string', 'max:255'],
            'ppn_rate' => ['required', 'string', 'max:255'],
            'ppn_amount' => ['required', 'string', 'max:255'],
            'pph_rate' => ['required', 'string', 'max:255'],
            'pph_amount' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'outstanding_amount' => ['required', 'string', 'max:255'],
            'functional_total' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'payment_status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'posted_journal_entry_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return VendorInvoice::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data VendorInvoice berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan VendorInvoice.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VendorInvoice gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $vendorInvoice = $this->model->findOrFail($id);
        $result = $this->getForm($vendorInvoice);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'VendorInvoice::edit',
            compact('vendorInvoice', 'forms', 'groupedForms')
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
            'fiscal_period_id' => ['required', 'integer'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'invoice_number' => ['required', 'string', 'max:255'],
            'internal_number' => ['required', 'string', 'max:255'],
            'invoice_date' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'received_date' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'tax_base' => ['required', 'string', 'max:255'],
            'ppn_rate' => ['required', 'string', 'max:255'],
            'ppn_amount' => ['required', 'string', 'max:255'],
            'pph_rate' => ['required', 'string', 'max:255'],
            'pph_amount' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'outstanding_amount' => ['required', 'string', 'max:255'],
            'functional_total' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'payment_status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'posted_journal_entry_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $vendorInvoice = $this->model->findOrFail($id);
            DB::transaction(function () use ($vendorInvoice, $validated) {
                $vendorInvoice->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data VendorInvoice berhasil diperbarui.',
                $vendorInvoice
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui VendorInvoice.',
                [
                    'exception' => $e,
                    'VendorInvoice_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VendorInvoice gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $vendorInvoice = $this->model->findOrFail($id);

            DB::transaction(function () use ($vendorInvoice) {
                $vendorInvoice->delete();
            });

            return $this->successResponse(
                $request,
                'Data VendorInvoice berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $vendorInvoice->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus VendorInvoice.',
                [
                    'exception' => $e,
                    'VendorInvoice_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VendorInvoice gagal dihapus.'
            );
        }
    }
}
