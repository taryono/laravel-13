<?php

namespace App\Modules\CbtFeature\Controllers;

use App\DataTables\CbtFeatureDataTable;
use App\Http\Controllers\MainController;
use Models\CbtFeature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CbtFeatureController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CbtFeature());
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
        CbtFeatureDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cbt-feature?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CbtFeature::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cbtFeature = new CbtFeature();
        $result = $this->getForm($cbtFeature);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtFeature::create',
            compact('cbtFeature', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'default_enabled' => ['required', 'string', 'max:255'],
            'default_config' => ['required', 'string', 'max:255'],
            'requires_device' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CbtFeature::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtFeature berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CbtFeature.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtFeature gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cbtFeature = $this->model->findOrFail($id);
        $result = $this->getForm($cbtFeature);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtFeature::edit',
            compact('cbtFeature', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'default_enabled' => ['required', 'string', 'max:255'],
            'default_config' => ['required', 'string', 'max:255'],
            'requires_device' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cbtFeature = $this->model->findOrFail($id);
            DB::transaction(function () use ($cbtFeature, $validated) {
                $cbtFeature->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtFeature berhasil diperbarui.',
                $cbtFeature
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CbtFeature.',
                [
                    'exception' => $e,
                    'CbtFeature_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtFeature gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cbtFeature = $this->model->findOrFail($id);

            DB::transaction(function () use ($cbtFeature) {
                $cbtFeature->delete();
            });

            return $this->successResponse(
                $request,
                'Data CbtFeature berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cbtFeature->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CbtFeature.',
                [
                    'exception' => $e,
                    'CbtFeature_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtFeature gagal dihapus.'
            );
        }
    }
}
