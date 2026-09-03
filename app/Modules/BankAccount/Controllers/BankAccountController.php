<?php

namespace App\Modules\BankAccount\Controllers;

use App\DataTables\BankAccountDataTable;
use App\Http\Controllers\MainController;
use Models\BankAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;  
use Models\Account; 

class BankAccountController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BankAccount());
    }

    public function index(Request $request, BankAccountDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('BankAccount::index');
    }

    public function create(): View
    {
        $bankAccount = new BankAccount();
        $accounts = Account::where('is_active', 1)
            ->where('account_type', 'detail')
            ->whereHas('category', function($query) {
                $query->where('type', 'aset');
            })
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($bankAccount);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('BankAccount::edit', compact('bankAccount', 'forms', 'groupedForms', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50|unique:bank_accounts,account_number',
            'account_name' => 'required|string|max:255',
            'currency_code' => 'nullable|string|max:10',
            'current_balance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        try {
            $bankAccount = DB::transaction(function () use ($validated) {
                if ($validated['is_default'] ?? false) {
                    BankAccount::where('is_default', 1)->update(['is_default' => 0]);
                }
                return BankAccount::create($validated);
            });

            return $this->successResponse($request, 'Data rekening bank berhasil ditambahkan.', $bankAccount);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan rekening bank.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data rekening bank gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $bankAccount = $this->model->findOrFail($id);
        $accounts = Account::where('is_active', 1)
            ->where('account_type', 'detail')
            ->whereHas('category', function($query) {
                $query->where('type', 'aset');
            })
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($bankAccount);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('BankAccount::edit', compact('bankAccount', 'forms', 'groupedForms', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $bankAccount = $this->model->findOrFail($id);

        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50|unique:bank_accounts,account_number,' . $id,
            'account_name' => 'required|string|max:255',
            'currency_code' => 'nullable|string|max:10',
            'current_balance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($bankAccount, $validated) {
                if ($validated['is_default'] ?? false) {
                    BankAccount::where('is_default', 1)
                        ->where('id', '!=', $bankAccount->id)
                        ->update(['is_default' => 0]);
                }
                $bankAccount->update($validated);
            });

            return $this->successResponse($request, 'Data rekening bank berhasil diperbarui.', $bankAccount);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui rekening bank.', ['exception' => $e, 'bank_account_id' => $id]);
            return $this->errorResponse($request, 'Data rekening bank gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bankAccount = $this->model->findOrFail($id);

            if ($bankAccount->transactions()->exists()) {
                return $this->errorResponse($request, 'Rekening bank tidak dapat dihapus karena masih memiliki transaksi.');
            }

            DB::transaction(function () use ($bankAccount) {
                $bankAccount->delete();
            });

            return $this->successResponse($request, 'Data rekening bank berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus rekening bank.', ['exception' => $e, 'bank_account_id' => $id]);
            return $this->errorResponse($request, 'Data rekening bank gagal dihapus.');
        }
    }

    public function getDefault(): JsonResponse
    {
        try {
            $bankAccount = BankAccount::where('is_default', 1)->first();
            return response()->json(['success' => true, 'data' => $bankAccount]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil rekening default.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function getBalance($id): JsonResponse
    {
        try {
            $bankAccount = $this->model->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => [
                    'account_number' => $bankAccount->account_number,
                    'current_balance' => $bankAccount->current_balance,
                    'currency' => $bankAccount->currency_code,
                ]
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil saldo rekening.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
