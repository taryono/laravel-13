<?php

namespace App\Modules\ApplicantStatusHistory\Controllers;

use App\DataTables\ApplicantStatusHistoryDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantStatusHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantStatusHistoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantStatusHistory());
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
        ApplicantStatusHistoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-status-history?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantStatusHistory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantStatusHistory = new ApplicantStatusHistory();
        $result = $this->getForm($applicantStatusHistory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantStatusHistory::create',
            compact('applicantStatusHistory', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'from_status_id' => ['required', 'integer'],
            'to_status_id' => ['required', 'integer'],
            'changed_by' => ['required', 'string', 'max:255'],
            'changed_by_system' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'metadata' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantStatusHistory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantStatusHistory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantStatusHistory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantStatusHistory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantStatusHistory = $this->model->findOrFail($id);
        $result = $this->getForm($applicantStatusHistory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantStatusHistory::edit',
            compact('applicantStatusHistory', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'from_status_id' => ['required', 'integer'],
            'to_status_id' => ['required', 'integer'],
            'changed_by' => ['required', 'string', 'max:255'],
            'changed_by_system' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'metadata' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantStatusHistory = $this->model->findOrFail($id);
            DB::transaction(function () use ($applicantStatusHistory, $validated) {
                $applicantStatusHistory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantStatusHistory berhasil diperbarui.',
                $applicantStatusHistory
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ApplicantStatusHistory.',
                [
                    'exception' => $e,
                    'ApplicantStatusHistory_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantStatusHistory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantStatusHistory = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantStatusHistory) {
                $applicantStatusHistory->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantStatusHistory berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $applicantStatusHistory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantStatusHistory.',
                [
                    'exception' => $e,
                    'ApplicantStatusHistory_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantStatusHistory gagal dihapus.'
            );
        }
    }
}
