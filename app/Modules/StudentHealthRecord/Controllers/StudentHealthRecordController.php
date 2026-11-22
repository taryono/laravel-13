<?php

namespace App\Modules\StudentHealthRecord\Controllers;

use App\DataTables\StudentHealthRecordDataTable;
use App\Http\Controllers\MainController;
use Models\StudentHealthRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentHealthRecordController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentHealthRecord());
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
        StudentHealthRecordDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-health-record?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentHealthRecord::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentHealthRecord = new StudentHealthRecord();
        $result = $this->getForm($studentHealthRecord);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentHealthRecord::create',
            compact('studentHealthRecord', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'record_date' => ['required', 'string', 'max:255'],
            'complaint' => ['required', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['required', 'string', 'max:255'],
            'height_cm' => ['required', 'string', 'max:255'],
            'weight_kg' => ['required', 'string', 'max:255'],
            'blood_type' => ['required', 'string', 'max:255'],
            'blood_pressure' => ['required', 'string', 'max:255'],
            'allergies' => ['required', 'string', 'max:255'],
            'vaccination_notes' => ['required', 'string', 'max:255'],
            'handled_by' => ['required', 'string', 'max:255'],
            'referred_to' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentHealthRecord::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentHealthRecord berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentHealthRecord.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentHealthRecord gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentHealthRecord = $this->model->findOrFail($id);
        $result = $this->getForm($studentHealthRecord);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentHealthRecord::edit',
            compact('studentHealthRecord', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'record_date' => ['required', 'string', 'max:255'],
            'complaint' => ['required', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['required', 'string', 'max:255'],
            'height_cm' => ['required', 'string', 'max:255'],
            'weight_kg' => ['required', 'string', 'max:255'],
            'blood_type' => ['required', 'string', 'max:255'],
            'blood_pressure' => ['required', 'string', 'max:255'],
            'allergies' => ['required', 'string', 'max:255'],
            'vaccination_notes' => ['required', 'string', 'max:255'],
            'handled_by' => ['required', 'string', 'max:255'],
            'referred_to' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentHealthRecord = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentHealthRecord, $validated) {
                $studentHealthRecord->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentHealthRecord berhasil diperbarui.',
                $studentHealthRecord
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentHealthRecord.',
                [
                    'exception' => $e,
                    'StudentHealthRecord_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentHealthRecord gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentHealthRecord = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentHealthRecord) {
                $studentHealthRecord->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentHealthRecord berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentHealthRecord->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentHealthRecord.',
                [
                    'exception' => $e,
                    'StudentHealthRecord_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentHealthRecord gagal dihapus.'
            );
        }
    }
}
