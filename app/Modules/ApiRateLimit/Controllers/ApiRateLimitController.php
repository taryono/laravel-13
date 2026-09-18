<?php

namespace App\Modules\ApiRateLimit\Controllers;

use App\DataTables\ApiRateLimitDataTable;
use App\Http\Controllers\MainController;
use Models\ApiRateLimit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApiRateLimitController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApiRateLimit());
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
        ApiRateLimitDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /api-rate-limit?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApiRateLimit::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $apiRateLimit = new ApiRateLimit();
        $result = $this->getForm($apiRateLimit);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApiRateLimit::create',
            compact('apiRateLimit', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'api_key' => ['required', 'string', 'max:255'],
            'endpoint' => ['required', 'string', 'max:255'],
            'requests' => ['required', 'string', 'max:255'],
            'limit' => ['required', 'string', 'max:255'],
            'reset_at' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApiRateLimit::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApiRateLimit berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApiRateLimit.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApiRateLimit gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $apiRateLimit = $this->model->findOrFail($id);
        $result = $this->getForm($apiRateLimit);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApiRateLimit::edit',
            compact('apiRateLimit', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'api_key' => ['required', 'string', 'max:255'],
            'endpoint' => ['required', 'string', 'max:255'],
            'requests' => ['required', 'string', 'max:255'],
            'limit' => ['required', 'string', 'max:255'],
            'reset_at' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $apiRateLimit = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $apiRateLimit,
                $validated
            ) {
                $apiRateLimit->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApiRateLimit berhasil diperbarui.',
                $apiRateLimit
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ApiRateLimit.',
                [
                    'exception' => $e,
                    'ApiRateLimit_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApiRateLimit gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $apiRateLimit = $this->model->findOrFail($id);

            DB::transaction(function () use ($apiRateLimit) {
                $apiRateLimit->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApiRateLimit berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $apiRateLimit->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApiRateLimit.',
                [
                    'exception' => $e,
                    'ApiRateLimit_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApiRateLimit gagal dihapus.'
            );
        }
    } 
}
