<?php

namespace App\Modules\VendorInvoiceLine\Controllers;

use App\DataTables\VendorInvoiceLineDataTable;
use App\Http\Controllers\MainController;
use Models\VendorInvoiceLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class VendorInvoiceLineController extends MainController
{
    public function __construct()
    {
        parent::__construct(new VendorInvoiceLine());
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
        VendorInvoiceLineDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /vendor-invoice-line?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'VendorInvoiceLine::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vendorInvoiceLine = new VendorInvoiceLine();
        $result = $this->getForm($vendorInvoiceLine);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'VendorInvoiceLine::create',
            compact('vendorInvoiceLine', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_invoice_id' => ['required', 'integer'],
            'chart_of_account_id' => ['required', 'integer'],
            'description' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'line_number' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return VendorInvoiceLine::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data VendorInvoiceLine berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan VendorInvoiceLine.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VendorInvoiceLine gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $vendorInvoiceLine = $this->model->findOrFail($id);
        $result = $this->getForm($vendorInvoiceLine);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'VendorInvoiceLine::edit',
            compact('vendorInvoiceLine', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'vendor_invoice_id' => ['required', 'integer'],
            'chart_of_account_id' => ['required', 'integer'],
            'description' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'line_number' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $vendorInvoiceLine = $this->model->findOrFail($id);
            DB::transaction(function () use ($vendorInvoiceLine, $validated) {
                $vendorInvoiceLine->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data VendorInvoiceLine berhasil diperbarui.',
                $vendorInvoiceLine
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui VendorInvoiceLine.',
                [
                    'exception' => $e,
                    'VendorInvoiceLine_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VendorInvoiceLine gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $vendorInvoiceLine = $this->model->findOrFail($id);

            DB::transaction(function () use ($vendorInvoiceLine) {
                $vendorInvoiceLine->delete();
            });

            return $this->successResponse(
                $request,
                'Data VendorInvoiceLine berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $vendorInvoiceLine->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus VendorInvoiceLine.',
                [
                    'exception' => $e,
                    'VendorInvoiceLine_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VendorInvoiceLine gagal dihapus.'
            );
        }
    }
}
