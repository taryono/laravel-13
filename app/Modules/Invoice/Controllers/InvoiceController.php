<?php

namespace App\Modules\Invoice\Controllers;

use App\DataTables\InvoiceDataTable;
use App\Http\Controllers\MainController;
use Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Models\InvoiceItem;
use Models\ChartOfAccount;
use App\Services\JournalService;

class InvoiceController extends MainController
{
    protected JournalService $journalService;

    public function __construct(JournalService $journalService)
    {
        parent::__construct(new Invoice());
        $this->journalService = $journalService;
    }

    public function index(Request $request, InvoiceDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::invoice.index');
    }

    public function create(): View
    {
        $invoice = new Invoice();
        $accounts = ChartOfAccount::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($invoice);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::invoice.edit', compact('invoice', 'forms', 'groupedForms', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'customer_email' => 'nullable|email|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        try {
            $invoice = DB::transaction(function () use ($validated) {
                $invoice = Invoice::create($validated);

                // Create invoice items
                if (!empty($validated['items'])) {
                    foreach ($validated['items'] as $item) {
                        $item['total_price'] = ($item['quantity'] * $item['unit_price']) - ($item['discount'] ?? 0);
                        $invoice->items()->create($item);
                    }
                }

                // Create journal entry if status is sent or paid
                if (in_array($validated['status'], ['sent', 'paid'])) {
                    $this->createInvoiceJournal($invoice);
                }

                return $invoice;
            });

            return $this->successResponse($request, 'Data invoice berhasil ditambahkan.', $invoice);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan invoice.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data invoice gagal ditambahkan: ' . $e->getMessage());
        }
    }

    private function createInvoiceJournal(Invoice $invoice): void
    {
        // Get account mappings (implementasi sesuai kebutuhan)
        $accountReceivable = ChartOfAccount::where('code', '1-1100')->first(); // Piutang Usaha
        $accountRevenue = ChartOfAccount::where('code', '4-1000')->first(); // Pendapatan
        $accountTax = ChartOfAccount::where('code', '2-2000')->first(); // Hutang Pajak

        $journalItems = [];

        // Debit: Piutang Usaha
        $journalItems[] = [
            'chart_of_account_id' => $accountReceivable->id,
            'debit' => $invoice->total_amount,
            'credit' => 0,
            'description' => 'Piutang invoice ' . $invoice->invoice_number,
        ];

        // Credit: Pendapatan
        $journalItems[] = [
            'chart_of_account_id' => $accountRevenue->id,
            'debit' => 0,
            'credit' => $invoice->subtotal,
            'description' => 'Pendapatan invoice ' . $invoice->invoice_number,
        ];

        // Credit: Hutang Pajak (if tax > 0)
        if ($invoice->tax > 0) {
            $journalItems[] = [
                'chart_of_account_id' => $accountTax->id,
                'debit' => 0,
                'credit' => $invoice->tax,
                'description' => 'Hutang Pajak invoice ' . $invoice->invoice_number,
            ];
        }

        $journal = $this->journalService->createJournal([
            'journal_date' => $invoice->invoice_date,
            'description' => 'Invoice ' . $invoice->invoice_number . '_' . $invoice->customer_name,
            'reference_number' => $invoice->invoice_number,
            'items' => $journalItems,
            'notes' => 'Dibuat dari invoice #' . $invoice->invoice_number,
        ], $invoice->total_amount, $invoice->subtotal + $invoice->tax);

        $invoice->update(['journal_entry_id' => $journal->id]);
    }

    public function edit($id): View
    {
        $invoice = $this->model->with('items')->findOrFail($id);
        $accounts = ChartOfAccount::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($invoice);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::invoice.edit', compact('invoice', 'forms', 'groupedForms', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $invoice = $this->model->findOrFail($id);

        if ($invoice->status === 'paid') {
            return $this->errorResponse($request, 'Invoice yang sudah lunas tidak dapat diubah.');
        }

        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $id,
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'customer_email' => 'nullable|email|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|exists:invoice_items,id',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($invoice, $validated) {
                $invoice->update($validated);

                // Sync items
                $existingIds = [];
                if (!empty($validated['items'])) {
                    foreach ($validated['items'] as $item) {
                        $item['total_price'] = ($item['quantity'] * $item['unit_price']) - ($item['discount'] ?? 0);

                        if (isset($item['id'])) {
                            $invoiceItem = InvoiceItem::find($item['id']);
                            if ($invoiceItem) {
                                $invoiceItem->update($item);
                                $existingIds[] = $item['id'];
                            }
                        } else {
                            $newItem = $invoice->items()->create($item);
                            $existingIds[] = $newItem->id;
                        }
                    }
                }

                // Delete items not in the list
                $invoice->items()->whereNotIn('id', $existingIds)->delete();

                // Update journal entry if exists
                if ($invoice->journal_entry_id) {
                    // Hapus jurnal lama dan buat baru
                    $this->journalService->voidJournal($invoice->journalEntry);
                    $this->createInvoiceJournal($invoice);
                }
            });

