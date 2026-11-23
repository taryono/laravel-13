<?php

namespace App\Modules\RkasSource\Controllers;

use App\DataTables\RkasSourceDataTable;
use App\Http\Controllers\MainController;
use Models\RkasSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasSourceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasSource());
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
        RkasSourceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-source?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasSource::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasSource = new RkasSource();
        $result = $this->getForm($rkasSource);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasSource::create',
            compact('rkasSource', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'source_type' => ['required', 'string', 'max:255'],
            'authority' => ['required', 'string', 'max:255'],
            'account_id' => ['required', 'integer'],
            'allocation_amount' => ['required', 'string', 'max:255'],
            'regulation_version' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasSource::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasSource berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasSource.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasSource gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasSource = $this->model->findOrFail($id);
        $result = $this->getForm($rkasSource);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasSource::edit',
            compact('rkasSource', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'source_type' => ['required', 'string', 'max:255'],
            'authority' => ['required', 'string', 'max:255'],
            'account_id' => ['required', 'integer'],
            'allocation_amount' => ['required', 'string', 'max:255'],
            'regulation_version' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasSource = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasSource, $validated) {
                $rkasSource->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasSource berhasil diperbarui.',
                $rkasSource
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasSource.',
                [
                    'exception' => $e,
                    'RkasSource_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasSource gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasSource = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasSource) {
                $rkasSource->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasSource berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasSource->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasSource.',
                [
                    'exception' => $e,
                    'RkasSource_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasSource gagal dihapus.'
            );
        }
    }
}
