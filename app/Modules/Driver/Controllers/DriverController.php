<?php

namespace App\Modules\Driver\Controllers;

use App\DataTables\DriverDataTable;
use App\Http\Controllers\MainController;
use Models\Driver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DriverController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Driver());
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
        DriverDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /driver?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Driver::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $driver = new Driver();
        $result = $this->getForm($driver);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Driver::create',
            compact('driver', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'sim_type' => ['required', 'string', 'max:255'],
            'sim_number' => ['required', 'string', 'max:255'],
            'sim_expiry_date' => ['required', 'string', 'max:255'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Driver::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Driver berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Driver.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Driver gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $driver = $this->model->findOrFail($id);
        $result = $this->getForm($driver);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Driver::edit',
            compact('driver', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'sim_type' => ['required', 'string', 'max:255'],
            'sim_number' => ['required', 'string', 'max:255'],
            'sim_expiry_date' => ['required', 'string', 'max:255'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $driver = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $driver,
                $validated
            ) {
                $driver->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Driver berhasil diperbarui.',
                $driver
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Driver.',
                [
                    'exception' => $e,
                    'Driver_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Driver gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $driver = $this->model->findOrFail($id);

            DB::transaction(function () use ($driver) {
                $driver->delete();
            });

            return $this->successResponse(
                $request,
                'Data Driver berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $driver->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Driver.',
                [
                    'exception' => $e,
                    'Driver_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Driver gagal dihapus.'
            );
        }
    } 
}
