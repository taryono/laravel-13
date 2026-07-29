<?php

namespace App\Modules\FormType\Controllers;

use App\DataTables\FormTypeDataTable;
use App\Http\Controllers\MainController;
use Models\FormType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FormTypeController extends MainController
{
    public function __construct(){
        parent::__construct(new FormType());
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
        FormTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /form-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FormType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {   $model = new FormType();
        $forms = $this->getForm($model);
        return view(
            'FormType::edit',
            compact('module', 'forms')
        );
        return view('FormType::create');
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
                return FormType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FormType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FormType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FormType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormType $formType): View
    {
        $forms = $this->getForm($formType);
        return view(
            'FormType::edit',
            compact('formType', 'forms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(
        Request $request,
        FormType $formType
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
                $formType,
                $validated
            ) {
                $formType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FormType berhasil diperbarui.',
                $formType
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui FormType.',
                [
                    'exception' => $e,
                    'FormType_id' => $formType->getKey(),
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FormType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(
        Request $request,
        FormType $formType
    ): JsonResponse|RedirectResponse {
        try {

            DB::transaction(function () use ($formType) {
                $formType->delete();
            });

            return $this->successResponse(
                $request,
                'Data FormType berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus FormType.',
                [
                    'exception' => $e,
                    'FormType_id' => $formType->getKey(),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FormType gagal dihapus.'
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
            ->route('form-type.index')
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