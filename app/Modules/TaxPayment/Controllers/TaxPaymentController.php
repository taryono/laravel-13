<?php

namespace App\Modules\TaxPayment\Controllers;

use App\DataTables\TaxPaymentDataTable;
use App\Http\Controllers\MainController;
use Models\TaxPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\ChartOfAccount;
use App\Services\JournalService;
use Throwable;

class TaxPaymentController extends MainController
{
    protected JournalService $journalService;

    public function __construct(JournalService $journalService)
    {
        parent::__construct(new TaxPayment());
        $this->journalService = $journalService;
    }

    public function index(Request $request, TaxPaymentDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::tax_payment.index');
    }

    public function create(): View
    {
        $tax = new TaxPayment();
        $result = $this->getForm($tax);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::tax_payment.edit', compact('tax', 'forms', 'groupedForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tax_code' => 'required|string|max:255|unique:tax_payments,tax_code',
            'tax_type' => 'required|in:pph21,pph23,pph25,ppn,ppnbm,other',
            'tax_period' => 'required|date_format:Y-m',
            'tax_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:pending,paid,overdue,cancelled',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            $tax = DB::transaction(function () use ($validated) {
                return TaxPayment::create($validated);
            });

            return $this->successResponse($request, 'Data pajak berhasil ditambahkan.', $tax);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan pajak.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data pajak gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $tax = $this->model->findOrFail($id);
        $result = $this->getForm($tax);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::tax_payment.edit', compact('tax', 'forms', 'groupedForms'));
    }

    public function update(Request $request, $id)
    {
        $tax = $this->model->findOrFail($id);

        if ($tax->status === 'paid') {
            return $this->errorResponse($request, 'Pajak yang sudah dibayar tidak dapat diubah.');
        }

        $validated = $request->validate([
            'tax_code' => 'required|string|max:255|unique:tax_payments,tax_code,' . $id,
            'tax_type' => 'required|in:pph21,pph23,pph25,ppn,ppnbm,other',
            'tax_period' => 'required|date_format:Y-m',
            'tax_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:pending,paid,overdue,cancelled',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($tax, $validated) {
                $tax->update($validated);
            });

            return $this->successResponse($request, 'Data pajak berhasil diperbarui.', $tax);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui pajak.', ['exception' => $e, 'tax_id' => $id]);
            return $this->errorResponse($request, 'Data pajak gagal diperbarui.');
        }
    }

    public function show($id): View
    {
        $tax = $this->model->with('journalEntry.details.account')->findOrFail($id);
        return view('Finance::tax_payment.show', compact('tax'));
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $tax = $this->model->findOrFail($id);

            if ($tax->status === 'paid') {
                return $this->errorResponse($request, 'Pajak yang sudah dibayar tidak dapat dihapus.');
            }

            DB::transaction(function () use ($tax) {
                $tax->delete();
            });

            return $this->successResponse($request, 'Data pajak berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus pajak.', ['exception' => $e, 'tax_id' => $id]);
            return $this->errorResponse($request, 'Data pajak gagal dihapus.');
        }
    }

    public function pay(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $tax = $this->model->findOrFail($id);

            DB::transaction(function () use ($tax, $request) {
                $tax->update([
                    'status' => 'paid',
                    'paid_amount' => $tax->tax_amount,
                    'payment_date' => now()->format('Y-m-d'),
                    'payment_reference' => $request->get('payment_reference'),
                ]);

                // Create journal entry
                $this->createTaxJournal($tax);
            });

            return $this->successResponse($request, 'Pajak berhasil dibayar.', $tax);
        } catch (Throwable $e) {
            Log::error('Gagal membayar pajak.', ['exception' => $e, 'tax_id' => $id]);
            return $this->errorResponse($request, 'Gagal membayar pajak.');
        }
    }

    private function createTaxJournal(TaxPayment $tax): void
    {
        $accountTax = ChartOfAccount::where('code', '2-2000')->first(); // Hutang Pajak
        $accountCash = ChartOfAccount::where('code', '1-1000')->first(); // Kas

        $journalItems = [];

        // Debit: Hutang Pajak
        $journalItems[] = [
            'chart_of_account_id' => $accountTax->id,
            'debit' => $tax->tax_amount,
            'credit' => 0,
            'description' => 'Pembayaran pajak ' . $tax->tax_type,
        ];

        // Credit: Kas
        $journalItems[] = [
            'chart_of_account_id' => $accountCash->id,
            'debit' => 0,
            'credit' => $tax->tax_amount,
            'description' => 'Pembayaran pajak ' . $tax->tax_type,
        ];

        $journal = $this->journalService->createJournal([
            'journal_date' => $tax->payment_date,
            'description' => 'Pembayaran Pajak ' . $tax->tax_type . ' - ' . $tax->tax_period,
            'reference_number' => $tax->tax_code,
            'items' => $journalItems,
            'notes' => 'Pembayaran pajak #' . $tax->tax_code,
        ], $tax->tax_amount, $tax->tax_amount);

        $tax->update(['journal_entry_id' => $journal->id]);
    }

    public function getOverdue(): JsonResponse
    {
        try {
            $overdueTax = TaxPayment::where('status', 'pending')
                ->where('due_date', '<', now())
                ->get();

            return response()->json(['success' => true, 'data' => $overdueTax]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil pajak jatuh tempo.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function getByPeriod($period): JsonResponse
    {
        try {
            $tax = TaxPayment::where('tax_period', $period)->get();
            return response()->json(['success' => true, 'data' => $tax]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil pajak per periode.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
