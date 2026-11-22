<?php

namespace App\Modules\RkasProgram\Controllers;

use App\DataTables\RkasProgramDataTable;
use App\Http\Controllers\MainController;
use Models\RkasProgram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasProgramController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasProgram());
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
        RkasProgramDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-program?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasProgram::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasProgram = new RkasProgram();
        $result = $this->getForm($rkasProgram);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasProgram::create',
            compact('rkasProgram', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rkas_document_id' => ['required', 'integer'],
            'standard_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'total_budget' => ['required', 'string', 'max:255'],
            'total_realization' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasProgram::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasProgram berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasProgram.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasProgram gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasProgram = $this->model->findOrFail($id);
        $result = $this->getForm($rkasProgram);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasProgram::edit',
            compact('rkasProgram', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rkas_document_id' => ['required', 'integer'],
            'standard_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'total_budget' => ['required', 'string', 'max:255'],
            'total_realization' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasProgram = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasProgram, $validated) {
                $rkasProgram->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasProgram berhasil diperbarui.',
                $rkasProgram
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasProgram.',
                [
                    'exception' => $e,
                    'RkasProgram_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasProgram gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasProgram = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasProgram) {
                $rkasProgram->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasProgram berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasProgram->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasProgram.',
                [
                    'exception' => $e,
                    'RkasProgram_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasProgram gagal dihapus.'
            );
        }
    }
}
