<?php

namespace App\Modules\Yudisium\Controllers;

use App\DataTables\YudisiumDataTable;
use App\Http\Controllers\MainController;
use Models\Yudisium;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class YudisiumController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Yudisium());
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
        YudisiumDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /yudisium?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Yudisium::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $yudisium = new Yudisium();
        $result = $this->getForm($yudisium);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Yudisium::create',
            compact('yudisium', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'decree_number' => ['required', 'string', 'max:255'],
            'decree_date' => ['required', 'string', 'max:255'],
            'national_diploma_number' => ['required', 'string', 'max:255'],
            'graduation_date' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Yudisium::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Yudisium berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Yudisium.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Yudisium gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $yudisium = $this->model->findOrFail($id);
        $result = $this->getForm($yudisium);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Yudisium::edit',
            compact('yudisium', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'decree_number' => ['required', 'string', 'max:255'],
            'decree_date' => ['required', 'string', 'max:255'],
            'national_diploma_number' => ['required', 'string', 'max:255'],
            'graduation_date' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $yudisium = $this->model->findOrFail($id);
            DB::transaction(function () use ($yudisium, $validated) {
                $yudisium->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Yudisium berhasil diperbarui.',
                $yudisium
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Yudisium.',
                [
                    'exception' => $e,
                    'Yudisium_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Yudisium gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $yudisium = $this->model->findOrFail($id);

            DB::transaction(function () use ($yudisium) {
                $yudisium->delete();
            });

            return $this->successResponse(
                $request,
                'Data Yudisium berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $yudisium->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Yudisium.',
                [
                    'exception' => $e,
                    'Yudisium_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Yudisium gagal dihapus.'
            );
        }
    }
}
