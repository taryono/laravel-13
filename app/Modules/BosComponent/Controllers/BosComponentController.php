<?php

namespace App\Modules\BosComponent\Controllers;

use App\DataTables\BosComponentDataTable;
use App\Http\Controllers\MainController;
use Models\BosComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BosComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BosComponent());
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
        BosComponentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /bos-component?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BosComponent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bosComponent = new BosComponent();
        $result = $this->getForm($bosComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosComponent::create',
            compact('bosComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'authority' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'component_type' => ['required', 'string', 'max:255'],
            'regulation_code' => ['required', 'string', 'max:255'],
            'regulation_version' => ['required', 'string', 'max:255'],
            'max_percentage' => ['required', 'string', 'max:255'],
            'min_percentage' => ['required', 'string', 'max:255'],
            'calculation_base' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_prohibited' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BosComponent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosComponent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BosComponent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosComponent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bosComponent = $this->model->findOrFail($id);
        $result = $this->getForm($bosComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosComponent::edit',
            compact('bosComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'authority' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'component_type' => ['required', 'string', 'max:255'],
            'regulation_code' => ['required', 'string', 'max:255'],
            'regulation_version' => ['required', 'string', 'max:255'],
            'max_percentage' => ['required', 'string', 'max:255'],
            'min_percentage' => ['required', 'string', 'max:255'],
            'calculation_base' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_prohibited' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bosComponent = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $bosComponent,
                $validated
            ) {
                $bosComponent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosComponent berhasil diperbarui.',
                $bosComponent
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui BosComponent.',
                [
                    'exception' => $e,
                    'BosComponent_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosComponent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bosComponent = $this->model->findOrFail($id);

            DB::transaction(function () use ($bosComponent) {
                $bosComponent->delete();
            });

            return $this->successResponse(
                $request,
                'Data BosComponent berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $bosComponent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BosComponent.',
                [
                    'exception' => $e,
                    'BosComponent_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosComponent gagal dihapus.'
            );
        }
    } 
}
