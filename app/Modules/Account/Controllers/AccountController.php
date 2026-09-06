<?php

namespace App\Modules\Account\Controllers;

use App\DataTables\AccountDataTable;
use App\Http\Controllers\MainController;
use Models\Account;
use Models\AccountCategory;
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

        return $dataTable->render('Account::index');
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
            'Account::create',
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
            'Account::edit',
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

    public function getTreeData(Request $request)
{
    $accounts = Account::with(['children' => function($query) {
        $query->where('is_active', true)->orderBy('code');
    }, 'account_category'])
    ->whereNull('parent_id')
    ->orderBy('code')
    ->get();

    return response()->json($this->buildJsTreeData($accounts));
}

private function buildJsTreeData($accounts)
{
    $result = [];
    foreach ($accounts as $account) {
        $node = [
            'id' => (string) $account->id, // ✅ Wajib string!
            'text' => $account->code . ' - ' . $account->name,
            'code' => $account->code,
            'name' => $account->name,
            'type' => $account->account_type, // header atau detail
            'state' => [
                'opened' => false, // Default: collapsed
                'selected' => false
            ],
            'li_attr' => [
                'data-id' => $account->id
            ],
            'a_attr' => [
                'data-code' => $account->code,
                'data-type' => $account->account_type
            ],
            'icon' => $account->account_type == 'header' 
                ? 'fas fa-folder-open text-warning' 
                : 'fas fa-file-invoice text-primary'
        ];

        if ($account->children->isNotEmpty()) {
            $node['children'] = $this->buildJsTreeData($account->children);
        }

        $result[] = $node;
    }
    return $result;
}

public function getDataWithRecursive(Request $request)
{
    $query = "
        WITH RECURSIVE account_tree AS (
            SELECT 
                id, code, name, category_id, parent_id, account_type,
                normal_balance, opening_balance, current_balance,
                is_active, is_system, 0 AS level,
                CAST(code AS CHAR(1000)) AS path
            FROM accounts
            WHERE parent_id IS NULL
            
            UNION ALL
            
            SELECT 
                a.id, a.code, a.name, a.category_id, a.parent_id,
                a.account_type, a.normal_balance, a.opening_balance,
                a.current_balance, a.is_active, a.is_system,
                at.level + 1,
                CONCAT(at.path, ' > ', a.code)
            FROM accounts a
            INNER JOIN account_tree at ON a.parent_id = at.id
        )
        SELECT * FROM account_tree
        ORDER BY path;
    ";

    $accounts = DB::select($query);

    return DataTables::of($accounts)
        ->addColumn('name_display', function ($account) {
            $prefix = str_repeat('&nbsp;&nbsp;&nbsp;', $account->level);
            if ($account->level > 0) {
                $prefix .= '└─ ';
            }
            return $prefix . $account->name;
        })
        ->make(true);
}

public function getDataTree(Request $request): View
    { 
        return view(
            'Account::tree'
        );
    }
}