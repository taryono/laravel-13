<?php

namespace App\Modules\Dormitory\Controllers;

use App\DataTables\DormitoryDataTable;
use App\Http\Controllers\MainController;
use Models\Dormitory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DormitoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Dormitory());
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
        DormitoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /dormitory?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Dormitory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dormitory = new Dormitory();
        $result = $this->getForm($dormitory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Dormitory::create',
            compact('dormitory', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organizationname' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'gender_type' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Dormitory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Dormitory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Dormitory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Dormitory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dormitory = $this->model->findOrFail($id);
        $result = $this->getForm($dormitory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Dormitory::edit',
            compact('dormitory', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organizationname' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'gender_type' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $dormitory = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $dormitory,
                $validated
            ) {
                $dormitory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Dormitory berhasil diperbarui.',
                $dormitory
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Dormitory.',
                [
                    'exception' => $e,
                    'Dormitory_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Dormitory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $dormitory = $this->model->findOrFail($id);

            DB::transaction(function () use ($dormitory) {
                $dormitory->delete();
            });

            return $this->successResponse(
                $request,
                'Data Dormitory berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $dormitory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Dormitory.',
                [
                    'exception' => $e,
                    'Dormitory_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Dormitory gagal dihapus.'
            );
        }
    } 
}
