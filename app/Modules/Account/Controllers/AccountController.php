<?php

namespace App\Modules\Account\Controllers;

use App\DataTables\AccountDataTable;
use App\Http\Controllers\MainController;
use Models\Account;
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
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        AccountDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /account?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Account::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $account = new Account();
        $result = $this->getForm($account);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Account::create',
            compact('account', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Account::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Account berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Account.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Account gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $account = $this->model->findOrFail($id);
        $result = $this->getForm($account);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Account::edit',
            compact('account', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $account = $this->model->findOrFail($id);
            DB::transaction(function () use ($account, $validated) {
                $account->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Account berhasil diperbarui.',
                $account
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Account.',
                [
                    'exception' => $e,
                    'Account_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Account gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $account = $this->model->findOrFail($id);

            DB::transaction(function () use ($account) {
                $account->delete();
            });

            return $this->successResponse(
                $request,
                'Data Account berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $account->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Account.',
                [
                    'exception' => $e,
                    'Account_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Account gagal dihapus.'
            );
        }
    }
}
