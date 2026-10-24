<?php

namespace App\Modules\ExtracurricularAchievement\Controllers;

use App\DataTables\ExtracurricularAchievementDataTable;
use App\Http\Controllers\MainController;
use Models\ExtracurricularAchievement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExtracurricularAchievementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExtracurricularAchievement());
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
        ExtracurricularAchievementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /extracurricular-achievement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExtracurricularAchievement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $extracurricularAchievement = new ExtracurricularAchievement();
        $result = $this->getForm($extracurricularAchievement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExtracurricularAchievement::create',
            compact('extracurricularAchievement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'extracurricular_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'coach_id' => ['required', 'integer'],
            'achievement_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'event_name' => ['required', 'string', 'max:255'],
            'event_level' => ['required', 'string', 'max:255'],
            'organizer' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:255'],
            'medal' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'string', 'max:255'],
            'event_end_date' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'certificate_path' => ['required', 'string', 'max:255'],
            'photo_path' => ['required', 'string', 'max:255'],
            'documentation_url' => ['required', 'string', 'max:255'],
            'points' => ['required', 'string', 'max:255'],
            'is_verified' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExtracurricularAchievement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularAchievement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExtracurricularAchievement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularAchievement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $extracurricularAchievement = $this->model->findOrFail($id);
        $result = $this->getForm($extracurricularAchievement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExtracurricularAchievement::edit',
            compact('extracurricularAchievement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'extracurricular_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'coach_id' => ['required', 'integer'],
            'achievement_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'event_name' => ['required', 'string', 'max:255'],
            'event_level' => ['required', 'string', 'max:255'],
            'organizer' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:255'],
            'medal' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'string', 'max:255'],
            'event_end_date' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'certificate_path' => ['required', 'string', 'max:255'],
            'photo_path' => ['required', 'string', 'max:255'],
            'documentation_url' => ['required', 'string', 'max:255'],
            'points' => ['required', 'string', 'max:255'],
            'is_verified' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $extracurricularAchievement = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $extracurricularAchievement,
                $validated
            ) {
                $extracurricularAchievement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularAchievement berhasil diperbarui.',
                $extracurricularAchievement
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ExtracurricularAchievement.',
                [
                    'exception' => $e,
                    'ExtracurricularAchievement_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularAchievement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $extracurricularAchievement = $this->model->findOrFail($id);

            DB::transaction(function () use ($extracurricularAchievement) {
                $extracurricularAchievement->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularAchievement berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $extracurricularAchievement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExtracurricularAchievement.',
                [
                    'exception' => $e,
                    'ExtracurricularAchievement_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularAchievement gagal dihapus.'
            );
        }
    } 
}
