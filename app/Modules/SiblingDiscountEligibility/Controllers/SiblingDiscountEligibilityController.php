<?php

namespace App\Modules\SiblingDiscountEligibility\Controllers;

use App\DataTables\SiblingDiscountEligibilityDataTable;
use App\Http\Controllers\MainController;
use Models\SiblingDiscountEligibility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SiblingDiscountEligibilityController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SiblingDiscountEligibility());
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
        SiblingDiscountEligibilityDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /sibling-discount-eligibility?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SiblingDiscountEligibility::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $siblingDiscountEligibility = new SiblingDiscountEligibility();
        $result = $this->getForm($siblingDiscountEligibility);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SiblingDiscountEligibility::create',
            compact('siblingDiscountEligibility', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'student_family_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'sibling_count' => ['required', 'string', 'max:255'],
            'sibling_order' => ['required', 'string', 'max:255'],
            'is_eligible' => ['required', 'string', 'max:255'],
            'discount_program_id' => ['required', 'integer'],
            'snapshot_date' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return SiblingDiscountEligibility::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountEligibility berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SiblingDiscountEligibility.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountEligibility gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $siblingDiscountEligibility = $this->model->findOrFail($id);
        $result = $this->getForm($siblingDiscountEligibility);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SiblingDiscountEligibility::edit',
            compact('siblingDiscountEligibility', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'student_family_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'sibling_count' => ['required', 'string', 'max:255'],
            'sibling_order' => ['required', 'string', 'max:255'],
            'is_eligible' => ['required', 'string', 'max:255'],
            'discount_program_id' => ['required', 'integer'],
            'snapshot_date' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $siblingDiscountEligibility = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $siblingDiscountEligibility,
                $validated
            ) {
                $siblingDiscountEligibility->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountEligibility berhasil diperbarui.',
                $siblingDiscountEligibility
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui SiblingDiscountEligibility.',
                [
                    'exception' => $e,
                    'SiblingDiscountEligibility_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountEligibility gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $siblingDiscountEligibility = $this->model->findOrFail($id);

            DB::transaction(function () use ($siblingDiscountEligibility) {
                $siblingDiscountEligibility->delete();
            });

            return $this->successResponse(
                $request,
                'Data SiblingDiscountEligibility berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $siblingDiscountEligibility->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SiblingDiscountEligibility.',
                [
                    'exception' => $e,
                    'SiblingDiscountEligibility_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SiblingDiscountEligibility gagal dihapus.'
            );
        }
    } 
}
