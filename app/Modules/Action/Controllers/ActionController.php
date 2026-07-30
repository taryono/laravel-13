<?php

namespace App\Modules\Action\Controllers;

use App\DataTables\ActionDataTable;
use App\Http\Controllers\MainController;
use Models\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ActionController extends MainController
{

    public function __construct(){
        parent::__construct(new Action());
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
        ActionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /action?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Action::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {   
        $action = new Action();
        $forms = $this->getForm($action);
        return view(
            'Action::edit',
            compact('action', 'forms')
        );
        return view('Action::create');
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
                return Action::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Action berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Action.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Action gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {   $action = $this->model->findOrFail($id);
        $forms = $this->getForm($action);
        return view(
            'Action::edit',
            compact('action', 'forms')
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
            $action = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $action,
                $validated
            ) {
                $action->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Action berhasil diperbarui.',
                $action
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui Action.',
                [
                    'exception' => $e,
                    'Action_id' => $action->getKey(),
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Action gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request,$id): JsonResponse|RedirectResponse {
        try {
             $action = $this->model->findOrFail($id);
            DB::transaction(function () use ($action) {
                $action->delete();
            });

            return $this->successResponse(
                $request,
                'Data Action berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus Action.',
                [
                    'exception' => $e,
                    'Action_id' => $action->getKey(),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Action gagal dihapus.'
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
            ->route('action.index')
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