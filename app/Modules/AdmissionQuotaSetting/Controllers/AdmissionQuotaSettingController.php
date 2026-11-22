<?php

namespace App\Modules\AdmissionQuotaSetting\Controllers;

use App\DataTables\AdmissionQuotaSettingDataTable;
use App\Http\Controllers\MainController;
use Models\AdmissionQuotaSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionQuotaSettingController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AdmissionQuotaSetting());
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
        AdmissionQuotaSettingDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission-quota-setting?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AdmissionQuotaSetting::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admissionQuotaSetting = new AdmissionQuotaSetting();
        $result = $this->getForm($admissionQuotaSetting);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionQuotaSetting::create',
            compact('admissionQuotaSetting', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'admission_path_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'faculty_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'quota' => ['required', 'string', 'max:255'],
            'filled' => ['required', 'string', 'max:255'],
            'reserved' => ['required', 'string', 'max:255'],
            'waitlist_quota' => ['required', 'string', 'max:255'],
            'min_quota' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AdmissionQuotaSetting::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionQuotaSetting berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AdmissionQuotaSetting.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionQuotaSetting gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admissionQuotaSetting = $this->model->findOrFail($id);
        $result = $this->getForm($admissionQuotaSetting);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionQuotaSetting::edit',
            compact('admissionQuotaSetting', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'admission_path_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'faculty_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'quota' => ['required', 'string', 'max:255'],
            'filled' => ['required', 'string', 'max:255'],
            'reserved' => ['required', 'string', 'max:255'],
            'waitlist_quota' => ['required', 'string', 'max:255'],
            'min_quota' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admissionQuotaSetting = $this->model->findOrFail($id);
            DB::transaction(function () use ($admissionQuotaSetting, $validated) {
                $admissionQuotaSetting->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionQuotaSetting berhasil diperbarui.',
                $admissionQuotaSetting
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AdmissionQuotaSetting.',
                [
                    'exception' => $e,
                    'AdmissionQuotaSetting_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionQuotaSetting gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admissionQuotaSetting = $this->model->findOrFail($id);

            DB::transaction(function () use ($admissionQuotaSetting) {
                $admissionQuotaSetting->delete();
            });

            return $this->successResponse(
                $request,
                'Data AdmissionQuotaSetting berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $admissionQuotaSetting->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AdmissionQuotaSetting.',
                [
                    'exception' => $e,
                    'AdmissionQuotaSetting_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionQuotaSetting gagal dihapus.'
            );
        }
    }
}
