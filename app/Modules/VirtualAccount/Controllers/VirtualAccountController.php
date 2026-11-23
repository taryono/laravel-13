<?php

namespace App\Modules\VirtualAccount\Controllers;

use App\DataTables\VirtualAccountDataTable;
use App\Http\Controllers\MainController;
use Models\VirtualAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class VirtualAccountController extends MainController
{
    public function __construct()
    {
        parent::__construct(new VirtualAccount());
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
        VirtualAccountDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /virtual-account?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'VirtualAccount::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $virtualAccount = new VirtualAccount();
        $result = $this->getForm($virtualAccount);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'VirtualAccount::create',
            compact('virtualAccount', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'payment_transaction_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'bank_code' => ['required', 'string', 'max:255'],
            'va_number' => ['required', 'string', 'max:255'],
            'va_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'billing_type' => ['required', 'string', 'max:255'],
            'expiry_time' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'inquiry_text' => ['required', 'string', 'max:255'],
            'payment_text' => ['required', 'string', 'max:255'],
            'raw_response' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return VirtualAccount::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data VirtualAccount berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan VirtualAccount.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VirtualAccount gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $virtualAccount = $this->model->findOrFail($id);
        $result = $this->getForm($virtualAccount);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'VirtualAccount::edit',
            compact('virtualAccount', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'payment_transaction_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'bank_code' => ['required', 'string', 'max:255'],
            'va_number' => ['required', 'string', 'max:255'],
            'va_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'billing_type' => ['required', 'string', 'max:255'],
            'expiry_time' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'inquiry_text' => ['required', 'string', 'max:255'],
            'payment_text' => ['required', 'string', 'max:255'],
            'raw_response' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $virtualAccount = $this->model->findOrFail($id);
            DB::transaction(function () use ($virtualAccount, $validated) {
                $virtualAccount->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data VirtualAccount berhasil diperbarui.',
                $virtualAccount
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui VirtualAccount.',
                [
                    'exception' => $e,
                    'VirtualAccount_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VirtualAccount gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $virtualAccount = $this->model->findOrFail($id);

            DB::transaction(function () use ($virtualAccount) {
                $virtualAccount->delete();
            });

            return $this->successResponse(
                $request,
                'Data VirtualAccount berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $virtualAccount->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus VirtualAccount.',
                [
                    'exception' => $e,
                    'VirtualAccount_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VirtualAccount gagal dihapus.'
            );
        }
    }
}
