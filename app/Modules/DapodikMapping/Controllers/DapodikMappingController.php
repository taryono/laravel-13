<?php

namespace App\Modules\DapodikMapping\Controllers;

use App\DataTables\DapodikMappingDataTable;
use App\Http\Controllers\MainController;
use Models\DapodikMapping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DapodikMappingController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DapodikMapping());
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
        DapodikMappingDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /dapodik-mapping?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DapodikMapping::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dapodikMapping = new DapodikMapping();
        $result = $this->getForm($dapodikMapping);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DapodikMapping::create',
            compact('dapodikMapping', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'local_table' => ['required', 'string', 'max:255'],
            'local_id' => ['required', 'integer'],
            'dapodik_id' => ['required', 'integer'],
            'dapodik_table' => ['required', 'string', 'max:255'],
            'hash_value' => ['required', 'string', 'max:255'],
            'last_sync_at' => ['required', 'string', 'max:255'],
            'sync_status' => ['required', 'string', 'max:255'],
            'error_message' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DapodikMapping::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DapodikMapping berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DapodikMapping.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DapodikMapping gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dapodikMapping = $this->model->findOrFail($id);
        $result = $this->getForm($dapodikMapping);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DapodikMapping::edit',
            compact('dapodikMapping', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'local_table' => ['required', 'string', 'max:255'],
            'local_id' => ['required', 'integer'],
            'dapodik_id' => ['required', 'integer'],
            'dapodik_table' => ['required', 'string', 'max:255'],
            'hash_value' => ['required', 'string', 'max:255'],
            'last_sync_at' => ['required', 'string', 'max:255'],
            'sync_status' => ['required', 'string', 'max:255'],
            'error_message' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $dapodikMapping = $this->model->findOrFail($id);
            DB::transaction(function () use ($dapodikMapping, $validated) {
                $dapodikMapping->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DapodikMapping berhasil diperbarui.',
                $dapodikMapping
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DapodikMapping.',
                [
                    'exception' => $e,
                    'DapodikMapping_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DapodikMapping gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $dapodikMapping = $this->model->findOrFail($id);

            DB::transaction(function () use ($dapodikMapping) {
                $dapodikMapping->delete();
            });

            return $this->successResponse(
                $request,
                'Data DapodikMapping berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $dapodikMapping->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DapodikMapping.',
                [
                    'exception' => $e,
                    'DapodikMapping_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DapodikMapping gagal dihapus.'
            );
        }
    }
}
