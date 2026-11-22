<?php

namespace App\Modules\TestType\Controllers;

use App\DataTables\TestTypeDataTable;
use App\Http\Controllers\MainController;
use Models\TestType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TestTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TestType());
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
        TestTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /test-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TestType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $testType = new TestType();
        $result = $this->getForm($testType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TestType::create',
            compact('testType', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'parent_id' => ['required', 'integer'],
            'icon' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'badge_class' => ['required', 'string', 'max:255'],
            'default_duration_minutes' => ['required', 'string', 'max:255'],
            'default_passing_score' => ['required', 'string', 'max:255'],
            'default_max_score' => ['required', 'string', 'max:255'],
            'default_weight' => ['required', 'string', 'max:255'],
            'default_method' => ['required', 'string', 'max:255'],
            'requires_room' => ['required', 'string', 'max:255'],
            'requires_supervisor' => ['required', 'string', 'max:255'],
            'requires_cbt' => ['required', 'string', 'max:255'],
            'scoring_components' => ['required', 'string', 'max:255'],
            'settings' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'school_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TestType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TestType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TestType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TestType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $testType = $this->model->findOrFail($id);
        $result = $this->getForm($testType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TestType::edit',
            compact('testType', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'parent_id' => ['required', 'integer'],
            'icon' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'badge_class' => ['required', 'string', 'max:255'],
            'default_duration_minutes' => ['required', 'string', 'max:255'],
            'default_passing_score' => ['required', 'string', 'max:255'],
            'default_max_score' => ['required', 'string', 'max:255'],
            'default_weight' => ['required', 'string', 'max:255'],
            'default_method' => ['required', 'string', 'max:255'],
            'requires_room' => ['required', 'string', 'max:255'],
            'requires_supervisor' => ['required', 'string', 'max:255'],
            'requires_cbt' => ['required', 'string', 'max:255'],
            'scoring_components' => ['required', 'string', 'max:255'],
            'settings' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'school_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $testType = $this->model->findOrFail($id);
            DB::transaction(function () use ($testType, $validated) {
                $testType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TestType berhasil diperbarui.',
                $testType
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui TestType.',
                [
                    'exception' => $e,
                    'TestType_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TestType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $testType = $this->model->findOrFail($id);

            DB::transaction(function () use ($testType) {
                $testType->delete();
            });

            return $this->successResponse(
                $request,
                'Data TestType berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $testType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TestType.',
                [
                    'exception' => $e,
                    'TestType_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TestType gagal dihapus.'
            );
        }
    }
}
