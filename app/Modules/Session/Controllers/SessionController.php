<?php

namespace App\Modules\Session\Controllers;

use App\DataTables\SessionDataTable;
use App\Http\Controllers\MainController;
use Models\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SessionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Session());
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
        SessionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /session?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Session::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $session = new Session();
        $result = $this->getForm($session);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Session::create',
            compact('session', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'user_id' => ['required', 'integer'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'payload' => ['required', 'string', 'max:255'],
            'last_activiy' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Session::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Session berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Session.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Session gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $session = $this->model->findOrFail($id);
        $result = $this->getForm($session);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Session::edit',
            compact('session', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'user_id' => ['required', 'integer'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'payload' => ['required', 'string', 'max:255'],
            'last_activiy' => ['required', 'string', 'max:255'],
        ]);

        try {
            $session = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $session,
                $validated
            ) {
                $session->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Session berhasil diperbarui.',
                $session
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Session.',
                [
                    'exception' => $e,
                    'Session_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Session gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $session = $this->model->findOrFail($id);

            DB::transaction(function () use ($session) {
                $session->delete();
            });

            return $this->successResponse(
                $request,
                'Data Session berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $session->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Session.',
                [
                    'exception' => $e,
                    'Session_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Session gagal dihapus.'
            );
        }
    } 
}
