<?php

namespace App\Modules\PermissionRequest\Controllers;

use App\DataTables\PermissionRequestDataTable;
use App\Http\Controllers\MainController;
use Models\PermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PermissionRequestController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PermissionRequest());
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
        PermissionRequestDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /permission-request?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PermissionRequest::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $permissionRequest = new PermissionRequest();
        $result = $this->getForm($permissionRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PermissionRequest::create',
            compact('permissionRequest', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'student_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'request_type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'rejection_notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PermissionRequest::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PermissionRequest berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PermissionRequest.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PermissionRequest gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $permissionRequest = $this->model->findOrFail($id);
        $result = $this->getForm($permissionRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PermissionRequest::edit',
            compact('permissionRequest', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'student_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'request_type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'rejection_notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $permissionRequest = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $permissionRequest,
                $validated
            ) {
                $permissionRequest->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PermissionRequest berhasil diperbarui.',
                $permissionRequest
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui PermissionRequest.',
                [
                    'exception' => $e,
                    'PermissionRequest_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PermissionRequest gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $permissionRequest = $this->model->findOrFail($id);

            DB::transaction(function () use ($permissionRequest) {
                $permissionRequest->delete();
            });

            return $this->successResponse(
                $request,
                'Data PermissionRequest berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $permissionRequest->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PermissionRequest.',
                [
                    'exception' => $e,
                    'PermissionRequest_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PermissionRequest gagal dihapus.'
            );
        }
    } 
}