            return $this->successResponse($request, 'Data invoice berhasil diperbarui.', $invoice);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui invoice.', ['exception' => $e, 'invoice_id' => $id]);
            return $this->errorResponse($request, 'Data invoice gagal diperbarui.');
        }
    }

    public function show($id): View
    {
        $invoice = $this->model->with(['items', 'journalEntry.details.account'])->findOrFail($id);
        return view('Finance::invoice.show', compact('invoice'));
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $invoice = $this->model->findOrFail($id);

            if ($invoice->status === 'paid') {
                return $this->errorResponse($request, 'Invoice yang sudah lunas tidak dapat dihapus.');
            }

            DB::transaction(function () use ($invoice) {
                // Void journal entry if exists
                if ($invoice->journal_entry_id) {
                    $this->journalService->voidJournal($invoice->journalEntry);
                }

                $invoice->items()->delete();
                $invoice->delete();
            });

            return $this->successResponse($request, 'Data invoice berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus invoice.', ['exception' => $e, 'invoice_id' => $id]);
            return $this->errorResponse($request, 'Data invoice gagal dihapus.');
        }
    }

    public function markAsPaid(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $invoice = $this->model->findOrFail($id);

            DB::transaction(function () use ($invoice, $request) {
                $invoice->update([
                    'status' => 'paid',
                    'paid_amount' => $invoice->total_amount,
                    'remaining_amount' => 0,
                ]);

                // Update journal entry (pembayaran)
                $this->createPaymentJournal($invoice);
            });

            return $this->successResponse($request, 'Invoice berhasil ditandai lunas.', $invoice);
        } catch (Throwable $e) {
            Log::error('Gagal menandai invoice lunas.', ['exception' => $e, 'invoice_id' => $id]);
            return $this->errorResponse($request, 'Gagal menandai invoice lunas.');
        }
    }

    private function createPaymentJournal(Invoice $invoice): void
    {
        $accountCash = ChartOfAccount::where('code', '1-1000')->first(); // Kas
        $accountReceivable = ChartOfAccount::where('code', '1-1100')->first(); // Piutang Usaha

        $journalItems = [];

        // Debit: Kas
        $journalItems[] = [
            'chart_of_account_id' => $accountCash->id,
            'debit' => $invoice->total_amount,
            'credit' => 0,
            'description' => 'Penerimaan pembayaran invoice ' . $invoice->invoice_number,
        ];

        // Credit: Piutang Usaha
        $journalItems[] = [
            'chart_of_account_id' => $accountReceivable->id,
            'debit' => 0,
            'credit' => $invoice->total_amount,
            'description' => 'Pelunasan piutang invoice ' . $invoice->invoice_number,
        ];

        $this->journalService->createJournal([
            'journal_date' => now()->format('Y-m-d'),
            'description' => 'Pembayaran Invoice ' . $invoice->invoice_number . '_' . $invoice->customer_name,
            'reference_number' => 'PAY-' . $invoice->invoice_number,
            'items' => $journalItems,
            'notes' => 'Pembayaran invoice #' . $invoice->invoice_number,
        ], $invoice->total_amount, $invoice->total_amount);
    }

    public function send(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $invoice = $this->model->findOrFail($id);

            if ($invoice->status === 'sent' || $invoice->status === 'paid') {
                return $this->errorResponse($request, 'Invoice sudah dikirim atau sudah lunas.');
            }

            DB::transaction(function () use ($invoice) {
                $invoice->update([
                    'status' => 'sent',
                ]);

                // Create journal entry if not exists
                if (!$invoice->journal_entry_id) {
                    $this->createInvoiceJournal($invoice);
                }

                // Send email (implementasi sesuai kebutuhan)
                // Mail::to($invoice->customer_email)->send(new InvoiceMail($invoice));
            });

            return $this->successResponse($request, 'Invoice berhasil dikirim.', $invoice);
        } catch (Throwable $e) {
            Log::error('Gagal mengirim invoice.', ['exception' => $e, 'invoice_id' => $id]);
            return $this->errorResponse($request, 'Gagal mengirim invoice.');
        }
    }

    public function getOverdue(): JsonResponse
    {
        try {
            $overdueInvoices = Invoice::where('status', 'sent')
                ->where('due_date', '<', now())
                ->get();

            return response()->json(['success' => true, 'data' => $overdueInvoices]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil invoice jatuh tempo.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
