<?php

namespace App\Modules\ApplicantChoice\Controllers;

use App\DataTables\ApplicantChoiceDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantChoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantChoiceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantChoice());
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
        ApplicantChoiceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-choice?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantChoice::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantChoice = new ApplicantChoice();
        $result = $this->getForm($applicantChoice);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantChoice::create',
            compact('applicantChoice', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'choice_order' => ['required', 'string', 'max:255'],
            'is_accepted' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantChoice::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantChoice berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantChoice.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantChoice gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantChoice = $this->model->findOrFail($id);
        $result = $this->getForm($applicantChoice);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantChoice::edit',
            compact('applicantChoice', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'choice_order' => ['required', 'string', 'max:255'],
            'is_accepted' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantChoice = $this->model->findOrFail($id);
            DB::transaction(function () use ($applicantChoice, $validated) {
                $applicantChoice->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantChoice berhasil diperbarui.',
                $applicantChoice
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ApplicantChoice.',
                [
                    'exception' => $e,
                    'ApplicantChoice_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantChoice gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantChoice = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantChoice) {
                $applicantChoice->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantChoice berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $applicantChoice->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantChoice.',
                [
                    'exception' => $e,
                    'ApplicantChoice_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantChoice gagal dihapus.'
            );
        }
    }
}
