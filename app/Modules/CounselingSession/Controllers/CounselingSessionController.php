<?php

namespace App\Modules\CounselingSession\Controllers;

use App\DataTables\CounselingSessionDataTable;
use App\Http\Controllers\MainController;
use Models\CounselingSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CounselingSessionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CounselingSession());
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
        CounselingSessionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /counseling-session?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CounselingSession::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $counselingSession = new CounselingSession();
        $result = $this->getForm($counselingSession);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CounselingSession::create',
            compact('counselingSession', 'forms', 'groupedForms')
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
            'counselor_id' => ['required', 'integer'],
            'session_date' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'problem' => ['required', 'string', 'max:255'],
            'action' => ['required', 'string', 'max:255'],
            'follow_up' => ['required', 'string', 'max:255'],
            'follow_up_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_confidential' => ['required', 'string', 'max:255'],
            'parent_involved' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CounselingSession::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CounselingSession berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CounselingSession.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CounselingSession gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $counselingSession = $this->model->findOrFail($id);
        $result = $this->getForm($counselingSession);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CounselingSession::edit',
            compact('counselingSession', 'forms', 'groupedForms')
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
            'counselor_id' => ['required', 'integer'],
            'session_date' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'problem' => ['required', 'string', 'max:255'],
            'action' => ['required', 'string', 'max:255'],
            'follow_up' => ['required', 'string', 'max:255'],
            'follow_up_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_confidential' => ['required', 'string', 'max:255'],
            'parent_involved' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $counselingSession = $this->model->findOrFail($id);
            DB::transaction(function () use ($counselingSession, $validated) {
                $counselingSession->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CounselingSession berhasil diperbarui.',
                $counselingSession
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CounselingSession.',
                [
                    'exception' => $e,
                    'CounselingSession_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CounselingSession gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $counselingSession = $this->model->findOrFail($id);

            DB::transaction(function () use ($counselingSession) {
                $counselingSession->delete();
            });

            return $this->successResponse(
                $request,
                'Data CounselingSession berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $counselingSession->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CounselingSession.',
                [
                    'exception' => $e,
                    'CounselingSession_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CounselingSession gagal dihapus.'
            );
        }
    }
}
