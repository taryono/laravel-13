<?php

namespace App\Modules\ApplicantAchievement\Controllers;

use App\DataTables\ApplicantAchievementDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantAchievement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantAchievementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantAchievement());
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
        ApplicantAchievementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-achievement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantAchievement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantAchievement = new ApplicantAchievement();
        $result = $this->getForm($applicantAchievement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantAchievement::create',
            compact('applicantAchievement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'applicant_id' => ['required', 'integer'],
            'achievement_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:255'],
            'organizer' => ['required', 'string', 'max:255'],
            'year' => ['required', 'string', 'max:255'],
            'certificate_number' => ['required', 'string', 'max:255'],
            'certificate_url' => ['required', 'string', 'max:255'],
            'score_point' => ['required', 'string', 'max:255'],
            'is_verified' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantAchievement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantAchievement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantAchievement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantAchievement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantAchievement = $this->model->findOrFail($id);
        $result = $this->getForm($applicantAchievement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantAchievement::edit',
            compact('applicantAchievement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'applicant_id' => ['required', 'integer'],
            'achievement_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:255'],
            'organizer' => ['required', 'string', 'max:255'],
            'year' => ['required', 'string', 'max:255'],
            'certificate_number' => ['required', 'string', 'max:255'],
            'certificate_url' => ['required', 'string', 'max:255'],
            'score_point' => ['required', 'string', 'max:255'],
            'is_verified' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantAchievement = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $applicantAchievement,
                $validated
            ) {
                $applicantAchievement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantAchievement berhasil diperbarui.',
                $applicantAchievement
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ApplicantAchievement.',
                [
                    'exception' => $e,
                    'ApplicantAchievement_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantAchievement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantAchievement = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantAchievement) {
                $applicantAchievement->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantAchievement berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $applicantAchievement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantAchievement.',
                [
                    'exception' => $e,
                    'ApplicantAchievement_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantAchievement gagal dihapus.'
            );
        }
    } 
}
