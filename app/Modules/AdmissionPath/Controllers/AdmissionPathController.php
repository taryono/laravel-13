<?php

namespace App\Modules\AdmissionPath\Controllers;

use App\DataTables\AdmissionPathDataTable;
use App\Http\Controllers\MainController;
use Models\AdmissionPath;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionPathController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AdmissionPath());
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
        AdmissionPathDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission-path?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AdmissionPath::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admissionPath = new AdmissionPath();
        $result = $this->getForm($admissionPath);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionPath::create',
            compact('admissionPath', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'admission_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'min_score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'requirements' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'open_date' => ['required', 'string', 'max:255'],
            'close_date' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AdmissionPath::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionPath berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AdmissionPath.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionPath gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admissionPath = $this->model->findOrFail($id);
        $result = $this->getForm($admissionPath);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionPath::edit',
            compact('admissionPath', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'admission_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'min_score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'requirements' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'open_date' => ['required', 'string', 'max:255'],
            'close_date' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admissionPath = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $admissionPath,
                $validated
            ) {
                $admissionPath->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionPath berhasil diperbarui.',
                $admissionPath
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui AdmissionPath.',
                [
                    'exception' => $e,
                    'AdmissionPath_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionPath gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admissionPath = $this->model->findOrFail($id);

            DB::transaction(function () use ($admissionPath) {
                $admissionPath->delete();
            });

            return $this->successResponse(
                $request,
                'Data AdmissionPath berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $admissionPath->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AdmissionPath.',
                [
                    'exception' => $e,
                    'AdmissionPath_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionPath gagal dihapus.'
            );
        }
    } 
}
