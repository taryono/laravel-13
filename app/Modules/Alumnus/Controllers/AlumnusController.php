<?php

namespace App\Modules\Alumnus\Controllers;

use App\DataTables\AlumnusDataTable;
use App\Http\Controllers\MainController;
use Models\Alumnus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AlumnusController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Alumnus());
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
        AlumnusDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /alumnus?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Alumnus::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $alumnus = new Alumnus();
        $result = $this->getForm($alumnus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Alumnus::create',
            compact('alumnus', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'graduation_year' => ['required', 'string', 'max:255'],
            'current_activity' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'industry_sector' => ['required', 'string', 'max:255'],
            'monthly_income_range' => ['required', 'string', 'max:255'],
            'university_name' => ['required', 'string', 'max:255'],
            'university_major' => ['required', 'string', 'max:255'],
            'current_city' => ['required', 'string', 'max:255'],
            'linkedin_url' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Alumnus::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Alumnus berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Alumnus.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Alumnus gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $alumnus = $this->model->findOrFail($id);
        $result = $this->getForm($alumnus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Alumnus::edit',
            compact('alumnus', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'graduation_year' => ['required', 'string', 'max:255'],
            'current_activity' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'industry_sector' => ['required', 'string', 'max:255'],
            'monthly_income_range' => ['required', 'string', 'max:255'],
            'university_name' => ['required', 'string', 'max:255'],
            'university_major' => ['required', 'string', 'max:255'],
            'current_city' => ['required', 'string', 'max:255'],
            'linkedin_url' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $alumnus = $this->model->findOrFail($id);
            DB::transaction(function () use ($alumnus, $validated) {
                $alumnus->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Alumnus berhasil diperbarui.',
                $alumnus
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Alumnus.',
                [
                    'exception' => $e,
                    'Alumnus_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Alumnus gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $alumnus = $this->model->findOrFail($id);

            DB::transaction(function () use ($alumnus) {
                $alumnus->delete();
            });

            return $this->successResponse(
                $request,
                'Data Alumnus berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $alumnus->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Alumnus.',
                [
                    'exception' => $e,
                    'Alumnus_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Alumnus gagal dihapus.'
            );
        }
    }
}
