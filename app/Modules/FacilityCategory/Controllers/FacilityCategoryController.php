<?php

namespace App\Modules\FacilityCategory\Controllers;

use App\DataTables\FacilityCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\FacilityCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FacilityCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FacilityCategory());
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
        FacilityCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /facility-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FacilityCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $facilityCategory = new FacilityCategory();
        $result = $this->getForm($facilityCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FacilityCategory::create',
            compact('facilityCategory', 'forms', 'groupedForms')
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
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FacilityCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FacilityCategory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FacilityCategory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FacilityCategory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $facilityCategory = $this->model->findOrFail($id);
        $result = $this->getForm($facilityCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FacilityCategory::edit',
            compact('facilityCategory', 'forms', 'groupedForms')
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
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $facilityCategory = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $facilityCategory,
                $validated
            ) {
                $facilityCategory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FacilityCategory berhasil diperbarui.',
                $facilityCategory
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $facilityCategory->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $facilityCategory belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui FacilityCategory.',
                [
                    'exception' => $e,
                    'FacilityCategory_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FacilityCategory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $facilityCategory = $this->model->findOrFail($id);

            DB::transaction(function () use ($facilityCategory) {
                $facilityCategory->delete();
            });

            return $this->successResponse(
                $request,
                'Data FacilityCategory berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $facilityCategory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FacilityCategory.',
                [
                    'exception' => $e,
                    'FacilityCategory_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FacilityCategory gagal dihapus.'
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
            ->route('facility-category.index')
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
