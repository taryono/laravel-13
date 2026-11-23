<?php

namespace App\Modules\ReportCardNote\Controllers;

use App\DataTables\ReportCardNoteDataTable;
use App\Http\Controllers\MainController;
use Models\ReportCardNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReportCardNoteController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportCardNote());
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
        ReportCardNoteDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-card-note?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportCardNote::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reportCardNote = new ReportCardNote();
        $result = $this->getForm($reportCardNote);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardNote::create',
            compact('reportCardNote', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_card_id' => ['required', 'integer'],
            'note_type' => ['required', 'string', 'max:255'],
            'note' => ['required', 'string', 'max:255'],
            'author_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportCardNote::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardNote berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportCardNote.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardNote gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportCardNote = $this->model->findOrFail($id);
        $result = $this->getForm($reportCardNote);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardNote::edit',
            compact('reportCardNote', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'report_card_id' => ['required', 'integer'],
            'note_type' => ['required', 'string', 'max:255'],
            'note' => ['required', 'string', 'max:255'],
            'author_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $reportCardNote = $this->model->findOrFail($id);
            DB::transaction(function () use ($reportCardNote, $validated) {
                $reportCardNote->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardNote berhasil diperbarui.',
                $reportCardNote
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ReportCardNote.',
                [
                    'exception' => $e,
                    'ReportCardNote_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardNote gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportCardNote = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportCardNote) {
                $reportCardNote->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportCardNote berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $reportCardNote->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportCardNote.',
                [
                    'exception' => $e,
                    'ReportCardNote_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardNote gagal dihapus.'
            );
        }
    }
}
