<?php

namespace App\Modules\ExpenseCategory\Controllers;

use App\DataTables\ExpenseCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\ExpenseCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExpenseCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExpenseCategory());
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
        ExpenseCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /expense-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExpenseCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $expenseCategory = new ExpenseCategory();
        $result = $this->getForm($expenseCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExpenseCategory::create',
            compact('expenseCategory', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'parent_id' => ['required', 'integer'],
            'sort_order' => ['required', 'string', 'max:255'],
            'create_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExpenseCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExpenseCategory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExpenseCategory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExpenseCategory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $expenseCategory = $this->model->findOrFail($id);
        $result = $this->getForm($expenseCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExpenseCategory::edit',
            compact('expenseCategory', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'parent_id' => ['required', 'integer'],
            'sort_order' => ['required', 'string', 'max:255'],
            'create_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $expenseCategory = $this->model->findOrFail($id);
            DB::transaction(function () use ($expenseCategory, $validated) {
                $expenseCategory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExpenseCategory berhasil diperbarui.',
                $expenseCategory
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ExpenseCategory.',
                [
                    'exception' => $e,
                    'ExpenseCategory_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExpenseCategory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $expenseCategory = $this->model->findOrFail($id);

            DB::transaction(function () use ($expenseCategory) {
                $expenseCategory->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExpenseCategory berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $expenseCategory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExpenseCategory.',
                [
                    'exception' => $e,
                    'ExpenseCategory_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExpenseCategory gagal dihapus.'
            );
        }
    }
}
