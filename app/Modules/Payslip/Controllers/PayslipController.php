<?php

namespace App\Modules\Payslip\Controllers;

use App\DataTables\PayslipDataTable;
use App\Http\Controllers\MainController;
use Models\Payslip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PayslipController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Payslip());
    }

    /**
     * Display a listing of accounts.
     */
    public function index(Request $request, PayslipDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payslip::index');
    }

    /**
     * Show form to create new payslip.
     */
    public function create(): View
    {
        $payslip = new Payslip();
        $categories = PayslipCategory::where('is_active', 1)->pluck('name', 'id');
        $parents = Payslip::where('is_active', 1)
            ->where('account_type', 'header')
            ->pluck('name', 'id');

        $result = $this->getForm($payslip);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Payslip::edit',
            compact('payslip', 'forms', 'groupedForms', 'categories', 'parents')
        );
    }

    /**
     * Store a newly created payslip.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:accounts,code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:account_categories,id',
            'parent_id' => 'nullable|exists:accounts,id',
            'account_type' => 'required|in:header,detail',
            'normal_balance' => 'required|in:debit,kredit',
            'currency_code' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_system' => 'boolean',
        ]);

        try {
            $payslip = DB::transaction(function () use ($validated) {
                return Payslip::create($validated);
            });

            return $this->successResponse(
                $request,
                'Akun berhasil ditambahkan.',
                $payslip
            );
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan akun.', [
                'exception' => $e,
                'data' => $validated,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Akun gagal ditambahkan.'
            );
        }
    }

    /**
     * Edit payslip.
     */
    public function edit($id): View
    {
        $payslip = $this->model->findOrFail($id);
        $categories = PayslipCategory::where('is_active', 1)->pluck('name', 'id');
        $parents = Payslip::where('is_active', 1)
            ->where('account_type', 'header')
            ->where('id', '!=', $id)
            ->pluck('name', 'id');

        $result = $this->getForm($payslip);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Payslip::edit',
            compact('payslip', 'forms', 'groupedForms', 'categories', 'parents')
        );
    }

    /**
     * Update payslip.
     */
    public function update(Request $request, $id)
    {
        $payslip = $this->model->findOrFail($id);

        if ($payslip->is_system) {
            return $this->errorResponse(
                $request,
                'Akun sistem tidak dapat diubah.'
            );
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:accounts,code,' . $id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:account_categories,id',
            'parent_id' => 'nullable|exists:accounts,id',
            'account_type' => 'required|in:header,detail',
            'normal_balance' => 'required|in:debit,kredit',
            'currency_code' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($payslip, $validated) {
                $payslip->update($validated);
            });

            return $this->successResponse(
                $request,
                'Akun berhasil diperbarui.',
                $payslip
            );
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui akun.', [
                'exception' => $e,
                'chart_of_account_id' => $id,
                'data' => $validated,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Akun gagal diperbarui.'
            );
        }
    }

    /**
     * Delete payslip.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $payslip = $this->model->findOrFail($id);

            if ($payslip->is_system) {
                return $this->errorResponse(
                    $request,
                    'Akun sistem tidak dapat dihapus.'
                );
            }

            // Check if payslip has transactions
            if ($payslip->journalDetails()->exists()) {
                return $this->errorResponse(
                    $request,
                    'Akun tidak dapat dihapus karena sudah memiliki transaksi.'
                );
            }

            DB::transaction(function () use ($payslip) {
                $payslip->delete();
            });

            return $this->successResponse(
                $request,
                'Akun berhasil dihapus.'
            );
        } catch (Throwable $e) {
            Log::error('Gagal menghapus akun.', [
                'exception' => $e,
                'chart_of_account_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Akun gagal dihapus.'
            );
        }
    }

    /**
     * Get payslip balance.
     */
    public function getBalance(Request $request, $id): JsonResponse
    {
        try {
            $payslip = $this->model->findOrFail($id);
            $balance = $payslip->getBalance();

            return response()->json([
                'success' => true,
                'data' => [
                    'account_code' => $payslip->code,
                    'account_name' => $payslip->name,
                    'balance' => $balance,
                    'normal_balance' => $payslip->normal_balance,
                ]
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil saldo akun.', [
                'exception' => $e,
                'chart_of_account_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil saldo akun.'
            ], 500);
        }
    }
}
