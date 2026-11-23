<?php

namespace App\Modules\ApplicantCategory\Controllers;

use App\DataTables\ApplicantCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantCategory());
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
        ApplicantCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantCategory = new ApplicantCategory();
        $result = $this->getForm($applicantCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantCategory::create',
            compact('applicantCategory', 'forms', 'groupedForms')
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
            'parent_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category_type' => ['required', 'string', 'max:255'],
            'priority_level' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'string', 'max:255'],
            'requires_document' => ['required', 'string', 'max:255'],
            'required_document_types' => ['required', 'string', 'max:255'],
            'required_fields' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'badge_class' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_default' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantCategory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantCategory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantCategory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantCategory = $this->model->findOrFail($id);
        $result = $this->getForm($applicantCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantCategory::edit',
            compact('applicantCategory', 'forms', 'groupedForms')
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
            'parent_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category_type' => ['required', 'string', 'max:255'],
            'priority_level' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'string', 'max:255'],
            'requires_document' => ['required', 'string', 'max:255'],
            'required_document_types' => ['required', 'string', 'max:255'],
            'required_fields' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'badge_class' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_default' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantCategory = $this->model->findOrFail($id);
            DB::transaction(function () use ($applicantCategory, $validated) {
                $applicantCategory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantCategory berhasil diperbarui.',
                $applicantCategory
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ApplicantCategory.',
                [
                    'exception' => $e,
                    'ApplicantCategory_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantCategory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantCategory = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantCategory) {
                $applicantCategory->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantCategory berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $applicantCategory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantCategory.',
                [
                    'exception' => $e,
                    'ApplicantCategory_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantCategory gagal dihapus.'
            );
        }
    }
}
