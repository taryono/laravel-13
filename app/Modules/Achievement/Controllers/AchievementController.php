<?php

namespace App\Modules\Achievement\Controllers;

use App\DataTables\AchievementDataTable;
use App\Http\Controllers\MainController;
use Models\Achievement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AchievementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Achievement());
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
        AchievementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /achievement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Achievement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $achievement = new Achievement();
        $result = $this->getForm($achievement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Achievement::create',
            compact('achievement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'achievement_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'date_achieved' => ['required', 'string', 'max:255'],
            'certificate_url' => ['required', 'string', 'max:255'],
            'points' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'is_verified' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Achievement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Achievement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Achievement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Achievement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $achievement = $this->model->findOrFail($id);
        $result = $this->getForm($achievement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Achievement::edit',
            compact('achievement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'achievement_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'date_achieved' => ['required', 'string', 'max:255'],
            'certificate_url' => ['required', 'string', 'max:255'],
            'points' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'is_verified' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $achievement = $this->model->findOrFail($id);
            DB::transaction(function () use ($achievement, $validated) {
                $achievement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Achievement berhasil diperbarui.',
                $achievement
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Achievement.',
                [
                    'exception' => $e,
                    'Achievement_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Achievement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $achievement = $this->model->findOrFail($id);

            DB::transaction(function () use ($achievement) {
                $achievement->delete();
            });

            return $this->successResponse(
                $request,
                'Data Achievement berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $achievement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Achievement.',
                [
                    'exception' => $e,
                    'Achievement_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Achievement gagal dihapus.'
            );
        }
    }
}
