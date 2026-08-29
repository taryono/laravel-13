<?php

namespace App\Modules\ReconciliationDetail\Controllers;

use App\DataTables\ReconciliationDetailDataTable;
use App\Http\Controllers\MainController;
use Models\ReconciliationDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Models\Reconciliation;
use Models\BankTransaction;
use Models\FiscalYear; 
class FiscalYearController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FiscalYear());
    }

    public function index(Request $request, FiscalYearDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::fiscal_year.index');
    }

    public function create(): View
    {
        $fiscalYear = new FiscalYear();
        $result = $this->getForm($fiscalYear);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::fiscal_year.edit', compact('fiscalYear', 'forms', 'groupedForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100|unique:fiscal_years,year',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_closed' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            $fiscalYear = DB::transaction(function () use ($validated) {
                return FiscalYear::create($validated);
            });

            return $this->successResponse($request, 'Data tahun buku berhasil ditambahkan.', $fiscalYear);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan tahun buku.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data tahun buku gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $fiscalYear = $this->model->findOrFail($id);
        $result = $this->getForm($fiscalYear);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::fiscal_year.edit', compact('fiscalYear', 'forms', 'groupedForms'));
    }

    public function update(Request $request, $id)
    {
        $fiscalYear = $this->model->findOrFail($id);

        if ($fiscalYear->is_closed) {
            return $this->errorResponse($request, 'Tahun buku yang sudah ditutup tidak dapat diubah.');
        }

        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100|unique:fiscal_years,year,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_closed' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($fiscalYear, $validated) {
                $fiscalYear->update($validated);
            });

            return $this->successResponse($request, 'Data tahun buku berhasil diperbarui.', $fiscalYear);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui tahun buku.', ['exception' => $e, 'fiscal_year_id' => $id]);
            return $this->errorResponse($request, 'Data tahun buku gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $fiscalYear = $this->model->findOrFail($id);

            if ($fiscalYear->is_closed) {
                return $this->errorResponse($request, 'Tahun buku yang sudah ditutup tidak dapat dihapus.');
            }

            DB::transaction(function () use ($fiscalYear) {
                $fiscalYear->delete();
            });

            return $this->successResponse($request, 'Data tahun buku berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus tahun buku.', ['exception' => $e, 'fiscal_year_id' => $id]);
            return $this->errorResponse($request, 'Data tahun buku gagal dihapus.');
        }
    }

    public function close(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $fiscalYear = $this->model->findOrFail($id);

            DB::transaction(function () use ($fiscalYear) {
                $fiscalYear->update([
                    'is_closed' => true,
                    'closed_at' => now(),
                ]);
            });

            return $this->successResponse($request, 'Tahun buku berhasil ditutup.', $fiscalYear);
        } catch (Throwable $e) {
            Log::error('Gagal menutup tahun buku.', ['exception' => $e, 'fiscal_year_id' => $id]);
            return $this->errorResponse($request, 'Gagal menutup tahun buku.');
        }
    }

    public function getCurrent(): JsonResponse
    {
        try {
            $fiscalYear = FiscalYear::where('is_closed', 0)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            return response()->json(['success' => true, 'data' => $fiscalYear]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil tahun buku aktif.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}