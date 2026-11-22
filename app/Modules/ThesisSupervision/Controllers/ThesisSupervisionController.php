<?php

namespace App\Modules\ThesisSupervision\Controllers;

use App\DataTables\ThesisSupervisionDataTable;
use App\Http\Controllers\MainController;
use Models\ThesisSupervision;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ThesisSupervisionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ThesisSupervision());
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
        ThesisSupervisionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /thesis-supervision?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ThesisSupervision::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $thesisSupervision = new ThesisSupervision();
        $result = $this->getForm($thesisSupervision);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ThesisSupervision::create',
            compact('thesisSupervision', 'forms', 'groupedForms')
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
            'title' => ['required', 'string', 'max:255'],
            'supervisor_1_id' => ['required', 'integer'],
            'supervisor_2_id' => ['required', 'integer'],
            'examiner_1_id' => ['required', 'integer'],
            'examiner_2_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'defense_date' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'manuscript_url' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ThesisSupervision::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ThesisSupervision berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ThesisSupervision.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ThesisSupervision gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $thesisSupervision = $this->model->findOrFail($id);
        $result = $this->getForm($thesisSupervision);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ThesisSupervision::edit',
            compact('thesisSupervision', 'forms', 'groupedForms')
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
            'title' => ['required', 'string', 'max:255'],
            'supervisor_1_id' => ['required', 'integer'],
            'supervisor_2_id' => ['required', 'integer'],
            'examiner_1_id' => ['required', 'integer'],
            'examiner_2_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'defense_date' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'manuscript_url' => ['required', 'string', 'max:255'],
        ]);

        try {
            $thesisSupervision = $this->model->findOrFail($id);
            DB::transaction(function () use ($thesisSupervision, $validated) {
                $thesisSupervision->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ThesisSupervision berhasil diperbarui.',
                $thesisSupervision
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ThesisSupervision.',
                [
                    'exception' => $e,
                    'ThesisSupervision_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ThesisSupervision gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $thesisSupervision = $this->model->findOrFail($id);

            DB::transaction(function () use ($thesisSupervision) {
                $thesisSupervision->delete();
            });

            return $this->successResponse(
                $request,
                'Data ThesisSupervision berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $thesisSupervision->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ThesisSupervision.',
                [
                    'exception' => $e,
                    'ThesisSupervision_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ThesisSupervision gagal dihapus.'
            );
        }
    }
}
