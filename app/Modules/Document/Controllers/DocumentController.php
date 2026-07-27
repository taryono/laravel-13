<?php

namespace App\Modules\Document\Controllers;

use App\DataTables\DocumentDataTable;
use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DocumentController extends Controller
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
        DocumentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /document?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Document::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('Document::create');
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
                return Document::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Document berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Document.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Document gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document): View
    {
        return view(
            'Document::edit',
            compact('document')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(
        Request $request,
        Document $document
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
                $document,
                $validated
            ) {
                $document->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Document berhasil diperbarui.',
                $document
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui Document.',
                [
                    'exception' => $e,
                    'Document_id' => $document->getKey(),
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Document gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(
        Request $request,
        Document $document
    ): JsonResponse|RedirectResponse {
        try {

            DB::transaction(function () use ($document) {
                $document->delete();
            });

            return $this->successResponse(
                $request,
                'Data Document berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus Document.',
                [
                    'exception' => $e,
                    'Document_id' => $document->getKey(),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Document gagal dihapus.'
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
            ->route('document.index')
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