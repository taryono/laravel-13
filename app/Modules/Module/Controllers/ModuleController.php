<?php

namespace App\Modules\Module\Controllers;

use App\DataTables\ModuleDataTable;
use App\Http\Controllers\MainController;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ModuleController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        ModuleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /module?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Module::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('Module::create');
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
                return Module::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Module berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Module.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Module gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module): View
    {
        $forms = $this->getForm($module);
        return view(
            'Module::edit',
            compact('module', 'forms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(
        Request $request,
        Module $module
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {

            DB::transaction(function () use (
                $module,
                $validated
            ) {
                $module->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Module berhasil diperbarui.',
                $module
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui Module.',
                [
                    'exception' => $e,
                    'Module_id' => $module->getKey(),
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Module gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(
        Request $request,
        Module $module
    ): JsonResponse|RedirectResponse {
        try {

            DB::transaction(function () use ($module) {
                $module->delete();
            });

            return $this->successResponse(
                $request,
                'Data Module berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus Module.',
                [
                    'exception' => $e,
                    'Module_id' => $module->getKey(),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Module gagal dihapus.'
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
            ->route('module.index')
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