<?php

namespace App\Modules\ApplicantDomicile\Controllers;

use App\DataTables\ApplicantDomicileDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantDomicile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantDomicileController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantDomicile());
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
        ApplicantDomicileDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-domicile?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantDomicile::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantDomicile = new ApplicantDomicile();
        $result = $this->getForm($applicantDomicile);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantDomicile::create',
            compact('applicantDomicile', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'string', 'max:255'],
            'rw' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'province_id' => ['required', 'integer'],
            'regency_id' => ['required', 'integer'],
            'district_id' => ['required', 'integer'],
            'village_id' => ['required', 'integer'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'distance_to_school_m' => ['required', 'string', 'max:255'],
            'distance_calculated_at' => ['required', 'string', 'max:255'],
            'is_verified' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'verification_notes' => ['required', 'string', 'max:255'],
            'document_kk_url' => ['required', 'string', 'max:255'],
            'document_house_photo_url' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantDomicile::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantDomicile berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantDomicile.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantDomicile gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantDomicile = $this->model->findOrFail($id);
        $result = $this->getForm($applicantDomicile);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantDomicile::edit',
            compact('applicantDomicile', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'string', 'max:255'],
            'rw' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'province_id' => ['required', 'integer'],
            'regency_id' => ['required', 'integer'],
            'district_id' => ['required', 'integer'],
            'village_id' => ['required', 'integer'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'distance_to_school_m' => ['required', 'string', 'max:255'],
            'distance_calculated_at' => ['required', 'string', 'max:255'],
            'is_verified' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'verification_notes' => ['required', 'string', 'max:255'],
            'document_kk_url' => ['required', 'string', 'max:255'],
            'document_house_photo_url' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantDomicile = $this->model->findOrFail($id);
            DB::transaction(function () use ($applicantDomicile, $validated) {
                $applicantDomicile->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantDomicile berhasil diperbarui.',
                $applicantDomicile
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ApplicantDomicile.',
                [
                    'exception' => $e,
                    'ApplicantDomicile_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantDomicile gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantDomicile = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantDomicile) {
                $applicantDomicile->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantDomicile berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $applicantDomicile->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantDomicile.',
                [
                    'exception' => $e,
                    'ApplicantDomicile_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantDomicile gagal dihapus.'
            );
        }
    }
}
