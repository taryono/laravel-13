<?php

namespace App\Modules\PtkpStatus\Controllers;

use App\DataTables\PtkpStatusDataTable;
use App\Http\Controllers\MainController;
use Models\PtkpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PtkpStatusController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PtkpStatus());
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
        PtkpStatusDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /ptkp-status?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PtkpStatus::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ptkpStatus = new PtkpStatus();
        $result = $this->getForm($ptkpStatus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PtkpStatus::create',
            compact('ptkpStatus', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PtkpStatus::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PtkpStatus berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PtkpStatus.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PtkpStatus gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $ptkpStatus = $this->model->findOrFail($id);
        $result = $this->getForm($ptkpStatus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PtkpStatus::edit',
            compact('ptkpStatus', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $ptkpStatus = $this->model->findOrFail($id);
            DB::transaction(function () use ($ptkpStatus, $validated) {
                $ptkpStatus->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PtkpStatus berhasil diperbarui.',
                $ptkpStatus
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PtkpStatus.',
                [
                    'exception' => $e,
                    'PtkpStatus_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PtkpStatus gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $ptkpStatus = $this->model->findOrFail($id);

            DB::transaction(function () use ($ptkpStatus) {
                $ptkpStatus->delete();
            });

            return $this->successResponse(
                $request,
                'Data PtkpStatus berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $ptkpStatus->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PtkpStatus.',
                [
                    'exception' => $e,
                    'PtkpStatus_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PtkpStatus gagal dihapus.'
            );
        }
    }
}
