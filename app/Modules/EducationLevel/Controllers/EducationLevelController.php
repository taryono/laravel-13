<?php

namespace App\Modules\EducationLevel\Controllers;

use App\DataTables\EducationLevelDataTable;
use App\Http\Controllers\MainController;
use Models\EducationLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EducationLevelController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EducationLevel());
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
        EducationLevelDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /education-level?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EducationLevel::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $educationLevel = new EducationLevel();
        $result = $this->getForm($educationLevel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EducationLevel::edit',
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
                return EducationLevel::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EducationLevel berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EducationLevel.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EducationLevel gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $educationLevel = $this->model->findOrFail($id);
        $result = $this->getForm($educationLevel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EducationLevel::edit',
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
            $educationLevel = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $educationLevel,
                $validated
            ) {
                $educationLevel->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EducationLevel berhasil diperbarui.',
                $educationLevel
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $educationLevel->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $educationLevel belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui EducationLevel.',
                [
                    'exception' => $e,
                    'EducationLevel_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EducationLevel gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $educationLevel = $this->model->findOrFail($id);

            DB::transaction(function () use ($educationLevel) {
                $educationLevel->delete();
            });

            return $this->successResponse(
                $request,
                'Data EducationLevel berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $educationLevel->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EducationLevel.',
                [
                    'exception' => $e,
                    'EducationLevel_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EducationLevel gagal dihapus.'
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
            ->route('education-level.index')
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
