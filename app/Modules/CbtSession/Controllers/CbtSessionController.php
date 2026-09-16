<?php

namespace App\Modules\CbtSession\Controllers;

use App\DataTables\CbtSessionDataTable;
use App\Http\Controllers\MainController;
use Models\CbtSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CbtSessionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CbtSession());
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
        CbtSessionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cbt-session?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CbtSession::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cbtSession = new CbtSession();
        $result = $this->getForm($cbtSession);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtSession::create',
            compact('cbtSession', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'exam_package_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'schedule_id' => ['required', 'integer'],
            'class_id' => ['required', 'integer'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_seconds' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CbtSession::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtSession berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CbtSession.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtSession gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cbtSession = $this->model->findOrFail($id);
        $result = $this->getForm($cbtSession);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtSession::edit',
            compact('cbtSession', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'exam_package_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'schedule_id' => ['required', 'integer'],
            'class_id' => ['required', 'integer'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_seconds' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cbtSession = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cbtSession,
                $validated
            ) {
                $cbtSession->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtSession berhasil diperbarui.',
                $cbtSession
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CbtSession.',
                [
                    'exception' => $e,
                    'CbtSession_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtSession gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cbtSession = $this->model->findOrFail($id);

            DB::transaction(function () use ($cbtSession) {
                $cbtSession->delete();
            });

            return $this->successResponse(
                $request,
                'Data CbtSession berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cbtSession->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CbtSession.',
                [
                    'exception' => $e,
                    'CbtSession_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtSession gagal dihapus.'
            );
        }
    } 
}
