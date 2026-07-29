<?php

namespace App\Modules\Competence\Controllers;

use App\DataTables\CompetenceDataTable;
use App\Http\Controllers\MainController;
use Models\Competence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CompetenceController extends MainController
{

    public function __construct(){
        parent::__construct(new Competence());
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
        CompetenceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /competence?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Competence::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {   $model = new Competence();
        $forms = $this->getForm($model);
        return view(
            'Competence::edit',
            compact('module', 'forms')
        );
        return view('Competence::create');
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
                return Competence::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Competence berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Competence.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Competence gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competence $competence): View
    {
        $forms = $this->getForm($competence);
        return view(
            'Competence::edit',
            compact('competence', 'forms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(
        Request $request,
        Competence $competence
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
                $competence,
                $validated
            ) {
                $competence->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Competence berhasil diperbarui.',
                $competence
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui Competence.',
                [
                    'exception' => $e,
                    'Competence_id' => $competence->getKey(),
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Competence gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(
        Request $request,
        Competence $competence
    ): JsonResponse|RedirectResponse {
        try {

            DB::transaction(function () use ($competence) {
                $competence->delete();
            });

            return $this->successResponse(
                $request,
                'Data Competence berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus Competence.',
                [
                    'exception' => $e,
                    'Competence_id' => $competence->getKey(),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Competence gagal dihapus.'
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
            ->route('competence.index')
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