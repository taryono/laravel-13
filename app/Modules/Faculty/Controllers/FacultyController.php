<?php

namespace App\Modules\Faculty\Controllers;

use App\DataTables\FacultyDataTable;
use App\Http\Controllers\MainController;
use Models\Faculty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FacultyController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Faculty());
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
        FacultyDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /faculty?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Faculty::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $faculty = new Faculty();
        $result = $this->getForm($faculty);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Faculty::create',
            compact('faculty', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'school_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Faculty::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Faculty berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Faculty.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Faculty gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $faculty = $this->model->findOrFail($id);
        $result = $this->getForm($faculty);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Faculty::edit',
            compact('faculty', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'school_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
        ]);

        try {
            $faculty = $this->model->findOrFail($id);
            DB::transaction(function () use ($faculty, $validated) {
                $faculty->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Faculty berhasil diperbarui.',
                $faculty
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Faculty.',
                [
                    'exception' => $e,
                    'Faculty_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Faculty gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $faculty = $this->model->findOrFail($id);

            DB::transaction(function () use ($faculty) {
                $faculty->delete();
            });

            return $this->successResponse(
                $request,
                'Data Faculty berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $faculty->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Faculty.',
                [
                    'exception' => $e,
                    'Faculty_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Faculty gagal dihapus.'
            );
        }
    }
}
