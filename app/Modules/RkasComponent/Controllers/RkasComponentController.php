<?php

namespace App\Modules\RkasComponent\Controllers;

use App\DataTables\RkasComponentDataTable;
use App\Http\Controllers\MainController;
use Models\RkasComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasComponent());
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
        RkasComponentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-component?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasComponent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasComponent = new RkasComponent();
        $result = $this->getForm($rkasComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasComponent::create',
            compact('rkasComponent', 'forms', 'groupedForms')
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
            'component_type' => ['required', 'string', 'max:255'],
            'account_code' => ['required', 'string', 'max:255'],
            'account_id' => ['required', 'integer'],
            'regulation_code' => ['required', 'string', 'max:255'],
            'regulation_version' => ['required', 'string', 'max:255'],
            'max_percentage' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasComponent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasComponent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasComponent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasComponent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasComponent = $this->model->findOrFail($id);
        $result = $this->getForm($rkasComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasComponent::edit',
            compact('rkasComponent', 'forms', 'groupedForms')
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
            'component_type' => ['required', 'string', 'max:255'],
            'account_code' => ['required', 'string', 'max:255'],
            'account_id' => ['required', 'integer'],
            'regulation_code' => ['required', 'string', 'max:255'],
            'regulation_version' => ['required', 'string', 'max:255'],
            'max_percentage' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasComponent = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasComponent, $validated) {
                $rkasComponent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasComponent berhasil diperbarui.',
                $rkasComponent
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasComponent.',
                [
                    'exception' => $e,
                    'RkasComponent_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasComponent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasComponent = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasComponent) {
                $rkasComponent->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasComponent berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasComponent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasComponent.',
                [
                    'exception' => $e,
                    'RkasComponent_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasComponent gagal dihapus.'
            );
        }
    }
}
