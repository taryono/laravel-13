<?php

namespace App\Modules\Subdistrict\Controllers;

use App\DataTables\SubdistrictDataTable;
use App\Http\Controllers\MainController;
use Models\Subdistrict;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SubdistrictController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Subdistrict());
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
        SubdistrictDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /subdistrict?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Subdistrict::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $subdistrict = new Subdistrict();
        $result = $this->getForm($subdistrict);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Subdistrict::create',
            compact('subdistrict', 'forms', 'groupedForms')
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
            'district_id' => ['required', 'integer'],
            'zip' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'elevation' => ['required', 'string', 'max:255'],
            'timezone' => ['required', 'string', 'max:255'],
            'ordering' => ['required', 'string', 'max:255'],
            'last_id' => ['required', 'integer'],
            'is_free_text' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'is_user_input' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Subdistrict::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Subdistrict berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Subdistrict.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Subdistrict gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $subdistrict = $this->model->findOrFail($id);
        $result = $this->getForm($subdistrict);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Subdistrict::edit',
            compact('subdistrict', 'forms', 'groupedForms')
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
            'district_id' => ['required', 'integer'],
            'zip' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'elevation' => ['required', 'string', 'max:255'],
            'timezone' => ['required', 'string', 'max:255'],
            'ordering' => ['required', 'string', 'max:255'],
            'last_id' => ['required', 'integer'],
            'is_free_text' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'is_user_input' => ['required', 'string', 'max:255'],
        ]);

        try {
            $subdistrict = $this->model->findOrFail($id);
            DB::transaction(function () use ($subdistrict, $validated) {
                $subdistrict->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Subdistrict berhasil diperbarui.',
                $subdistrict
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Subdistrict.',
                [
                    'exception' => $e,
                    'Subdistrict_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Subdistrict gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $subdistrict = $this->model->findOrFail($id);

            DB::transaction(function () use ($subdistrict) {
                $subdistrict->delete();
            });

            return $this->successResponse(
                $request,
                'Data Subdistrict berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $subdistrict->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Subdistrict.',
                [
                    'exception' => $e,
                    'Subdistrict_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Subdistrict gagal dihapus.'
            );
        }
    }
}
