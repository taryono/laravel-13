<?php

namespace App\Modules\BosPeriod\Controllers;

use App\DataTables\BosPeriodDataTable;
use App\Http\Controllers\MainController;
use Models\BosPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BosPeriodController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BosPeriod());
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
        BosPeriodDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /bos-period?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BosPeriod::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bosPeriod = new BosPeriod();
        $result = $this->getForm($bosPeriod);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosPeriod::create',
            compact('bosPeriod', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'authority' => ['required', 'string', 'max:255'],
            'regulation_version' => ['required', 'string', 'max:255'],
            'period_number' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'total_allocation' => ['required', 'string', 'max:255'],
            'total_received' => ['required', 'string', 'max:255'],
            'total_used' => ['required', 'string', 'max:255'],
            'total_remaining' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BosPeriod::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosPeriod berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BosPeriod.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosPeriod gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bosPeriod = $this->model->findOrFail($id);
        $result = $this->getForm($bosPeriod);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosPeriod::edit',
            compact('bosPeriod', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'authority' => ['required', 'string', 'max:255'],
            'regulation_version' => ['required', 'string', 'max:255'],
            'period_number' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'total_allocation' => ['required', 'string', 'max:255'],
            'total_received' => ['required', 'string', 'max:255'],
            'total_used' => ['required', 'string', 'max:255'],
            'total_remaining' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bosPeriod = $this->model->findOrFail($id);
            DB::transaction(function () use ($bosPeriod, $validated) {
                $bosPeriod->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosPeriod berhasil diperbarui.',
                $bosPeriod
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BosPeriod.',
                [
                    'exception' => $e,
                    'BosPeriod_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosPeriod gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bosPeriod = $this->model->findOrFail($id);

            DB::transaction(function () use ($bosPeriod) {
                $bosPeriod->delete();
            });

            return $this->successResponse(
                $request,
                'Data BosPeriod berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $bosPeriod->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BosPeriod.',
                [
                    'exception' => $e,
                    'BosPeriod_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosPeriod gagal dihapus.'
            );
        }
    }
}
