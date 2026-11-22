<?php

namespace App\Modules\ApplicantSupportProgram\Controllers;

use App\DataTables\ApplicantSupportProgramDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantSupportProgram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantSupportProgramController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantSupportProgram());
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
        ApplicantSupportProgramDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-support-program?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantSupportProgram::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantSupportProgram = new ApplicantSupportProgram();
        $result = $this->getForm($applicantSupportProgram);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantSupportProgram::create',
            compact('applicantSupportProgram', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'program_type' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'max:255'],
            'program_name' => ['required', 'string', 'max:255'],
            'issuer' => ['required', 'string', 'max:255'],
            'valid_from' => ['required', 'string', 'max:255'],
            'valid_until' => ['required', 'string', 'max:255'],
            'card_url' => ['required', 'string', 'max:255'],
            'supporting_document_url' => ['required', 'string', 'max:255'],
            'validation_status' => ['required', 'string', 'max:255'],
            'validated_by' => ['required', 'string', 'max:255'],
            'validated_at' => ['required', 'string', 'max:255'],
            'validation_notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantSupportProgram::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantSupportProgram berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantSupportProgram.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantSupportProgram gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantSupportProgram = $this->model->findOrFail($id);
        $result = $this->getForm($applicantSupportProgram);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantSupportProgram::edit',
            compact('applicantSupportProgram', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'program_type' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'max:255'],
            'program_name' => ['required', 'string', 'max:255'],
            'issuer' => ['required', 'string', 'max:255'],
            'valid_from' => ['required', 'string', 'max:255'],
            'valid_until' => ['required', 'string', 'max:255'],
            'card_url' => ['required', 'string', 'max:255'],
            'supporting_document_url' => ['required', 'string', 'max:255'],
            'validation_status' => ['required', 'string', 'max:255'],
            'validated_by' => ['required', 'string', 'max:255'],
            'validated_at' => ['required', 'string', 'max:255'],
            'validation_notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantSupportProgram = $this->model->findOrFail($id);
            DB::transaction(function () use ($applicantSupportProgram, $validated) {
                $applicantSupportProgram->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantSupportProgram berhasil diperbarui.',
                $applicantSupportProgram
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ApplicantSupportProgram.',
                [
                    'exception' => $e,
                    'ApplicantSupportProgram_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantSupportProgram gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantSupportProgram = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantSupportProgram) {
                $applicantSupportProgram->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantSupportProgram berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $applicantSupportProgram->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantSupportProgram.',
                [
                    'exception' => $e,
                    'ApplicantSupportProgram_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantSupportProgram gagal dihapus.'
            );
        }
    }
}
