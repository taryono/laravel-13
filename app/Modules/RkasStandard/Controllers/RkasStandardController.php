<?php

namespace App\Modules\RkasStandard\Controllers;

use App\DataTables\RkasStandardDataTable;
use App\Http\Controllers\MainController;
use Models\RkasStandard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasStandardController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasStandard());
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
        RkasStandardDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-standard?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasStandard::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasStandard = new RkasStandard();
        $result = $this->getForm($rkasStandard);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasStandard::create',
            compact('rkasStandard', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasStandard::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasStandard berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasStandard.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasStandard gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasStandard = $this->model->findOrFail($id);
        $result = $this->getForm($rkasStandard);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasStandard::edit',
            compact('rkasStandard', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasStandard = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasStandard, $validated) {
                $rkasStandard->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasStandard berhasil diperbarui.',
                $rkasStandard
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasStandard.',
                [
                    'exception' => $e,
                    'RkasStandard_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasStandard gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasStandard = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasStandard) {
                $rkasStandard->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasStandard berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasStandard->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasStandard.',
                [
                    'exception' => $e,
                    'RkasStandard_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasStandard gagal dihapus.'
            );
        }
    }
}
