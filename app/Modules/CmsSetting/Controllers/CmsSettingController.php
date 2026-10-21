<?php

namespace App\Modules\CmsSetting\Controllers;

use App\DataTables\CmsSettingDataTable;
use App\Http\Controllers\MainController;
use Models\CmsSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsSettingController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsSetting());
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
        CmsSettingDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-setting?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsSetting::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsSetting = new CmsSetting();
        $result = $this->getForm($cmsSetting);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsSetting::create',
            compact('cmsSetting', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'group' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsSetting::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsSetting berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsSetting.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsSetting gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsSetting = $this->model->findOrFail($id);
        $result = $this->getForm($cmsSetting);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsSetting::edit',
            compact('cmsSetting', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'group' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsSetting = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cmsSetting,
                $validated
            ) {
                $cmsSetting->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsSetting berhasil diperbarui.',
                $cmsSetting
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CmsSetting.',
                [
                    'exception' => $e,
                    'CmsSetting_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsSetting gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsSetting = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsSetting) {
                $cmsSetting->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsSetting berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cmsSetting->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsSetting.',
                [
                    'exception' => $e,
                    'CmsSetting_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsSetting gagal dihapus.'
            );
        }
    } 
}
