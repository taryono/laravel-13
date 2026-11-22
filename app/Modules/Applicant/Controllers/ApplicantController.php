<?php

namespace App\Modules\Applicant\Controllers;

use App\DataTables\ApplicantDataTable;
use App\Http\Controllers\MainController;
use Models\Applicant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Applicant());
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
        ApplicantDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Applicant::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicant = new Applicant();
        $result = $this->getForm($applicant);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Applicant::create',
            compact('applicant', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'full_name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:255'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['required', 'string', 'max:255'],
            'parent_email' => ['required', 'string', 'max:255'],
            'previous_school' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'test_score' => ['required', 'string', 'max:255'],
            'interview_score' => ['required', 'string', 'max:255'],
            'total_score' => ['required', 'string', 'max:255'],
            'ranking' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'registered_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Applicant::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Applicant berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Applicant.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Applicant gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicant = $this->model->findOrFail($id);
        $result = $this->getForm($applicant);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Applicant::edit',
            compact('applicant', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'full_name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:255'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['required', 'string', 'max:255'],
            'parent_email' => ['required', 'string', 'max:255'],
            'previous_school' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'test_score' => ['required', 'string', 'max:255'],
            'interview_score' => ['required', 'string', 'max:255'],
            'total_score' => ['required', 'string', 'max:255'],
            'ranking' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'registered_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicant = $this->model->findOrFail($id);
            DB::transaction(function () use ($applicant, $validated) {
                $applicant->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Applicant berhasil diperbarui.',
                $applicant
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Applicant.',
                [
                    'exception' => $e,
                    'Applicant_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Applicant gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicant = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicant) {
                $applicant->delete();
            });

            return $this->successResponse(
                $request,
                'Data Applicant berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $applicant->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Applicant.',
                [
                    'exception' => $e,
                    'Applicant_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Applicant gagal dihapus.'
            );
        }
    }
}
