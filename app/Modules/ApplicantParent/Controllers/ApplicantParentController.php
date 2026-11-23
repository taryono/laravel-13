<?php

namespace App\Modules\ApplicantParent\Controllers;

use App\DataTables\ApplicantParentDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantParent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantParentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantParent());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     *_Normal page request
     *_Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        ApplicantParentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-parent?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantParent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantParent = new ApplicantParent();
        $result = $this->getForm($applicantParent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantParent::create',
            compact('applicantParent', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'relation_type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:255'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'string', 'max:255'],
            'education_level' => ['required', 'string', 'max:255'],
            'job' => ['required', 'string', 'max:255'],
            'income_range' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'is_alive' => ['required', 'string', 'max:255'],
            'is_primary_contact' => ['required', 'string', 'max:255'],
            'is_verified' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantParent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantParent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantParent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantParent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantParent = $this->model->findOrFail($id);
        $result = $this->getForm($applicantParent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantParent::edit',
            compact('applicantParent', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'relation_type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:255'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'string', 'max:255'],
            'education_level' => ['required', 'string', 'max:255'],
            'job' => ['required', 'string', 'max:255'],
            'income_range' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'is_alive' => ['required', 'string', 'max:255'],
            'is_primary_contact' => ['required', 'string', 'max:255'],
            'is_verified' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantParent = $this->model->findOrFail($id);
            DB::transaction(function () use ($applicantParent, $validated) {
                $applicantParent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantParent berhasil diperbarui.',
                $applicantParent
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ApplicantParent.',
                [
                    'exception' => $e,
                    'ApplicantParent_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantParent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantParent = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantParent) {
                $applicantParent->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantParent berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $applicantParent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantParent.',
                [
                    'exception' => $e,
                    'ApplicantParent_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantParent gagal dihapus.'
            );
        }
    }
}
