<?php

namespace App\Modules\UserPermission\Controllers;

use App\DataTables\UserPermissionDataTable;
use App\Http\Controllers\MainController;
use Models\UserPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class UserPermissionController extends MainController
{

    public function __construct(){
        parent::__construct(new UserPermission());
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
        UserPermissionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /user-permission?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'UserPermission::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {   
        $userPermission = new UserPermission();
        $forms = $this->getForm($userPermission);
        return view(
            'UserPermission::edit',
            compact('userPermission', 'forms')
        );
        return view('UserPermission::create');
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
                return UserPermission::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data UserPermission berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan UserPermission.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data UserPermission gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {   $userPermission = $this->model->findOrFail($id);
        $forms = $this->getForm($userPermission);
        return view(
            'UserPermission::edit',
            compact('userPermission', 'forms')
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
            $userPermission = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $userPermission,
                $validated
            ) {
                $userPermission->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data UserPermission berhasil diperbarui.',
                $userPermission
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui UserPermission.',
                [
                    'exception' => $e,
                    'UserPermission_id' => $userPermission->getKey(),
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data UserPermission gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request,$id): JsonResponse|RedirectResponse {
        try {
             $userPermission = $this->model->findOrFail($id);
            DB::transaction(function () use ($userPermission) {
                $userPermission->delete();
            });

            return $this->successResponse(
                $request,
                'Data UserPermission berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus UserPermission.',
                [
                    'exception' => $e,
                    'UserPermission_id' => $userPermission->getKey(),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data UserPermission gagal dihapus.'
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
            ->route('user-permission.index')
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