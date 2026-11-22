<?php

namespace App\Modules\PaymentRequest\Controllers;

use App\DataTables\PaymentRequestDataTable;
use App\Http\Controllers\MainController;
use Models\PaymentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\BankAccount;
use Throwable;

class PaymentRequestController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PaymentRequest());
    }

    public function index(Request $request, PaymentRequestDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::payment_request.index');
    }

    public function create(): View
    {
        $request = new PaymentRequest();
        $bankAccounts = BankAccount::where('is_active', 1)->pluck('bank_name', 'id');

        $result = $this->getForm($request);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::payment_request.edit', compact('request', 'forms', 'groupedForms', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_code' => 'required|string|max:255|unique:payment_requests,request_code',
            'request_type' => 'required|in:payroll,invoice,expense,tax,other',
            'vendor_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'request_date' => 'required|date',
            'bank_chart_of_account_id' => 'nullable|exists:bank_accounts,id',
            'notes' => 'nullable|string',
        ]);

        try {
            $paymentRequest = DB::transaction(function () use ($validated) {
                $validated['requested_by'] = request()->user()?->id;
                return PaymentRequest::create($validated);
            });

            return $this->successResponse($request, 'Data permintaan pembayaran berhasil ditambahkan.', $paymentRequest);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan permintaan pembayaran.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data permintaan pembayaran gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $paymentRequest = $this->model->findOrFail($id);
        $bankAccounts = BankAccount::where('is_active', 1)->pluck('bank_name', 'id');

        $result = $this->getForm($paymentRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::payment_request.edit', compact('paymentRequest', 'forms', 'groupedForms', 'bankAccounts'));
    }

    public function update(Request $request, $id)
    {
        $paymentRequest = $this->model->findOrFail($id);

        if ($paymentRequest->status === 'approved' || $paymentRequest->status === 'paid') {
            return $this->errorResponse($request, 'Permintaan yang sudah disetujui/dibayar tidak dapat diubah.');
        }

        $validated = $request->validate([
            'request_code' => 'required|string|max:255|unique:payment_requests,request_code,' . $id,
            'request_type' => 'required|in:payroll,invoice,expense,tax,other',
            'vendor_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'request_date' => 'required|date',
            'bank_chart_of_account_id' => 'nullable|exists:bank_accounts,id',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($paymentRequest, $validated) {
                $paymentRequest->update($validated);
            });

            return $this->successResponse($request, 'Data permintaan pembayaran berhasil diperbarui.', $paymentRequest);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui permintaan pembayaran.', ['exception' => $e, 'request_id' => $id]);
            return $this->errorResponse($request, 'Data permintaan pembayaran gagal diperbarui.');
        }
    }

    public function show($id): View
    {
        $paymentRequest = $this->model->with(['approvals', 'bankAccount'])->findOrFail($id);
        return view('Finance::payment_request.show', compact('paymentRequest'));
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $paymentRequest = $this->model->findOrFail($id);

            if ($paymentRequest->status === 'approved' || $paymentRequest->status === 'paid') {
                return $this->errorResponse($request, 'Permintaan yang sudah disetujui/dibayar tidak dapat dihapus.');
            }

            DB::transaction(function () use ($paymentRequest) {
                $paymentRequest->approvals()->delete();
                $paymentRequest->delete();
            });

            return $this->successResponse($request, 'Data permintaan pembayaran berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus permintaan pembayaran.', ['exception' => $e, 'request_id' => $id]);
            return $this->errorResponse($request, 'Data permintaan pembayaran gagal dihapus.');
        }
    }

    public function approve(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $paymentRequest = $this->model->findOrFail($id);

            DB::transaction(function () use ($paymentRequest, $request) {
                $paymentRequest->update([
                    'status' => 'approved',
                    'approved_by' => request()->user()?->id,
                    'approved_at' => now(),
                ]);
            });

            return $this->successResponse($request, 'Permintaan pembayaran berhasil disetujui.', $paymentRequest);
        } catch (Throwable $e) {
            Log::error('Gagal menyetujui permintaan pembayaran.', ['exception' => $e, 'request_id' => $id]);
            return $this->errorResponse($request, 'Gagal menyetujui permintaan pembayaran.');
        }
    }

    public function reject(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $paymentRequest = $this->model->findOrFail($id);

            DB::transaction(function () use ($paymentRequest) {
                $paymentRequest->update([
                    'status' => 'rejected',
                    'approved_by' => request()->user()?->id,
                    'approved_at' => now(),
                ]);
            });

            return $this->successResponse($request, 'Permintaan pembayaran berhasil ditolak.', $paymentRequest);
        } catch (Throwable $e) {
            Log::error('Gagal menolak permintaan pembayaran.', ['exception' => $e, 'request_id' => $id]);
            return $this->errorResponse($request, 'Gagal menolak permintaan pembayaran.');
        }
    }

    public function markAsPaid(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $paymentRequest = $this->model->findOrFail($id);

            if ($paymentRequest->status !== 'approved') {
                return $this->errorResponse($request, 'Permintaan harus disetujui terlebih dahulu.');
            }

            DB::transaction(function () use ($paymentRequest) {
                $paymentRequest->update([
                    'status' => 'paid',
                ]);

                // Create journal entry
                $this->createPaymentJournal($paymentRequest);
            });

            return $this->successResponse($request, 'Permintaan pembayaran berhasil ditandai lunas.', $paymentRequest);
        } catch (Throwable $e) {
            Log::error('Gagal menandai permintaan pembayaran lunas.', ['exception' => $e, 'request_id' => $id]);
            return $this->errorResponse($request, 'Gagal menandai permintaan pembayaran lunas.');
        }
    }

    private function createPaymentJournal(PaymentRequest $paymentRequest): void
    {
        // Implementasi journal creation
        // Sesuaikan dengan kebutuhan
    }

    public function getByStatus($status): JsonResponse
    {
        try {
            $requests = PaymentRequest::where('status', $status)
                ->orderBy('request_date', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $requests]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil permintaan pembayaran.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
