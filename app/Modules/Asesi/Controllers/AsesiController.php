<?php

namespace App\Modules\Asesi\Controllers;

use App\DataTables\AsesiDataTable;
use App\Http\Controllers\Controller;
use App\Models\Asesi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AsesiController extends Controller
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
        AsesiDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /asesi?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Asesi::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('Asesi::create');
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
                return Asesi::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Asesi berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Asesi.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Asesi gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $asesi = Asesi::findOrFail($id);
        return view(
            'Asesi::edit',
            compact('asesi')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(
        Request $request,
        $id
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {

            $asesi = Asesi::findOrFail($id);
            DB::transaction(function () use (
                $asesi,
                $validated
            ) {
                $asesi->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Asesi berhasil diperbarui.',
                $asesi
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui Asesi.',
                [
                    'exception' => $e,
                    'Asesi_id' => $asesi->getKey(),
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Asesi gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(
        Request $request,
        Asesi $asesi
    ): JsonResponse|RedirectResponse {
        try {

            DB::transaction(function () use ($asesi) {
                $asesi->delete();
            });

            return $this->successResponse(
                $request,
                'Data Asesi berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus Asesi.',
                [
                    'exception' => $e,
                    'Asesi_id' => $asesi->getKey(),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Asesi gagal dihapus.'
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
            ->route('asesi.index')
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