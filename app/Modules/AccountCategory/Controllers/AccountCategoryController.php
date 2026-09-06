<?php

namespace App\Modules\AccountCategory\Controllers;

use App\DataTables\AccountCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\AccountCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 
class AccountCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AccountCategory());
    }

    public function index(Request $request, AccountCategoryDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('AccountCategory::index');
    }

    public function create(): View
    {
        $category = new AccountCategory();
        $result = $this->getForm($category);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('AccountCategory::create', compact('category', 'forms', 'groupedForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:account_categories,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:aset,kewajiban,ekuitas,pendapatan,beban',
            'normal_balance' => 'required|in:debit,kredit',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $category = DB::transaction(function () use ($validated) {
                return AccountCategory::create($validated);
            });

            return $this->successResponse($request, 'Data kategori akun berhasil ditambahkan.', $category);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan kategori akun.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data kategori akun gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $category = $this->model->findOrFail($id);
        $result = $this->getForm($category);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('AccountCategory::edit', compact('category', 'forms', 'groupedForms'));
    }

    public function update(Request $request, $id)
    {
        $category = $this->model->findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:account_categories,code,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:aset,kewajiban,ekuitas,pendapatan,beban',
            'normal_balance' => 'required|in:debit,kredit',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($category, $validated) {
                $category->update($validated);
            });

            return $this->successResponse($request, 'Data kategori akun berhasil diperbarui.', $category);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui kategori akun.', ['exception' => $e, 'category_id' => $id]);
            return $this->errorResponse($request, 'Data kategori akun gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $category = $this->model->findOrFail($id);

            if ($category->accounts()->exists()) {
                return $this->errorResponse($request, 'Kategori tidak dapat dihapus karena masih memiliki akun.');
            }

            DB::transaction(function () use ($category) {
                $category->delete();
            });

            return $this->successResponse($request, 'Data kategori akun berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus kategori akun.', ['exception' => $e, 'category_id' => $id]);
            return $this->errorResponse($request, 'Data kategori akun gagal dihapus.');
        }
    }

    public function getByType($type): JsonResponse
    {
        try {
            $categories = AccountCategory::where('type', $type)
                ->where('is_active', 1)
                ->get(['id', 'code', 'name', 'normal_balance']);

            return response()->json(['success' => true, 'data' => $categories]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil kategori akun.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}