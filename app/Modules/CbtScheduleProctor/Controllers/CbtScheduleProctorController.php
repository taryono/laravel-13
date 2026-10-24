<?php

namespace App\Modules\CbtScheduleProctor\Controllers;

use App\DataTables\CbtScheduleProctorDataTable;
use App\Http\Controllers\MainController;
use Models\CbtScheduleProctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CbtScheduleProctorController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CbtScheduleProctor());
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
        CbtScheduleProctorDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cbt-schedule-proctor?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CbtScheduleProctor::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cbtScheduleProctor = new CbtScheduleProctor();
        $result = $this->getForm($cbtScheduleProctor);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtScheduleProctor::create',
            compact('cbtScheduleProctor', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'schedule_id' => ['required', 'integer'],
            'proctor_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CbtScheduleProctor::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtScheduleProctor berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CbtScheduleProctor.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtScheduleProctor gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cbtScheduleProctor = $this->model->findOrFail($id);
        $result = $this->getForm($cbtScheduleProctor);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtScheduleProctor::edit',
            compact('cbtScheduleProctor', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'schedule_id' => ['required', 'integer'],
            'proctor_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
        ]);

        try {
            $cbtScheduleProctor = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cbtScheduleProctor,
                $validated
            ) {
                $cbtScheduleProctor->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtScheduleProctor berhasil diperbarui.',
                $cbtScheduleProctor
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CbtScheduleProctor.',
                [
                    'exception' => $e,
                    'CbtScheduleProctor_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtScheduleProctor gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cbtScheduleProctor = $this->model->findOrFail($id);

            DB::transaction(function () use ($cbtScheduleProctor) {
                $cbtScheduleProctor->delete();
            });

            return $this->successResponse(
                $request,
                'Data CbtScheduleProctor berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cbtScheduleProctor->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CbtScheduleProctor.',
                [
                    'exception' => $e,
                    'CbtScheduleProctor_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtScheduleProctor gagal dihapus.'
            );
        }
    } 
}
