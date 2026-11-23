<?php

namespace App\Modules\OpeningBalance\Controllers;

use App\DataTables\OpeningBalanceDataTable;
use App\Http\Controllers\MainController;
use Models\OpeningBalance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class OpeningBalanceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new OpeningBalance());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     *_Normal page request
     *_Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        OpeningBalanceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /opening-balance?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'OpeningBalance::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $openingBalance = new OpeningBalance();
        $result = $this->getForm($openingBalance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'OpeningBalance::create',
            compact('openingBalance', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'fiscal_year_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'cost_center_id' => ['required', 'integer'],
            'debit' => ['required', 'string', 'max:255'],
            'credit' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return OpeningBalance::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data OpeningBalance berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan OpeningBalance.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data OpeningBalance gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $openingBalance = $this->model->findOrFail($id);
        $result = $this->getForm($openingBalance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'OpeningBalance::edit',
            compact('openingBalance', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'fiscal_year_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'cost_center_id' => ['required', 'integer'],
            'debit' => ['required', 'string', 'max:255'],
            'credit' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $openingBalance = $this->model->findOrFail($id);
            DB::transaction(function () use ($openingBalance, $validated) {
                $openingBalance->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data OpeningBalance berhasil diperbarui.',
                $openingBalance
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui OpeningBalance.',
                [
                    'exception' => $e,
                    'OpeningBalance_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data OpeningBalance gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $openingBalance = $this->model->findOrFail($id);

            DB::transaction(function () use ($openingBalance) {
                $openingBalance->delete();
            });

            return $this->successResponse(
                $request,
                'Data OpeningBalance berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $openingBalance->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus OpeningBalance.',
                [
                    'exception' => $e,
                    'OpeningBalance_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data OpeningBalance gagal dihapus.'
            );
        }
    }
}
