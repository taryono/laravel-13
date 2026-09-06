<?php

namespace App\Modules\AccountMapping\Controllers;

use App\DataTables\AccountMappingDataTable;
use App\Http\Controllers\MainController;
use Models\AccountMapping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 
use Models\Account; 
class AccountMappingController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AccountMapping());
    }

    public function index(Request $request, AccountMappingDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('AccountMapping::index');
    }

    public function create(): View
    {
        $mapping = new AccountMapping();
        $accounts = Account::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($mapping);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('AccountMapping::edit', compact('mapping', 'forms', 'groupedForms', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source_type' => 'required|in:payroll_component,sales,purchase,expense',
            'source_code' => 'required|string|max:255',
            'debit_account_id' => 'nullable|exists:accounts,id',
            'credit_account_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $mapping = DB::transaction(function () use ($validated) {
                return AccountMapping::create($validated);
            });

            return $this->successResponse($request, 'Data mapping akun berhasil ditambahkan.', $mapping);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan mapping akun.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data mapping akun gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $mapping = $this->model->findOrFail($id);
        $accounts = Account::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($mapping);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('AccountMapping::edit', compact('mapping', 'forms', 'groupedForms', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $mapping = $this->model->findOrFail($id);

        $validated = $request->validate([
            'source_type' => 'required|in:payroll_component,sales,purchase,expense',
            'source_code' => 'required|string|max:255',
            'debit_account_id' => 'nullable|exists:accounts,id',
            'credit_account_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($mapping, $validated) {
                $mapping->update($validated);
            });

            return $this->successResponse($request, 'Data mapping akun berhasil diperbarui.', $mapping);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui mapping akun.', ['exception' => $e, 'mapping_id' => $id]);
            return $this->errorResponse($request, 'Data mapping akun gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $mapping = $this->model->findOrFail($id);
            DB::transaction(function () use ($mapping) {
                $mapping->delete();
            });

            return $this->successResponse($request, 'Data mapping akun berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus mapping akun.', ['exception' => $e, 'mapping_id' => $id]);
            return $this->errorResponse($request, 'Data mapping akun gagal dihapus.');
        }
    }

    public function getBySource($sourceType): JsonResponse
    {
        try {
            $mappings = AccountMapping::where('source_type', $sourceType)
                ->where('is_active', 1)
                ->with(['debitAccount', 'creditAccount'])
                ->get();

            return response()->json(['success' => true, 'data' => $mappings]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil mapping akun.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function toggleActive(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $mapping = $this->model->findOrFail($id);
            $mapping->update(['is_active' => !$mapping->is_active]);

            return $this->successResponse(
                $request,
                'Status mapping akun berhasil diubah.',
                $mapping
            );
        } catch (Throwable $e) {
            Log::error('Gagal mengubah status mapping akun.', ['exception' => $e, 'mapping_id' => $id]);
            return $this->errorResponse($request, 'Gagal mengubah status mapping akun.');
        }
    }
}