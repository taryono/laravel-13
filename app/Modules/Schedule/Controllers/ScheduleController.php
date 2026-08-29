<?php

namespace App\Modules\Schedule\Controllers;

use App\DataTables\ScheduleDataTable;
use App\Http\Controllers\MainController;
use Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ScheduleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Schedule());
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
        ScheduleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /schedule?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Schedule::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $schedule = new Schedule();
        $result = $this->getForm($schedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Schedule::create',
            compact('schedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Schedule::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Schedule berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Schedule.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Schedule gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $schedule = $this->model->findOrFail($id);
        $result = $this->getForm($schedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Schedule::edit',
            compact('schedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $schedule = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $schedule,
                $validated
            ) {
                $schedule->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Schedule berhasil diperbarui.',
                $schedule
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $schedule->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $schedule belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui Schedule.',
                [
                    'exception' => $e,
                    'Schedule_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Schedule gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $schedule = $this->model->findOrFail($id);

            DB::transaction(function () use ($schedule) {
                $schedule->delete();
            });

            return $this->successResponse(
                $request,
                'Data Schedule berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $schedule->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Schedule.',
                [
                    'exception' => $e,
                    'Schedule_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Schedule gagal dihapus.'
            );
        }
    }

    /**
     * Return successful response.
     */
    protected function successResponse(
        Request $request,
        string $message,
        $data = null
    ): JsonResponse|RedirectResponse {

        if ($this->isAjaxRequest($request)) {

            $response = [
                'success' => true,
                'message' => $message,
            ];

            if ($data !== null) {
                $response['data'] = $data;
            }

            return response()->json($response);
        }

        return redirect()
            ->route('schedule.index')
            ->with('success', $message);
    }

    /**
     * Return failed response.
     */
    protected function errorResponse(
        Request $request,
        string $message,
        int $status = 500
    ): JsonResponse|RedirectResponse {

        if ($this->isAjaxRequest($request)) {

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $message);
    }

    /**
     * Determine whether the request expects an AJAX response.
     */
    protected function isAjaxRequest(Request $request): bool
    {
        return $request->ajax() || $request->expectsJson();
    }
}
