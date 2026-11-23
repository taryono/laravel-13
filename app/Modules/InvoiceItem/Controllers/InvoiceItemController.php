<?php

namespace App\Modules\InvoiceItem\Controllers;

use App\DataTables\InvoiceItemDataTable;
use App\Http\Controllers\MainController;
use Models\InvoiceItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\Invoice;
use Throwable;

class InvoiceItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new InvoiceItem());
    }

    public function index(Request $request, InvoiceItemDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::invoice_item.index');
    }

    public function create($invoiceId = null): View
    {
        $item = new InvoiceItem();
        $invoices = Invoice::whereIn('status', ['draft', 'sent'])->pluck('invoice_number', 'id');

        $result = $this->getForm($item);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::invoice_item.edit', compact('item', 'forms', 'groupedForms', 'invoices', 'invoiceId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $item = DB::transaction(function () use ($validated) {
                $item = InvoiceItem::create($validated);

                // Update invoice totals
                $invoice = $item->invoice;
                $invoice->subtotal += $item->total_price;
                $invoice->total_amount = $invoice->subtotal + $invoice->tax - $invoice->discount;
                $invoice->save();

                return $item;
            });

            return $this->successResponse($request, 'Data item invoice berhasil ditambahkan.', $item);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan item invoice.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data item invoice gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $item = $this->model->findOrFail($id);
        $invoices = Invoice::whereIn('status', ['draft', 'sent'])->pluck('invoice_number', 'id');

        $result = $this->getForm($item);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::invoice_item.edit', compact('item', 'forms', 'groupedForms', 'invoices'));
    }

    public function update(Request $request, $id)
    {
        $item = $this->model->findOrFail($id);

        if ($item->invoice->status === 'paid') {
            return $this->errorResponse($request, 'Invoice sudah lunas, tidak dapat diubah.');
        }

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($item, $validated) {
                // Update invoice totals (remove old)
                $invoice = $item->invoice;
                $invoice->subtotal -= $item->total_price;
                $invoice->total_amount = $invoice->subtotal + $invoice->tax - $invoice->discount;
                $invoice->save();

                // Update item
                $item->update($validated);

                // Update invoice totals (add new)
                $invoice->subtotal += $item->total_price;
                $invoice->total_amount = $invoice->subtotal + $invoice->tax - $invoice->discount;
                $invoice->save();
            });

            return $this->successResponse($request, 'Data item invoice berhasil diperbarui.', $item);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui item invoice.', ['exception' => $e, 'item_id' => $id]);
            return $this->errorResponse($request, 'Data item invoice gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $item = $this->model->findOrFail($id);

            if ($item->invoice->status === 'paid') {
                return $this->errorResponse($request, 'Invoice sudah lunas, tidak dapat dihapus.');
            }

            DB::transaction(function () use ($item) {
                $invoice = $item->invoice;
                $invoice->subtotal -= $item->total_price;
                $invoice->total_amount = $invoice->subtotal + $invoice->tax - $invoice->discount;
                $invoice->save();

                $item->delete();
            });

            return $this->successResponse($request, 'Data item invoice berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus item invoice.', ['exception' => $e, 'item_id' => $id]);
            return $this->errorResponse($request, 'Data item invoice gagal dihapus.');
        }
    }

    public function getByInvoice($invoiceId): JsonResponse
    {
        try {
            $items = InvoiceItem::where('invoice_id', $invoiceId)->get();
            return response()->json(['success' => true, 'data' => $items]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil item invoice.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
