<?php

namespace App\Modules\ActionPermission\Controllers;

use App\DataTables\ActionPermissionDataTable;
use App\Http\Controllers\MainController;
use Models\ActionPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ActionPermissionController extends MainController
{

    public function __construct(){
        parent::__construct(new ActionPermission());
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
        ActionPermissionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /action-permission?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ActionPermission::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {   
        $actionPermission = new ActionPermission();
        $forms = $this->getForm($actionPermission);
        return view(
            'ActionPermission::edit',
            compact('actionPermission', 'forms')
        );
        return view('ActionPermission::create');
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ActionPermission::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ActionPermission berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ActionPermission.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ActionPermission gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {   $actionPermission = $this->model->findOrFail($id);
        $forms = $this->getForm($actionPermission);
        return view(
            'ActionPermission::edit',
            compact('actionPermission', 'forms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request,$id) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $actionPermission = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $actionPermission,
                $validated
            ) {
                $actionPermission->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ActionPermission berhasil diperbarui.',
                $actionPermission
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui ActionPermission.',
                [
                    'exception' => $e,
                    'ActionPermission_id' => $actionPermission->getKey(),
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ActionPermission gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request,$id): JsonResponse|RedirectResponse {
        try {
             $actionPermission = $this->model->findOrFail($id);
            DB::transaction(function () use ($actionPermission) {
                $actionPermission->delete();
            });

            return $this->successResponse(
                $request,
                'Data ActionPermission berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus ActionPermission.',
                [
                    'exception' => $e,
                    'ActionPermission_id' => $actionPermission->getKey(),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ActionPermission gagal dihapus.'
            );
        }
    }

    /**
     * Return successful response.
     */
    protected function successResponse(
        Request $request,
        string $message,
        $data = null
    ): JsonResponse|RedirectResponse {

        if ($this->isAjaxRequest($request)) {

            $response = [
                'success' => true,
                'message' => $message,
            ];

            if ($data !== null) {
                $response['data'] = $data;
            }

            return response()->json($response);
        }

        return redirect()
            ->route('action-permission.index')
            ->with('success', $message);
    }

    /**
     * Return failed response.
     */
    protected function errorResponse(
        Request $request,
        string $message,
        int $status = 500
    ): JsonResponse|RedirectResponse {

        if ($this->isAjaxRequest($request)) {

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $message);
    }

    /**
     * Determine whether the request expects an AJAX response.
     */
    protected function isAjaxRequest(Request $request): bool
    {
        return $request->ajax() || $request->expectsJson();
    }
}