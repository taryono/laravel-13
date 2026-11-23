<?php

namespace App\Modules\ClinicMedicine\Controllers;

use App\DataTables\ClinicMedicineDataTable;
use App\Http\Controllers\MainController;
use Models\ClinicMedicine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ClinicMedicineController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ClinicMedicine());
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
        ClinicMedicineDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /clinic-medicine?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ClinicMedicine::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clinicMedicine = new ClinicMedicine();
        $result = $this->getForm($clinicMedicine);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ClinicMedicine::create',
            compact('clinicMedicine', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'stock_qty' => ['required', 'string', 'max:255'],
            'min_stock_qty' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ClinicMedicine::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ClinicMedicine berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ClinicMedicine.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClinicMedicine gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $clinicMedicine = $this->model->findOrFail($id);
        $result = $this->getForm($clinicMedicine);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ClinicMedicine::edit',
            compact('clinicMedicine', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'stock_qty' => ['required', 'string', 'max:255'],
            'min_stock_qty' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $clinicMedicine = $this->model->findOrFail($id);
            DB::transaction(function () use ($clinicMedicine, $validated) {
                $clinicMedicine->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ClinicMedicine berhasil diperbarui.',
                $clinicMedicine
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ClinicMedicine.',
                [
                    'exception' => $e,
                    'ClinicMedicine_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClinicMedicine gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $clinicMedicine = $this->model->findOrFail($id);

            DB::transaction(function () use ($clinicMedicine) {
                $clinicMedicine->delete();
            });

            return $this->successResponse(
                $request,
                'Data ClinicMedicine berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $clinicMedicine->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ClinicMedicine.',
                [
                    'exception' => $e,
                    'ClinicMedicine_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClinicMedicine gagal dihapus.'
            );
        }
    }
}
