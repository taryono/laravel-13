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

class AccountController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Account());
    }

    /**
     * Display a listing of accounts.
     */
    public function index(Request $request, AccountDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::account.index');
    }

    /**
     * Show form to create new account.
     */
    public function create(): View
    {
        $account = new Account();
        $categories = AccountCategory::where('is_active', 1)->pluck('name', 'id');
        $parents = Account::where('is_active', 1)
            ->where('account_type', 'header')
            ->pluck('name', 'id');

        $result = $this->getForm($account);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Finance::account.edit',
            compact('account', 'forms', 'groupedForms', 'categories', 'parents')
        );
    }

    /**
     * Store a newly created account.
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
            $account = DB::transaction(function () use ($validated) {
                return Account::create($validated);
            });

            return $this->successResponse(
                $request,
                'Akun berhasil ditambahkan.',
                $account
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
     * Edit account.
     */
    public function edit($id): View
    {
        $account = $this->model->findOrFail($id);
        $categories = AccountCategory::where('is_active', 1)->pluck('name', 'id');
        $parents = Account::where('is_active', 1)
            ->where('account_type', 'header')
            ->where('id', '!=', $id)
            ->pluck('name', 'id');

        $result = $this->getForm($account);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Finance::account.edit',
            compact('account', 'forms', 'groupedForms', 'categories', 'parents')
        );
    }

    /**
     * Update account.
     */
    public function update(Request $request, $id)
    {
        $account = $this->model->findOrFail($id);

        if ($account->is_system) {
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
            DB::transaction(function () use ($account, $validated) {
                $account->update($validated);
            });

            return $this->successResponse(
                $request,
                'Akun berhasil diperbarui.',
                $account
            );

        } catch (Throwable $e) {
            Log::error('Gagal memperbarui akun.', [
                'exception' => $e,
                'account_id' => $id,
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
     * Delete account.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $account = $this->model->findOrFail($id);

            if ($account->is_system) {
                return $this->errorResponse(
                    $request,
                    'Akun sistem tidak dapat dihapus.'
                );
            }

            // Check if account has transactions
            if ($account->journalDetails()->exists()) {
                return $this->errorResponse(
                    $request,
                    'Akun tidak dapat dihapus karena sudah memiliki transaksi.'
                );
            }

            DB::transaction(function () use ($account) {
                $account->delete();
            });

            return $this->successResponse(
                $request,
                'Akun berhasil dihapus.'
            );

        } catch (Throwable $e) {
            Log::error('Gagal menghapus akun.', [
                'exception' => $e,
                'account_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Akun gagal dihapus.'
            );
        }
    }

    /**
     * Get account balance.
     */
    public function getBalance(Request $request, $id): JsonResponse
    {
        try {
            $account = $this->model->findOrFail($id);
            $balance = $account->getBalance();

            return response()->json([
                'success' => true,
                'data' => [
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'balance' => $balance,
                    'normal_balance' => $account->normal_balance,
                ]
            ]);

        } catch (Throwable $e) {
            Log::error('Gagal mengambil saldo akun.', [
                'exception' => $e,
                'account_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil saldo akun.'
            ], 500);
        }
    }
}