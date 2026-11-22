<?php

namespace App\Modules\Customer\Controllers;

use App\DataTables\CustomerDataTable;
use App\Http\Controllers\MainController;
use Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CustomerController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Customer());
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
        CustomerDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /customer?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Customer::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $customer = new Customer();
        $result = $this->getForm($customer);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Customer::create',
            compact('customer', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'tax_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'receivable_account_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Customer::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Customer berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Customer.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Customer gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $customer = $this->model->findOrFail($id);
        $result = $this->getForm($customer);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Customer::edit',
            compact('customer', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'tax_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'receivable_account_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $customer = $this->model->findOrFail($id);
            DB::transaction(function () use ($customer, $validated) {
                $customer->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Customer berhasil diperbarui.',
                $customer
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Customer.',
                [
                    'exception' => $e,
                    'Customer_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Customer gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $customer = $this->model->findOrFail($id);

            DB::transaction(function () use ($customer) {
                $customer->delete();
            });

            return $this->successResponse(
                $request,
                'Data Customer berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $customer->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Customer.',
                [
                    'exception' => $e,
                    'Customer_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Customer gagal dihapus.'
            );
        }
    }
}
