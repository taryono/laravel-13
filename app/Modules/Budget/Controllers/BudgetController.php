<?php

namespace App\Modules\Budget\Controllers;

use App\DataTables\BudgetDataTable;
use App\Http\Controllers\MainController;
use Models\Budget;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Models\Account;
use Models\Department; 

class BudgetController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Budget());
    }

    public function index(Request $request, BudgetDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::budget.index');
    }

    public function create(): View
    {
        $budget = new Budget();
        $accounts = Account::where('is_active', 1)->orderBy('code')->pluck('name', 'id');
        $departments = Department::where('is_active', 1)->pluck('name', 'id');

        $result = $this->getForm($budget);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::budget.edit', compact('budget', 'forms', 'groupedForms', 'accounts', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'budget_code' => 'required|string|max:255|unique:budgets,budget_code',
            'account_id' => 'required|exists:accounts,id',
            'department_id' => 'nullable|exists:departments,id',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,approved,active,closed',
        ]);

        try {
            $budget = DB::transaction(function () use ($validated) {
                $budget = Budget::create($validated);
                return $budget;
            });

            return $this->successResponse($request, 'Data anggaran berhasil ditambahkan.', $budget);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan anggaran.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data anggaran gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $budget = $this->model->findOrFail($id);
        $accounts = Account::where('is_active', 1)->orderBy('code')->pluck('name', 'id');
        $departments = Department::where('is_active', 1)->pluck('name', 'id');

        $result = $this->getForm($budget);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::budget.edit', compact('budget', 'forms', 'groupedForms', 'accounts', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $budget = $this->model->findOrFail($id);

        if ($budget->status === 'approved') {
            return $this->errorResponse($request, 'Anggaran yang sudah disetujui tidak dapat diubah.');
        }

        $validated = $request->validate([
            'budget_code' => 'required|string|max:255|unique:budgets,budget_code,' . $id,
            'account_id' => 'required|exists:accounts,id',
            'department_id' => 'nullable|exists:departments,id',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,approved,active,closed',
        ]);

        try {
            DB::transaction(function () use ($budget, $validated) {
                $budget->update($validated);
            });

            return $this->successResponse($request, 'Data anggaran berhasil diperbarui.', $budget);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui anggaran.', ['exception' => $e, 'budget_id' => $id]);
            return $this->errorResponse($request, 'Data anggaran gagal diperbarui.');
        }
    }

    public function show($id): View
    {
        $budget = $this->model->with(['account', 'department'])->findOrFail($id);
        return view('Finance::budget.show', compact('budget'));
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $budget = $this->model->findOrFail($id);

            if ($budget->status === 'approved') {
                return $this->errorResponse($request, 'Anggaran yang sudah disetujui tidak dapat dihapus.');
            }

            DB::transaction(function () use ($budget) {
                $budget->delete();
            });

            return $this->successResponse($request, 'Data anggaran berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus anggaran.', ['exception' => $e, 'budget_id' => $id]);
            return $this->errorResponse($request, 'Data anggaran gagal dihapus.');
        }
    }

    public function approve(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $budget = $this->model->findOrFail($id);

            DB::transaction(function () use ($budget) {
                $budget->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);
            });

            return $this->successResponse($request, 'Anggaran berhasil disetujui.', $budget);
        } catch (Throwable $e) {
            Log::error('Gagal menyetujui anggaran.', ['exception' => $e, 'budget_id' => $id]);
            return $this->errorResponse($request, 'Gagal menyetujui anggaran.');
        }
    }

    public function getByYear($year): JsonResponse
    {
        try {
            $budgets = Budget::with(['account', 'department'])
                ->where('year', $year)
                ->get();

            return response()->json(['success' => true, 'data' => $budgets]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil anggaran per tahun.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function getVariance($id): JsonResponse
    {
        try {
            $budget = $this->model->findOrFail($id);
            
            // Calculate actual amount based on journal entries
            $actualAmount = $this->getActualAmount($budget);
            $variance = $budget->amount - $actualAmount;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'budget' => $budget,
                    'actual_amount' => $actualAmount,
                    'variance' => $variance,
                    'variance_percentage' => $budget->amount > 0 ? ($variance / $budget->amount) * 100 : 0,
                ]
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menghitung variance anggaran.', ['exception' => $e, 'budget_id' => $id]);
            return response()->json(['success' => false, 'message' => 'Gagal menghitung variance.'], 500);
        }
    }

    private function getActualAmount(Budget $budget): float
    {
        // Implementasi perhitungan aktual berdasarkan journal entries
        // Sesuaikan dengan kebutuhan
        return 0;
    }
}
