<?php

namespace App\Modules\SoroganSession\Controllers;

use App\DataTables\SoroganSessionDataTable;
use App\Http\Controllers\MainController;
use Models\SoroganSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SoroganSessionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SoroganSession());
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
        SoroganSessionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /sorogan-session?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SoroganSession::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $soroganSession = new SoroganSession();
        $result = $this->getForm($soroganSession);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SoroganSession::create',
            compact('soroganSession', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'title' => ['required', 'string', 'max:255'],
            'session_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'teachername' => ['required', 'string', 'max:255'],
            'employeename' => ['required', 'string', 'max:255'],
            'kitab_referencename' => ['required', 'string', 'max:255'],
            'dormitoryname' => ['required', 'string', 'max:255'],
            'roomname' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'total_participants' => ['required', 'string', 'max:255'],
            'present_count' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return SoroganSession::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SoroganSession berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SoroganSession.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SoroganSession gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $soroganSession = $this->model->findOrFail($id);
        $result = $this->getForm($soroganSession);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SoroganSession::edit',
            compact('soroganSession', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'title' => ['required', 'string', 'max:255'],
            'session_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'teachername' => ['required', 'string', 'max:255'],
            'employeename' => ['required', 'string', 'max:255'],
            'kitab_referencename' => ['required', 'string', 'max:255'],
            'dormitoryname' => ['required', 'string', 'max:255'],
            'roomname' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'total_participants' => ['required', 'string', 'max:255'],
            'present_count' => ['required', 'string', 'max:255'],
        ]);

        try {
            $soroganSession = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $soroganSession,
                $validated
            ) {
                $soroganSession->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SoroganSession berhasil diperbarui.',
                $soroganSession
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui SoroganSession.',
                [
                    'exception' => $e,
                    'SoroganSession_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SoroganSession gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $soroganSession = $this->model->findOrFail($id);

            DB::transaction(function () use ($soroganSession) {
                $soroganSession->delete();
            });

            return $this->successResponse(
                $request,
                'Data SoroganSession berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $soroganSession->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SoroganSession.',
                [
                    'exception' => $e,
                    'SoroganSession_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SoroganSession gagal dihapus.'
            );
        }
    } 
}
