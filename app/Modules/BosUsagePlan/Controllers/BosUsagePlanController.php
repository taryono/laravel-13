<?php

namespace App\Modules\BosUsagePlan\Controllers;

use App\DataTables\BosUsagePlanDataTable;
use App\Http\Controllers\MainController;
use Models\BosUsagePlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BosUsagePlanController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BosUsagePlan());
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
        BosUsagePlanDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /bos-usage-plan?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BosUsagePlan::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bosUsagePlan = new BosUsagePlan();
        $result = $this->getForm($bosUsagePlan);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosUsagePlan::create',
            compact('bosUsagePlan', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'bos_period_id' => ['required', 'integer'],
            'rkas_document_id' => ['required', 'integer'],
            'rkas_item_id' => ['required', 'integer'],
            'bos_component_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'planned_amount' => ['required', 'string', 'max:255'],
            'execution_month' => ['required', 'string', 'max:255'],
            'execution_quarter' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BosUsagePlan::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosUsagePlan berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BosUsagePlan.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosUsagePlan gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bosUsagePlan = $this->model->findOrFail($id);
        $result = $this->getForm($bosUsagePlan);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosUsagePlan::edit',
            compact('bosUsagePlan', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'bos_period_id' => ['required', 'integer'],
            'rkas_document_id' => ['required', 'integer'],
            'rkas_item_id' => ['required', 'integer'],
            'bos_component_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'planned_amount' => ['required', 'string', 'max:255'],
            'execution_month' => ['required', 'string', 'max:255'],
            'execution_quarter' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bosUsagePlan = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $bosUsagePlan,
                $validated
            ) {
                $bosUsagePlan->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosUsagePlan berhasil diperbarui.',
                $bosUsagePlan
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui BosUsagePlan.',
                [
                    'exception' => $e,
                    'BosUsagePlan_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosUsagePlan gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bosUsagePlan = $this->model->findOrFail($id);

            DB::transaction(function () use ($bosUsagePlan) {
                $bosUsagePlan->delete();
            });

            return $this->successResponse(
                $request,
                'Data BosUsagePlan berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $bosUsagePlan->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BosUsagePlan.',
                [
                    'exception' => $e,
                    'BosUsagePlan_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosUsagePlan gagal dihapus.'
            );
        }
    } 
}
