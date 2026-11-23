<?php

namespace App\Modules\DeductionCategory\Controllers;

use App\DataTables\DeductionCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\DeductionCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DeductionCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DeductionCategory());
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
        DeductionCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /deduction-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DeductionCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $deductionCategory = new DeductionCategory();
        $result = $this->getForm($deductionCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DeductionCategory::create',
            compact('deductionCategory', 'forms', 'groupedForms')
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
            'category_type' => ['required', 'string', 'max:255'],
            'deduction_group' => ['required', 'string', 'max:255'],
            'calculation_type' => ['required', 'string', 'max:255'],
            'default_amount' => ['required', 'string', 'max:255'],
            'default_percentage' => ['required', 'string', 'max:255'],
            'min_amount' => ['required', 'string', 'max:255'],
            'max_amount' => ['required', 'string', 'max:255'],
            'is_base_on_salary' => ['required', 'string', 'max:255'],
            'is_taxable' => ['required', 'string', 'max:255'],
            'tax_code' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DeductionCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DeductionCategory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DeductionCategory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DeductionCategory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $deductionCategory = $this->model->findOrFail($id);
        $result = $this->getForm($deductionCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DeductionCategory::edit',
            compact('deductionCategory', 'forms', 'groupedForms')
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
            'category_type' => ['required', 'string', 'max:255'],
            'deduction_group' => ['required', 'string', 'max:255'],
            'calculation_type' => ['required', 'string', 'max:255'],
            'default_amount' => ['required', 'string', 'max:255'],
            'default_percentage' => ['required', 'string', 'max:255'],
            'min_amount' => ['required', 'string', 'max:255'],
            'max_amount' => ['required', 'string', 'max:255'],
            'is_base_on_salary' => ['required', 'string', 'max:255'],
            'is_taxable' => ['required', 'string', 'max:255'],
            'tax_code' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        try {
            $deductionCategory = $this->model->findOrFail($id);
            DB::transaction(function () use ($deductionCategory, $validated) {
                $deductionCategory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DeductionCategory berhasil diperbarui.',
                $deductionCategory
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DeductionCategory.',
                [
                    'exception' => $e,
                    'DeductionCategory_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DeductionCategory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $deductionCategory = $this->model->findOrFail($id);

            DB::transaction(function () use ($deductionCategory) {
                $deductionCategory->delete();
            });

            return $this->successResponse(
                $request,
                'Data DeductionCategory berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $deductionCategory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DeductionCategory.',
                [
                    'exception' => $e,
                    'DeductionCategory_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DeductionCategory gagal dihapus.'
            );
        }
    }
}
