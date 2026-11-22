<?php

namespace App\Modules\StudentTranscript\Controllers;

use App\DataTables\StudentTranscriptDataTable;
use App\Http\Controllers\MainController;
use Models\StudentTranscript;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentTranscriptController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentTranscript());
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
        StudentTranscriptDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-transcript?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentTranscript::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentTranscript = new StudentTranscript();
        $result = $this->getForm($studentTranscript);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentTranscript::create',
            compact('studentTranscript', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'transcript_number' => ['required', 'string', 'max:255'],
            'graduation_date' => ['required', 'string', 'max:255'],
            'total_sks_earned' => ['required', 'string', 'max:255'],
            'final_gpa' => ['required', 'string', 'max:255'],
            'predicate' => ['required', 'string', 'max:255'],
            'is_official' => ['required', 'string', 'max:255'],
            'printed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentTranscript::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentTranscript berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentTranscript.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentTranscript gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentTranscript = $this->model->findOrFail($id);
        $result = $this->getForm($studentTranscript);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentTranscript::edit',
            compact('studentTranscript', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'transcript_number' => ['required', 'string', 'max:255'],
            'graduation_date' => ['required', 'string', 'max:255'],
            'total_sks_earned' => ['required', 'string', 'max:255'],
            'final_gpa' => ['required', 'string', 'max:255'],
            'predicate' => ['required', 'string', 'max:255'],
            'is_official' => ['required', 'string', 'max:255'],
            'printed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentTranscript = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentTranscript, $validated) {
                $studentTranscript->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentTranscript berhasil diperbarui.',
                $studentTranscript
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentTranscript.',
                [
                    'exception' => $e,
                    'StudentTranscript_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentTranscript gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentTranscript = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentTranscript) {
                $studentTranscript->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentTranscript berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentTranscript->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentTranscript.',
                [
                    'exception' => $e,
                    'StudentTranscript_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentTranscript gagal dihapus.'
            );
        }
    }
}
