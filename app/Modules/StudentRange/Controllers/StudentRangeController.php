<?php

namespace App\Modules\StudentRange\Controllers;

use App\DataTables\StudentRangeDataTable;
use App\Http\Controllers\MainController;
use Models\StudentRange;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentRangeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentRange());
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
        StudentRangeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-range?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentRange::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentRange = new StudentRange();
        $result = $this->getForm($studentRange);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentRange::create',
            compact('studentRange', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'min_value' => ['required', 'string', 'max:255'],
            'max_value' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentRange::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentRange berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentRange.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentRange gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentRange = $this->model->findOrFail($id);
        $result = $this->getForm($studentRange);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentRange::edit',
            compact('studentRange', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'min_value' => ['required', 'string', 'max:255'],
            'max_value' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentRange = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentRange, $validated) {
                $studentRange->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentRange berhasil diperbarui.',
                $studentRange
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentRange.',
                [
                    'exception' => $e,
                    'StudentRange_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentRange gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentRange = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentRange) {
                $studentRange->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentRange berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentRange->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentRange.',
                [
                    'exception' => $e,
                    'StudentRange_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentRange gagal dihapus.'
            );
        }
    }
}
