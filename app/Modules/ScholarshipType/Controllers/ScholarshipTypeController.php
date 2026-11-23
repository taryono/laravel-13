<?php

namespace App\Modules\ScholarshipType\Controllers;

use App\DataTables\ScholarshipTypeDataTable;
use App\Http\Controllers\MainController;
use Models\ScholarshipType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ScholarshipTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ScholarshipType());
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
        ScholarshipTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /scholarship-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ScholarshipType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $scholarshipType = new ScholarshipType();
        $result = $this->getForm($scholarshipType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ScholarshipType::create',
            compact('scholarshipType', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'requirements' => ['required', 'string', 'max:255'],
            'benefits' => ['required', 'string', 'max:255'],
            'is_recurring' => ['required', 'string', 'max:255'],
            'recurrence_period' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ScholarshipType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ScholarshipType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $scholarshipType = $this->model->findOrFail($id);
        $result = $this->getForm($scholarshipType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ScholarshipType::edit',
            compact('scholarshipType', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'requirements' => ['required', 'string', 'max:255'],
            'benefits' => ['required', 'string', 'max:255'],
            'is_recurring' => ['required', 'string', 'max:255'],
            'recurrence_period' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $scholarshipType = $this->model->findOrFail($id);
            DB::transaction(function () use ($scholarshipType, $validated) {
                $scholarshipType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipType berhasil diperbarui.',
                $scholarshipType
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ScholarshipType.',
                [
                    'exception' => $e,
                    'ScholarshipType_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $scholarshipType = $this->model->findOrFail($id);

            DB::transaction(function () use ($scholarshipType) {
                $scholarshipType->delete();
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipType berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $scholarshipType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ScholarshipType.',
                [
                    'exception' => $e,
                    'ScholarshipType_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipType gagal dihapus.'
            );
        }
    }
}
