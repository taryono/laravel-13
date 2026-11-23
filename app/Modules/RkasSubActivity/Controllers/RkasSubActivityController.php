<?php

namespace App\Modules\RkasSubActivity\Controllers;

use App\DataTables\RkasSubActivityDataTable;
use App\Http\Controllers\MainController;
use Models\RkasSubActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasSubActivityController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasSubActivity());
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
        RkasSubActivityDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-sub-activity?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasSubActivity::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasSubActivity = new RkasSubActivity();
        $result = $this->getForm($rkasSubActivity);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasSubActivity::create',
            compact('rkasSubActivity', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rkas_activity_id' => ['required', 'integer'],
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
                return RkasSubActivity::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasSubActivity berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasSubActivity.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasSubActivity gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasSubActivity = $this->model->findOrFail($id);
        $result = $this->getForm($rkasSubActivity);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasSubActivity::edit',
            compact('rkasSubActivity', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rkas_activity_id' => ['required', 'integer'],
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
            $rkasSubActivity = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasSubActivity, $validated) {
                $rkasSubActivity->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasSubActivity berhasil diperbarui.',
                $rkasSubActivity
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasSubActivity.',
                [
                    'exception' => $e,
                    'RkasSubActivity_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasSubActivity gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasSubActivity = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasSubActivity) {
                $rkasSubActivity->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasSubActivity berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasSubActivity->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasSubActivity.',
                [
                    'exception' => $e,
                    'RkasSubActivity_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasSubActivity gagal dihapus.'
            );
        }
    }
}
