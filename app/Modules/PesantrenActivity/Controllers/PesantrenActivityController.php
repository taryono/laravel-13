<?php

namespace App\Modules\PesantrenActivity\Controllers;

use App\DataTables\PesantrenActivityDataTable;
use App\Http\Controllers\MainController;
use Models\PesantrenActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PesantrenActivityController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PesantrenActivity());
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
        PesantrenActivityDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /pesantren-activity?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PesantrenActivity::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $pesantrenActivity = new PesantrenActivity();
        $result = $this->getForm($pesantrenActivity);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PesantrenActivity::create',
            compact('pesantrenActivity', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'dormitory_id' => ['required', 'integer'],
            'room_id' => ['required', 'integer'],
            'kitab_reference_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'activity_type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'objectives' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'speaker_name' => ['required', 'string', 'max:255'],
            'speaker_phone' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'registered' => ['required', 'string', 'max:255'],
            'attended' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_public' => ['required', 'string', 'max:255'],
            'requires_registration' => ['required', 'string', 'max:255'],
            'registration_open' => ['required', 'string', 'max:255'],
            'registration_close' => ['required', 'string', 'max:255'],
            'budget' => ['required', 'string', 'max:255'],
            'actual_cost' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'poster_path' => ['required', 'string', 'max:255'],
            'documentation_paths' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PesantrenActivity::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PesantrenActivity berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PesantrenActivity.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PesantrenActivity gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $pesantrenActivity = $this->model->findOrFail($id);
        $result = $this->getForm($pesantrenActivity);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PesantrenActivity::edit',
            compact('pesantrenActivity', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'dormitory_id' => ['required', 'integer'],
            'room_id' => ['required', 'integer'],
            'kitab_reference_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'activity_type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'objectives' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'speaker_name' => ['required', 'string', 'max:255'],
            'speaker_phone' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'registered' => ['required', 'string', 'max:255'],
            'attended' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_public' => ['required', 'string', 'max:255'],
            'requires_registration' => ['required', 'string', 'max:255'],
            'registration_open' => ['required', 'string', 'max:255'],
            'registration_close' => ['required', 'string', 'max:255'],
            'budget' => ['required', 'string', 'max:255'],
            'actual_cost' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'poster_path' => ['required', 'string', 'max:255'],
            'documentation_paths' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $pesantrenActivity = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $pesantrenActivity,
                $validated
            ) {
                $pesantrenActivity->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PesantrenActivity berhasil diperbarui.',
                $pesantrenActivity
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui PesantrenActivity.',
                [
                    'exception' => $e,
                    'PesantrenActivity_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PesantrenActivity gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $pesantrenActivity = $this->model->findOrFail($id);

            DB::transaction(function () use ($pesantrenActivity) {
                $pesantrenActivity->delete();
            });

            return $this->successResponse(
                $request,
                'Data PesantrenActivity berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $pesantrenActivity->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PesantrenActivity.',
                [
                    'exception' => $e,
                    'PesantrenActivity_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PesantrenActivity gagal dihapus.'
            );
        }
    } 
}
