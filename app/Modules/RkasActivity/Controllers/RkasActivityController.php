<?php

namespace App\Modules\RkasActivity\Controllers;

use App\DataTables\RkasActivityDataTable;
use App\Http\Controllers\MainController;
use Models\RkasActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasActivityController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasActivity());
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
        RkasActivityDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-activity?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasActivity::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasActivity = new RkasActivity();
        $result = $this->getForm($rkasActivity);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasActivity::create',
            compact('rkasActivity', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rkas_program_id' => ['required', 'integer'],
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
                return RkasActivity::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasActivity berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasActivity.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasActivity gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasActivity = $this->model->findOrFail($id);
        $result = $this->getForm($rkasActivity);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasActivity::edit',
            compact('rkasActivity', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rkas_program_id' => ['required', 'integer'],
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
            $rkasActivity = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasActivity, $validated) {
                $rkasActivity->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasActivity berhasil diperbarui.',
                $rkasActivity
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasActivity.',
                [
                    'exception' => $e,
                    'RkasActivity_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasActivity gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasActivity = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasActivity) {
                $rkasActivity->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasActivity berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasActivity->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasActivity.',
                [
                    'exception' => $e,
                    'RkasActivity_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasActivity gagal dihapus.'
            );
        }
    }
}
