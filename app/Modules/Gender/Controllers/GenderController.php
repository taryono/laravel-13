<?php

namespace App\Modules\Gender\Controllers;

use App\DataTables\GenderDataTable;
use App\Http\Controllers\MainController;
use Models\Gender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GenderController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Gender());
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
        GenderDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /gender?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Gender::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $gender = new Gender();
        $result = $this->getForm($gender);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Gender::create',
            compact('gender', 'forms', 'groupedForms')
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
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Gender::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Gender berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Gender.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Gender gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $gender = $this->model->findOrFail($id);
        $result = $this->getForm($gender);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Gender::edit',
            compact('gender', 'forms', 'groupedForms')
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
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $gender = $this->model->findOrFail($id);
            DB::transaction(function () use ($gender, $validated) {
                $gender->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Gender berhasil diperbarui.',
                $gender
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Gender.',
                [
                    'exception' => $e,
                    'Gender_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Gender gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $gender = $this->model->findOrFail($id);

            DB::transaction(function () use ($gender) {
                $gender->delete();
            });

            return $this->successResponse(
                $request,
                'Data Gender berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $gender->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Gender.',
                [
                    'exception' => $e,
                    'Gender_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Gender gagal dihapus.'
            );
        }
    }
}
