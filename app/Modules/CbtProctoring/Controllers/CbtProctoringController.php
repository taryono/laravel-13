<?php

namespace App\Modules\CbtProctoring\Controllers;

use App\DataTables\CbtProctoringDataTable;
use App\Http\Controllers\MainController;
use Models\CbtProctoring;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CbtProctoringController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CbtProctoring());
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
        CbtProctoringDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cbt-proctoring?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CbtProctoring::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cbtProctoring = new CbtProctoring();
        $result = $this->getForm($cbtProctoring);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtProctoring::create',
            compact('cbtProctoring', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'integer'],
            'proctor_id' => ['required', 'integer'],
            'start_proctoring' => ['required', 'string', 'max:255'],
            'end_proctoring' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'flag_reason' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CbtProctoring::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtProctoring berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CbtProctoring.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtProctoring gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cbtProctoring = $this->model->findOrFail($id);
        $result = $this->getForm($cbtProctoring);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtProctoring::edit',
            compact('cbtProctoring', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'integer'],
            'proctor_id' => ['required', 'integer'],
            'start_proctoring' => ['required', 'string', 'max:255'],
            'end_proctoring' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'flag_reason' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cbtProctoring = $this->model->findOrFail($id);
            DB::transaction(function () use ($cbtProctoring, $validated) {
                $cbtProctoring->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtProctoring berhasil diperbarui.',
                $cbtProctoring
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CbtProctoring.',
                [
                    'exception' => $e,
                    'CbtProctoring_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtProctoring gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cbtProctoring = $this->model->findOrFail($id);

            DB::transaction(function () use ($cbtProctoring) {
                $cbtProctoring->delete();
            });

            return $this->successResponse(
                $request,
                'Data CbtProctoring berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cbtProctoring->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CbtProctoring.',
                [
                    'exception' => $e,
                    'CbtProctoring_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtProctoring gagal dihapus.'
            );
        }
    }
}
