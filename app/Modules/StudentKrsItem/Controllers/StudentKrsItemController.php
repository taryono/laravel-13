<?php

namespace App\Modules\StudentKrsItem\Controllers;

use App\DataTables\StudentKrsItemDataTable;
use App\Http\Controllers\MainController;
use Models\StudentKrsItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentKrsItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentKrsItem());
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
        StudentKrsItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-krs-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentKrsItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentKrsItem = new StudentKrsItem();
        $result = $this->getForm($studentKrsItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentKrsItem::create',
            compact('studentKrsItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'student_krs_id' => ['required', 'integer'],
            'course_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'sks' => ['required', 'string', 'max:255'],
            'grade_numeric' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'grade_point' => ['required', 'string', 'max:255'],
            'is_passed' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentKrsItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentKrsItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentKrsItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentKrsItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentKrsItem = $this->model->findOrFail($id);
        $result = $this->getForm($studentKrsItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentKrsItem::edit',
            compact('studentKrsItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'student_krs_id' => ['required', 'integer'],
            'course_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'sks' => ['required', 'string', 'max:255'],
            'grade_numeric' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'grade_point' => ['required', 'string', 'max:255'],
            'is_passed' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentKrsItem = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $studentKrsItem,
                $validated
            ) {
                $studentKrsItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentKrsItem berhasil diperbarui.',
                $studentKrsItem
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StudentKrsItem.',
                [
                    'exception' => $e,
                    'StudentKrsItem_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentKrsItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentKrsItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentKrsItem) {
                $studentKrsItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentKrsItem berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $studentKrsItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentKrsItem.',
                [
                    'exception' => $e,
                    'StudentKrsItem_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentKrsItem gagal dihapus.'
            );
        }
    } 
}
