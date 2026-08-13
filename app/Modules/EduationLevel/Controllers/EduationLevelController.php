<?php

namespace App\Modules\EduationLevel\Controllers;

use App\DataTables\EduationLevelDataTable;
use App\Http\Controllers\MainController;
use Models\EduationLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EduationLevelController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EduationLevel());
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
        EduationLevelDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /eduation-level?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EduationLevel::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $eduationLevel = new EduationLevel();
        $result = $this->getForm($eduationLevel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EduationLevel::edit',
            compact('eduationLevel', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EduationLevel::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EduationLevel berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EduationLevel.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EduationLevel gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $eduationLevel = $this->model->findOrFail($id);
        $result = $this->getForm($eduationLevel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EduationLevel::edit',
            compact('eduationLevel', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $eduationLevel = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $eduationLevel,
                $validated
            ) {
                $eduationLevel->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EduationLevel berhasil diperbarui.',
                $eduationLevel
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $eduationLevel->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $eduationLevel belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui EduationLevel.',
                [
                    'exception' => $e,
                    'EduationLevel_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EduationLevel gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $eduationLevel = $this->model->findOrFail($id);

            DB::transaction(function () use ($eduationLevel) {
                $eduationLevel->delete();
            });

            return $this->successResponse(
                $request,
                'Data EduationLevel berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $eduationLevel->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EduationLevel.',
                [
                    'exception' => $e,
                    'EduationLevel_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EduationLevel gagal dihapus.'
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
            ->route('eduation-level.index')
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
