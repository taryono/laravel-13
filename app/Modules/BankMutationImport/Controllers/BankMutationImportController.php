<?php

namespace App\Modules\BankMutationImport\Controllers;

use App\DataTables\BankMutationImportDataTable;
use App\Http\Controllers\MainController;
use Models\BankMutationImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BankMutationImportController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BankMutationImport());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        BankMutationImportDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /bank-mutation-import?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BankMutationImport::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bankMutationImport = new BankMutationImport();
        $result = $this->getForm($bankMutationImport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BankMutationImport::create',
            compact('bankMutationImport', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'bank_account_id' => ['required', 'integer'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_url' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'string', 'max:255'],
            'total_rows' => ['required', 'string', 'max:255'],
            'success_rows' => ['required', 'string', 'max:255'],
            'failed_rows' => ['required', 'string', 'max:255'],
            'duplicate_rows' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'error_log' => ['required', 'string', 'max:255'],
            'imported_at' => ['required', 'string', 'max:255'],
            'imported_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BankMutationImport::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BankMutationImport berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BankMutationImport.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BankMutationImport gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bankMutationImport = $this->model->findOrFail($id);
        $result = $this->getForm($bankMutationImport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BankMutationImport::edit',
            compact('bankMutationImport', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'bank_account_id' => ['required', 'integer'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_url' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'string', 'max:255'],
            'total_rows' => ['required', 'string', 'max:255'],
            'success_rows' => ['required', 'string', 'max:255'],
            'failed_rows' => ['required', 'string', 'max:255'],
            'duplicate_rows' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'error_log' => ['required', 'string', 'max:255'],
            'imported_at' => ['required', 'string', 'max:255'],
            'imported_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bankMutationImport = $this->model->findOrFail($id);
            DB::transaction(function () use ($bankMutationImport, $validated) {
                $bankMutationImport->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BankMutationImport berhasil diperbarui.',
                $bankMutationImport
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BankMutationImport.',
                [
                    'exception' => $e,
                    'BankMutationImport_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BankMutationImport gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bankMutationImport = $this->model->findOrFail($id);

            DB::transaction(function () use ($bankMutationImport) {
                $bankMutationImport->delete();
            });

            return $this->successResponse(
                $request,
                'Data BankMutationImport berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $bankMutationImport->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BankMutationImport.',
                [
                    'exception' => $e,
                    'BankMutationImport_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BankMutationImport gagal dihapus.'
            );
        }
    }
}
